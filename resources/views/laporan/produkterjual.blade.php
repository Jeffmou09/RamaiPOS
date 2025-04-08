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
        .rank {
            font-weight: bold;
            text-align: center;
        }
        .highlight {
            background-color: #f9f9f9;
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
                <th width="5%">Rank</th>
                <th width="40%">Nama Produk</th>
                <th width="15%">Jumlah Terjual</th>
                <th width="25%">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @if(count($produkTerjual) > 0)
                @foreach($produkTerjual as $index => $item)
                    <tr class="{{ $index < 3 ? 'highlight' : '' }}">
                        <td class="rank">{{ $index + 1 }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td class="text-center">{{ number_format($item->total_jumlah, 0, ',', '.') }}</td>
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
                <th class="text-center">{{ number_format($total_jumlah, 0, ',', '.') }}</th>
                <th class="text-right">Rp {{ number_format($total_transaksi, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>