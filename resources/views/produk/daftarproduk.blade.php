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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <!-- Form Pencarian -->
            <div class="mb-4">
                <form action="{{ route('daftarproduk') }}" method="GET" class="d-flex gap-2">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="{{ $search ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-light">
                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produk as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('daftarproduk.editproduk', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('daftarproduk.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus?');">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">Tidak ada data produk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-4">
                {{ $produk->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection