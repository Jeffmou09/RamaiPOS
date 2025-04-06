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
        // Validate request
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'items_json' => 'required|json',
            'jumlah_produk_terjual' => 'required|integer|min:1',
            'total_transaksi' => 'required|integer|min:0',
            'diskon' => 'nullable|integer|min:0',
        ]);
    
        // Parse items from JSON
        $items = json_decode($request->items_json, true);
    
        // Begin transaction
        DB::beginTransaction();
        try {
            // Create transaksi record
            $transaksi = new Transaksi();
            $transaksi->id = Str::uuid(); // Generate UUID
            $transaksi->id_customer = $request->customer_id ?: null; // Set null if empty
            $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
            $transaksi->diskon = $request->diskon ?? 0;
            $transaksi->jumlah_produk_terjual = $request->jumlah_produk_terjual;
            $transaksi->total_transaksi = $request->total_transaksi;
            $transaksi->save();
    
            // Create detail transaksi records and update stock
            foreach ($items as $item) {
                // Add detail transaction
                $detail = new DetailTransaksi();
                $detail->id = Str::uuid(); // Generate UUID
                $detail->id_produk = $item['produk_id'];
                $detail->id_transaksi = $transaksi->id;
                $detail->jumlah_barang = $item['jumlah'];
                $detail->jenis_satuan = $item['jenis_satuan'];
                $detail->sub_total = $item['sub_total'];
                $detail->save();
    
                // Get related product stock
                $stokProduk = StokProduk::where('id_produk', $item['produk_id'])->first();
                
                if (!$stokProduk) {
                    throw new \Exception('Stok produk tidak ditemukan untuk produk ID: ' . $item['produk_id']);
                }
    
                $produk = Produk::find($item['produk_id']);
                
                if ($item['jenis_satuan'] == $produk->satuan) {
                    if ($stokProduk->stok_satuan_utama < $item['jumlah']) {
                        throw new \Exception('Stok tidak mencukupi untuk produk: ' . $produk->nama_produk);
                    }
                    
                    $stokProduk->stok_satuan_utama -= $item['jumlah'];
                    
                    $stokOpname = new StokOpname();
                    $stokOpname->id = Str::uuid();
                    $stokOpname->id_stok = $stokProduk->id;
                    $stokOpname->jenis_perubahan = 'Pengurangan';
                    $stokOpname->jumlah_perubahan = $item['jumlah'];
                    $stokOpname->satuan = $item['jenis_satuan'];
                    $stokOpname->save();
                } else if ($item['jenis_satuan'] == $produk->jenis_isi) {
                    $jumlahDibutuhkan = $item['jumlah'];
                    
                    if ($stokProduk->stok_satuan_isi >= $jumlahDibutuhkan) {
                        $stokProduk->stok_satuan_isi -= $jumlahDibutuhkan;
                        
                        $stokOpname = new StokOpname();
                        $stokOpname->id = Str::uuid();
                        $stokOpname->id_stok = $stokProduk->id;
                        $stokOpname->jenis_perubahan = 'Pengurangan';
                        $stokOpname->jumlah_perubahan = $jumlahDibutuhkan;
                        $stokOpname->satuan = $item['jenis_satuan'];
                        $stokOpname->save();
                    } else {
                        $isiPerSatuan = $produk->isi_per_satuan;
                        
                        if ($isiPerSatuan <= 0) {
                            throw new \Exception('Nilai isi per satuan tidak valid untuk produk: ' . $produk->nama_produk);
                        }
                        
                        $kurangDariSubUnit = $jumlahDibutuhkan - $stokProduk->stok_satuan_isi;
                        $unitBesarDibutuhkan = ceil($kurangDariSubUnit / $isiPerSatuan);
                        
                        if ($stokProduk->stok_satuan_utama < $unitBesarDibutuhkan) {
                            throw new \Exception('Stok tidak mencukupi untuk produk: ' . $produk->nama_produk . 
                                                '. Dibutuhkan ' . $unitBesarDibutuhkan . ' ' . $produk->satuan . ' tambahan.');
                        }
                        
                        $stokProduk->stok_satuan_utama -= $unitBesarDibutuhkan;
                        
                        $stokOpnameUtama = new StokOpname();
                        $stokOpnameUtama->id = Str::uuid();
                        $stokOpnameUtama->id_stok = $stokProduk->id;
                        $stokOpnameUtama->jenis_perubahan = 'Pengurangan';
                        $stokOpnameUtama->jumlah_perubahan = $unitBesarDibutuhkan;
                        $stokOpnameUtama->satuan = $produk->satuan;
                        $stokOpnameUtama->save();
                        
                        $subUnitDitambahkan = $unitBesarDibutuhkan * $isiPerSatuan;
                        
                        $stokOpnamePenambahan = new StokOpname();
                        $stokOpnamePenambahan->id = Str::uuid();
                        $stokOpnamePenambahan->id_stok = $stokProduk->id;
                        $stokOpnamePenambahan->jenis_perubahan = 'Penambahan';
                        $stokOpnamePenambahan->jumlah_perubahan = $subUnitDitambahkan;
                        $stokOpnamePenambahan->satuan = $produk->jenis_isi;
                        $stokOpnamePenambahan->save();
                        
                        $totalSubUnitSetelahKonversi = $stokProduk->stok_satuan_isi + $subUnitDitambahkan;
                        
                        $stokProduk->stok_satuan_isi = $totalSubUnitSetelahKonversi - $jumlahDibutuhkan;
                        
                        $stokOpnamePengurangan = new StokOpname();
                        $stokOpnamePengurangan->id = Str::uuid();
                        $stokOpnamePengurangan->id_stok = $stokProduk->id;
                        $stokOpnamePengurangan->jenis_perubahan = 'Pengurangan';
                        $stokOpnamePengurangan->jumlah_perubahan = $jumlahDibutuhkan;
                        $stokOpnamePengurangan->satuan = $produk->jenis_isi;
                        $stokOpnamePengurangan->save();
                    }
                } else {
                    throw new \Exception('Jenis satuan tidak valid untuk produk: ' . $produk->nama_produk);
                }
                
                // Save the updated stock
                $stokProduk->save();
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
        
        // Urutkan berdasarkan tanggal transaksi terbaru
        $transaksi = $query->orderBy('tanggal_transaksi', 'desc')
                   ->orderBy('created_at', 'desc')
                   ->get();
    
        return view('transaksi.history', compact('transaksi'));
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
