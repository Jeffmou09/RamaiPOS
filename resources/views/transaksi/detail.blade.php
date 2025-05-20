@extends('layouts.sidebar')
@section('content')
<div class="container-fluid pt-4">
    <div class="card border-0 rounded-lg mb-4" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title text-dark m-0">Detail Transaksi</h4>
                <div>
                    <a href="{{ route('transaksi.history') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('transaksi.cetak', $transaksi->id) }}" class="btn btn-outline-primary" target="_blank">
                        <i class="bi bi-printer"></i> Cetak Nota
                    </a>
                </div>
            </div>

            <div class="card mb-4 border-0 bg-light">
                <div class="card-body">
                    <h5 class="card-title mb-3">Informasi Transaksi</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID Transaksi:</strong> {{ $transaksi->id }}</p>
                            <p><strong>Customer:</strong> {{ $transaksi->customer ? $transaksi->customer->nama_customer : 'Umum' }}</p>
                            <p><strong>Tanggal Transaksi:</strong> {{ date('d-m-Y', strtotime($transaksi->tanggal_transaksi)) }}</p>
                            <p><strong>Deskripsi:</strong> {{ $transaksi->deskripsi ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Diskon:</strong> Rp {{ number_format($transaksi->diskon, 2, ',', '.') }}</p>
                            <p><strong>Jumlah Produk Terjual:</strong> {{ $transaksi->jumlah_produk_terjual }}</p>
                            <p><strong>Total Transaksi:</strong> Rp {{ number_format($transaksi->total_transaksi, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <h5 class="mb-3">Daftar Produk yang Dibeli</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-light">
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->detailTransaksi as $detail)
                        <tr>
                            <td>{{ $detail->produk->nama_produk }}</td>
                            <td>{{ $detail->jumlah_barang }}</td>
                            <td>Rp {{ number_format($detail->sub_total / $detail->jumlah_barang, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->sub_total, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="4" class="text-end"><strong>Sub Total</strong></td>
                            <td>Rp {{ number_format($transaksi->detailTransaksi->sum('sub_total'), 2, ',', '.') }}</td>
                        </tr>
                        @if($transaksi->diskon > 0)
                        <tr>
                            <td colspan="4" class="text-end"><strong>Diskon</strong></td>
                            <td>Rp {{ number_format($transaksi->diskon, 2, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total Akhir</strong></td>
                            <td>Rp {{ number_format($transaksi->total_transaksi, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection