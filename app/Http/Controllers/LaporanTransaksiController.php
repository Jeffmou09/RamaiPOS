<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use PDF;

class LaporanTransaksiController extends Controller
{
    public function generateTransaksiPDF(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date',
        ]);
        
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        
        $transaksi = Transaksi::whereBetween('tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
            ->with('customer', 'detailTransaksi')
            ->orderBy('tanggal_transaksi', 'asc')
            ->get();
        
        $totalDiskon = $transaksi->sum(function ($trx) {
            return $trx->diskon ?? 0;
        });
        
        $data = [
            'transaksi' => $transaksi,
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir,
            'judul' => 'Laporan Penjualan Toko Ramai',
            'total_penjualan' => $transaksi->sum('total_transaksi'),
            'total_produk_terjual' => $transaksi->sum('jumlah_produk_terjual'),
            'total_diskon' => $totalDiskon,
        ];
        
        $pdf = PDF::loadView('laporan.listtransaksi', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->stream('laporan-penjualan-' . date('Y-m-d') . '.pdf');
    }

    public function generateProdukPDF(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date',
        ]);
        
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        
        // Query products sold and sort by total quantity (best-selling first)
        $produkTerjual = DB::table('detail_transaksi')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id')
            ->join('produk', 'detail_transaksi.id_produk', '=', 'produk.id')
            ->select(
                'produk.id',
                'produk.nama_produk',
                DB::raw('SUM(detail_transaksi.jumlah_barang) as total_jumlah'),
                DB::raw('SUM(detail_transaksi.sub_total) as total_transaksi')
            )
            ->whereBetween('transaksi.tanggal_transaksi', [$tanggalAwal, $tanggalAkhir])
            ->groupBy('produk.id', 'produk.nama_produk')
            ->orderByDesc('total_jumlah') // Order by best-selling first
            ->get();
        
        $totalJumlah = $produkTerjual->sum('total_jumlah');
        $totalTransaksi = $produkTerjual->sum('total_transaksi');
        
        // Calculate percentage of sales for each product
        foreach ($produkTerjual as $item) {
            $item->persentase = $totalJumlah > 0 ? round(($item->total_jumlah / $totalJumlah) * 100, 2) : 0;
        }
        
        $data = [
            'produkTerjual' => $produkTerjual,
            'tanggal_awal' => $tanggalAwal,
            'tanggal_akhir' => $tanggalAkhir,
            'judul' => 'Laporan Produk Terlaris Toko Ramai',
            'total_jumlah' => $totalJumlah,
            'total_transaksi' => $totalTransaksi,
        ];
        
        $pdf = PDF::loadView('laporan.produkterjual', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->stream('laporan-produk-terlaris-' . date('Y-m-d') . '.pdf');
    }
}