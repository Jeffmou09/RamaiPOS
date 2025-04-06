@extends('layouts.sidebar')
@section('content')
<div class="container-fluid pt-4">
    <div class="card border-0 rounded-lg mb-4" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title text-dark m-0">Daftar Produk</h4>
                <a href="{{ route('produk.tambahproduk') }}" class="btn btn-primary px-3">
                    <i class="fas fa-plus me-1"></i> Tambah Produk
                </a>
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
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Isi per Satuan</th>
                            <th>Jenis Isi</th>
                            <th>Harga Beli per Satuan</th>
                            <th>Harga Beli per Isi</th>
                            <th>Harga Jual per Satuan</th>
                            <th>Harga Jual per Isi</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produks as $produk)
                        <tr>
                            <td>{{ $produk->nama_produk }}</td>
                            <td>{{ $produk->satuan }}</td>
                            <td>{{ $produk->isi_per_satuan }}</td>
                            <td>{{ $produk->jenis_isi }}</td>
                            <td>Rp {{ number_format($produk->harga_beli_per_satuan, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($produk->harga_beli_per_isi, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($produk->harga_jual_per_satuan, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format($produk->harga_jual_per_isi, 2, ',', '.') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('daftarproduk.editproduk', $produk->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('daftarproduk.destroy', $produk->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus?');">
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