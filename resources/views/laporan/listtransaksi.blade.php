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
                <th>Kode Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Customer</th>
                <th>Jumlah Produk</th>
                <th>Diskon</th>
                <th>Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @if(count($transaksi) > 0)
                @foreach($transaksi as $index => $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ date('d/m/Y', strtotime($item->tanggal_transaksi)) }}</td>
                        <td>{{ $item->customer ? $item->customer->nama_customer : 'Umum' }}</td>
                        <td class="text-right">{{ $item->jumlah_produk_terjual }}</td>
                        <td class="text-right">Rp {{ number_format($item->diskon, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_transaksi, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center">Tidak ada data transaksi dalam periode ini</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th class="text-right">{{ $total_produk_terjual }}</th>
                <th class="text-right">Rp {{ number_format($total_diskon, 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($total_penjualan, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>