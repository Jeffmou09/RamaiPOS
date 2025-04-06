<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\StokProduk;
use App\Models\StokOpname;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::orderBy('created_at', 'desc')->get();
        return view('produk.daftarproduk', compact('produks'));
    }

    public function create()
    {
        return view('produk.tambahproduk');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'satuan' => 'required|in:SAK,DUS',
            'isi_per_satuan' => 'required|integer|min:1',
            'jenis_isi' => 'required|in:PCS,KG',
            'harga_beli_per_satuan' => 'required|integer|min:1',
            'harga_jual_per_satuan' => 'required|integer|min:1',
            'harga_jual_per_isi' => 'required|integer|min:1',
            'stok_satuan_utama' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $produkId = Str::uuid()->toString();

            // Hitung otomatis harga beli per isi
            $harga_beli_per_isi = $request->harga_beli_per_satuan / $request->isi_per_satuan;

            // Simpan data ke tabel produk
            $produk = Produk::create([
                'id' => $produkId,
                'nama_produk' => $request->nama_produk,
                'satuan' => $request->satuan,
                'isi_per_satuan' => $request->isi_per_satuan,
                'jenis_isi' => $request->jenis_isi,
                'harga_beli_per_satuan' => $request->harga_beli_per_satuan,
                'harga_beli_per_isi' => $harga_beli_per_isi,
                'harga_jual_per_satuan' => $request->harga_jual_per_satuan,
                'harga_jual_per_isi' => $request->harga_jual_per_isi,
            ]);

            // Simpan data ke tabel stok_produk
            $stokId = Str::uuid()->toString();
            $stokProduk = StokProduk::create([
                'id' => $stokId,
                'id_produk' => $produkId,
                'stok_satuan_utama' => $request->stok_satuan_utama,
                'stok_satuan_isi' => 0,
            ]);

            // Simpan data ke tabel stok_opname
            StokOpname::create([
                'id' => Str::uuid()->toString(),
                'id_stok' => $stokId,
                'jenis_perubahan' => 'Penambahan',
                'jumlah_perubahan' => $request->stok_satuan_utama,
                'satuan' => $request->satuan,
            ]);

            DB::commit(); 

            return redirect()->route('daftarproduk')->with('success', 'Produk dan stok berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika terjadi error
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.editproduk', compact('produk'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'satuan' => 'required|in:SAK,DUS',
            'isi_per_satuan' => 'required|integer|min:1',
            'jenis_isi' => 'required|in:PCS,KG',
            'harga_beli_per_satuan' => 'required|integer|min:1',
            'harga_jual_per_satuan' => 'required|integer|min:1',
            'harga_jual_per_isi' => 'required|integer|min:1',
        ]);

        $produk = Produk::findOrFail($id);

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'satuan' => $request->satuan,
            'isi_per_satuan' => $request->isi_per_satuan,
            'jenis_isi' => $request->jenis_isi,
            'harga_beli_per_satuan' => $request->harga_beli_per_satuan,
            'harga_beli_per_isi' => $request->harga_beli_per_satuan / $request->isi_per_satuan, 
            'harga_jual_per_satuan' => $request->harga_jual_per_satuan,
            'harga_jual_per_isi' => $request->harga_jual_per_isi,
        ]);

        return redirect()->route('daftarproduk')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        StokProduk::where('id_produk', $id)->delete();
        StokOpname::where('id_stok', $id)->delete();

        $produk->delete();

        return redirect()->route('daftarproduk')->with('success', 'Produk berhasil dihapus!');
    }
}