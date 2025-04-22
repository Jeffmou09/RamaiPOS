<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokProduk;
use App\Models\Produk;
use App\Models\StokOpname;
use Illuminate\Support\Str;

class StokProdukController extends Controller
{
    public function index()
    {
        $produkList = Produk::all(); 
        return view('produk.inputstok', compact('produkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'aksi_stok' => 'required|in:tambah,kurang',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required_if:aksi_stok,tambah|numeric|min:0',
        ]);
        
        $produk = Produk::findOrFail($request->produk_id);
        
        if ($request->aksi_stok === 'tambah') {
            $stokLama = $produk->stok;
            $hargaBeliLama = $produk->harga_beli ?? 0;
            $nilaiStokLama = $stokLama * $hargaBeliLama;
            
            $produk->stok += $request->jumlah;
            
            $nilaiStokBaru = $request->jumlah * $request->harga_beli;
            $totalNilai = $nilaiStokLama + $nilaiStokBaru;
            $totalStok = $produk->stok;
            
            if ($totalStok > 0) {
                $produk->harga_beli = round($totalNilai / $totalStok, 2);
            }
        } else {
            if ($produk->stok < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk pengurangan!');
            }
            $produk->stok -= $request->jumlah;
        }
        
        $produk->save();
        
        // Generate StokOpname ID (S + YYYYMMDD + 0001 format)
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
        
        // Record stock change in StokOpname table
        StokOpname::create([
            'id' => $nextId,
            'id_produk' => $request->produk_id,
            'jenis_perubahan' => $request->aksi_stok === 'tambah' ? 'Penambahan' : 'Pengurangan',
            'jumlah_perubahan' => $request->jumlah
        ]);
        
        return redirect()->back()->with('success', 'Stok berhasil diperbarui!');
    }

    public function detail($id)
    {
        $produk = Produk::findOrFail($id);
        $stokOpnameList = StokOpname::where('id_produk', $id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('produk.stokdetail', compact('produk', 'stokOpnameList'));
    }
}
