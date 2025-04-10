<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokProduk;
use App\Models\StokOpname;
use App\Models\Produk;
use App\Models\Customer;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Support\Str;
use DB;
use PDF;

class TransaksiController extends Controller
{
    public function index()
    {
        $customerList = Customer::all(); 
        $produkList = Produk::all(); 

        return view('transaksi.transaksi', compact('customerList', 'produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'items_json' => 'required|json',
            'jumlah_produk_terjual' => 'required|integer|min:1',
            'total_transaksi' => 'required|integer|min:0',
            'diskon' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string|max:255',
        ]);
        
        $items = json_decode($request->items_json, true);
        DB::beginTransaction();
        
        try {
            $today = date('Ymd');
            $latestTransaction = Transaksi::where('id', 'like', "T{$today}%")
                ->orderBy('id', 'desc')
                ->first();
                
            if ($latestTransaction) {
                // Extract sequence number and increment it
                $sequence = (int)substr($latestTransaction->id, -5);
                $nextSequence = $sequence + 1;
            } else {
                // First transaction of the day
                $nextSequence = 1;
            }
            
            // Create the transaction ID (T + DDMMYYYY + 5-digit sequence)
            $transactionId = 'T' . $today . str_pad($nextSequence, 5, '0', STR_PAD_LEFT);
            
            // Create transaksi record
            $transaksi = new Transaksi();
            $transaksi->id = $transactionId;
            $transaksi->id_customer = $request->customer_id ?: null; // Set null if empty
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
            $transaksi->diskon = $request->diskon ?? 0;
            $transaksi->jumlah_produk_terjual = $request->jumlah_produk_terjual;
            $transaksi->total_transaksi = $request->total_transaksi;
            $transaksi->deskripsi = $request->deskripsi;
            $transaksi->save();
            
            foreach ($items as $index => $item) {
                // Add detail transaction with sequential ID
                $detail = new DetailTransaksi();
                $detailSequence = $index + 1;
                $detail->id = $transactionId . '-' . str_pad($detailSequence, 3, '0', STR_PAD_LEFT);
                $detail->id_produk = $item['produk_id'];
                $detail->id_transaksi = $transaksi->id;
                $detail->jumlah_barang = $item['jumlah'];
                $detail->sub_total = $item['sub_total'];
                $detail->save();
                
                // Get related product
                $produk = Produk::find($item['produk_id']);
                if (!$produk) {
                    throw new \Exception('Produk tidak ditemukan untuk ID: ' . $item['produk_id']);
                }
                
                // Check if stock is sufficient
                if ($produk->stok < $item['jumlah']) {
                    throw new \Exception('Stok tidak mencukupi untuk produk: ' . $produk->nama_produk);
                }
                
                // Update product stock
                $produk->stok -= $item['jumlah'];
                $produk->save();
                
                $today = now()->format('Ymd');
                $prefix = 'S' . $today;
                
                // Find the last StokOpname with today's date prefix
                $lastStokOpname = StokOpname::where('id', 'like', $prefix . '%')
                                            ->orderBy('id', 'desc')
                                            ->first();
                
                if ($lastStokOpname) {
                    // Extract the numeric part (last 4 characters) and increment
                    $lastNumber = (int) substr($lastStokOpname->id, -4);
                    $nextNumber = $lastNumber + 1;
                    $nextId = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                } else {
                    // First record for today
                    $nextId = $prefix . '0001';
                }
                
                $stokOpname = new StokOpname();
                $stokOpname->id = $nextId;
                $stokOpname->id_produk = $produk->id;
                $stokOpname->jenis_perubahan = 'Pengurangan';
                $stokOpname->jumlah_perubahan = $item['jumlah'];
                $stokOpname->save();
            }
            
            DB::commit();
            session(['last_transaction_id' => $transaksi->id]);
            return redirect()->route('transaksi')
                ->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function cetakNota($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'customer'])->find($id);
        
        if (!$transaksi) {
            return abort(404);
        }
        
        $pdf = PDF::loadView('transaksi.cetak-nota', compact('transaksi'));
        return $pdf->stream('Nota-'.$id.'.pdf');
    }

    public function history(Request $request)
    {
        $periode = $request->input('periode', 'today'); // Default: hari ini
        $search = $request->input('search');
        
        $query = Transaksi::with(['detailTransaksi.produk', 'customer']);
        
        // Filter berdasarkan periode yang dipilih
        switch ($periode) {
            case 'today':
                $query->whereDate('tanggal_transaksi', now()->toDateString());
                break;
            case 'week':
                $query->where('tanggal_transaksi', '>=', now()->subDays(7)->startOfDay());
                break;
            case 'month':
                $query->where('tanggal_transaksi', '>=', now()->subDays(30)->startOfDay());
                break;
            case 'all':
                // Tidak perlu filter, tampilkan semua data
                break;
        }
        
        // Pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('customer', function($query) use ($search) {
                      $query->where('nama_customer', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->paginate(20)
                           ->appends(['periode' => $periode, 'search' => $search]);
        
        return view('transaksi.history', compact('transaksi', 'search', 'periode'));
    }

    public function detail($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'customer'])->find($id);
        if (!$transaksi) {
            return abort(404);
        }
        return view('transaksi.detail', compact('transaksi'));
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);
            
            foreach ($transaksi->detailTransaksi as $detail) {
                $detail->delete();
            }
            
            $transaksi->delete();
            
            DB::commit();
            return redirect()->route('transaksi.history')
                ->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }
}
