<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $judul }}</title>
<style>
body {
    font-family: Arial, sans-serif;
    font-size: 12px;
}
.header {
    text-align: center;
    margin-bottom: 20px;
}
.title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 5px;
}
.subtitle {
    font-size: 14px;
    margin-bottom: 15px;
}
.periode {
    font-size: 13px;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}
th {
    background-color: #f2f2f2;
    font-weight: bold;
}
.text-right {
    text-align: right;
}
.text-center {
    text-align: center;
}
.summary {
    margin-top: 20px;
    text-align: right;
}
.footer {
    margin-top: 30px;
    text-align: right;
}
.page-break {
    page-break-after: always;
}
</style>
</head>
<body>
<div class="header">
    <div class="title">{{ $judul }}</div>
    <div class="subtitle">Toko Ramai</div>
    <div class="periode">Periode: {{ date('d/m/Y', strtotime($tanggal_awal)) }} - {{ date('d/m/Y', strtotime($tanggal_akhir)) }}</div>
</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Jumlah Dus/Sak</th>
            <th>Jumlah Pcs/Kg</th>
            <th>Total Penjualan</th>
        </tr>
    </thead>
    <tbody>
        @if(count($produkTerjual) > 0)
            @foreach($produkTerjual as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td class="text-center">
                        @if($item->jumlah_satuan_besar > 0)
                            {{ $item->jumlah_satuan_besar }} {{ $item->satuan_besar_label }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item->jumlah_satuan_kecil > 0)
                            {{ $item->jumlah_satuan_kecil }} {{ $item->satuan_kecil_label }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($item->total_transaksi, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" style="text-align: center">Tidak ada data produk terjual dalam periode ini</td>
            </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-right">Total</th>
            <th class="text-center"></th>
            <th class="text-center"></th>
            <th class="text-right">Rp {{ number_format($total_transaksi, 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>
<div class="footer">
    <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
</div>
</body>
</html>