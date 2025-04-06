<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Transaksi</title>
    <style>
        @page {
            size: 58mm auto; /* Mengatur ukuran kertas */
            margin: 0; /* Hilangkan margin default */
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.2;
            width: 58mm;
            margin: 0;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
            padding: 2px 0; /* Padding lebih kecil untuk menghemat ruang */
        }

        .header h1 {
            margin: 0;
            font-size: 12pt;
        }

        .header p {
            margin: 2px 0; /* Jarak lebih kecil antar teks */
            font-size: 7pt; /* Ukuran alamat lebih kecil */
        }
        .info {
            font-size: 8pt;
            margin-bottom: 5px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
        }
        .items th, .items td {
            border-bottom: 1px dashed #000;
            padding: 2px;
            text-align: left;
        }
        .total {
            width: 100%;
            font-size: 9pt;
            margin-top: 5px;
            text-align: right;
        }
        .footer {
            text-align: center;
            font-size: 8pt;
            margin-top: 5px;
        }

        /* Format cetak */
        @media print {
            body {
                width: 58mm;
                margin: 0;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Toko Ramai Rambipuji</h1>
        <p>Jl.Gajah Mada 53 Dusun Krajan, Rambipuji</p>
    </div>
    
    <div class="info">
        <p>
        Tgl: {{ date('d M Y H:i:s', strtotime(now()->setTimezone('Asia/Jakarta'))) }}<br>
            Kasir: Admin<br>
            Customer: {{ $transaksi->customer ? $transaksi->customer->nama_customer : 'Umum' }}
        </p>
    </div>
    
    <table class="items">
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Jml</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi->detailTransaksi as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->produk->nama_produk }}</td>
                <td>{{ $detail->jumlah_barang }}</td>
                <td>Rp {{ number_format($detail->sub_total / $detail->jumlah_barang, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="total">
        <table width="100%">
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>Rp {{ number_format($transaksi->total_transaksi + $transaksi->diskon, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Diskon:</strong></td>
                <td>Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td><strong>Rp {{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
    </div>

    <!-- <script>
        window.onload = function() {
            window.print();
        };
    </script> -->

</body>
</html>
