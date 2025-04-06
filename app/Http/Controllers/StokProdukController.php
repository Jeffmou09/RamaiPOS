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
        $stokProduk = StokProduk::orderBy('created_at', 'desc')->get();
        return view('produk.inputstok', compact('produkList', 'stokProduk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'aksi_stok' => 'required|in:tambah,kurang',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required',
            'harga_beli' => 'required|numeric|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);
        $stokProduk = StokProduk::where('id_produk', $request->produk_id)->first();

        if (!$stokProduk) {
            $stokProduk = StokProduk::create([
                'produk_id' => $request->produk_id,
                'stok_satuan_utama' => 0,
                'stok_satuan_isi' => 0,
            ]);
        }
        if ($request->aksi_stok === 'tambah') {
            $stokProduk->stok_satuan_utama += $request->jumlah;
        } else {
            $stokProduk->stok_satuan_utama -= $request->jumlah;
        }

        $stokProduk->save();

        // Simpan riwayat stok opname
        StokOpname::create([
            'id' => Str::uuid(),
            'id_stok' => $stokProduk->id,
            'jenis_perubahan' => $request->aksi_stok === 'tambah' ? 'Penambahan' : 'Pengurangan',
            'jumlah_perubahan' => $request->jumlah,
            'satuan' => $request->satuan,
        ]);

        if ($request->aksi_stok === 'tambah') {
            $stokLama = $stokProduk->stok_satuan_utama - $request->jumlah;
            $hargaBeliLama = $produk->harga_beli_per_satuan;
            $totalStokBaru = $stokLama + $request->jumlah;
        
            $hargaBeliBaru = (($stokLama * $hargaBeliLama) + ($request->jumlah * $request->harga_beli)) / $totalStokBaru;

            $produk->harga_beli_per_satuan = round($hargaBeliBaru, 2);
            $produk->harga_beli_per_isi = round($hargaBeliBaru / $produk->isi_per_satuan, 2);
            $produk->save();
        }
        

        return redirect()->back()->with('success', 'Stok berhasil diperbarui!');
    }

    public function detail($id)
    {
        $stok = StokProduk::with('produk')->findOrFail($id);
        $stokOpname = StokOpname::where('id_stok', $id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return view('produk.stokdetail', compact('stok', 'stokOpname'));
    }
}
