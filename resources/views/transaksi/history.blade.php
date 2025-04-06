@extends('layouts.sidebar')
@section('content')
<div class="container-fluid pt-4">
    <div class="card border-0 rounded-lg mb-4" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title text-dark m-0">History Transaksi</h4>
                <!-- Filter dropdown -->
                <form action="{{ route('transaksi.history') }}" method="GET">
                    <select name="periode" id="periode" class="form-select" onchange="this.form.submit()">
                        <option value="today" {{ request('periode') == 'today' || !request('periode') ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ request('periode') == 'week' ? 'selected' : '' }}>1 Minggu Terakhir</option>
                        <option value="month" {{ request('periode') == 'month' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                        <option value="all" {{ request('periode') == 'all' ? 'selected' : '' }}>Semua Transaksi</option>
                    </select>
                </form>
            </div>
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
            @endif
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-light">
                            <th>Tanggal Transaksi</th>
                            <th>Customer</th>
                            <th>Jumlah Produk</th>
                            <th>Diskon</th>
                            <th>Total Transaksi</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $trx)
                        <tr>
                            <td>{{ date('d-m-Y', strtotime($trx->tanggal_transaksi)) }}</td>
                            <td>{{ $trx->customer ? $trx->customer->nama_customer : 'Umum' }}</td>
                            <td>{{ $trx->jumlah_produk_terjual }}</td>
                            <td>Rp {{ number_format($trx->diskon, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($trx->total_transaksi, 2, ',', '.') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Tombol lihat detail -->
                                    <a href="{{ route('transaksi.detail', $trx->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <!-- Tombol cetak nota -->
                                    <a href="{{ route('transaksi.cetak', $trx->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                    <!-- Tombol hapus -->
                                    <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus transaksi ini?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection