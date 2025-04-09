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
        $produk = Produk::all();
        return view('produk.daftarproduk', compact('produk'));
    }

    public function create()
    {
        return view('produk.tambahproduk');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
        ]);
        
        // Create the product
        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'stok' => $request->stok,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);
        
        // Generate StokOpname ID (S000001 format)
        $lastStokOpname = StokOpname::orderBy('id', 'desc')->first();
        if ($lastStokOpname) {
            $lastId = substr($lastStokOpname->id, 1);
            $nextId = 'S' . str_pad((int)$lastId + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $nextId = 'S000001';
        }
        
        // Create StokOpname entry
        StokOpname::create([
            'id' => $nextId,
            'id_produk' => $produk->id,
            'jenis_perubahan' => 'Penambahan', // Or whatever value makes sense for new product
            'jumlah_perubahan' => $request->stok,
        ]);
        
        return redirect()->route('daftarproduk')
            ->with('success', 'Produk berhasil ditambahkan dengan ID: ' . $produk->id);
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
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
        ]);
        
        $produk = Produk::findOrFail($id);
        
        $produk->update([
            'nama_produk' => $request->nama_produk,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);
        
        return redirect()->route('daftarproduk')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        StokOpname::where('id_produk', $id)->delete();

        $produk->delete();

        return redirect()->route('daftarproduk')->with('success', 'Produk berhasil dihapus!');
    }
}