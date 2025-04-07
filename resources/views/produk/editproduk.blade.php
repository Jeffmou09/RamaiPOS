@extends('layouts.sidebar')
@section('content')
<div class="container-fluid pt-4">
    <div class="card border-0 rounded-lg" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="card-body p-4">
            <h4 class="card-title text-dark mb-4">Edit Produk</h4>
            
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form action="{{ route('daftarproduk.update', $produk->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">ID Produk</label>
                    <input type="text" class="form-control" value="{{ $produk->id }}" disabled>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" class="form-control" value="{{ $produk->stok }}" disabled>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Harga Beli</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_beli" class="form-control" value="{{ $produk->harga_beli }}" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Harga Jual</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_jual" class="form-control" value="{{ $produk->harga_jual }}" required>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Update Produk</button>
                    <a href="{{ route('daftarproduk') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection