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
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Satuan</label>
                        <select name="satuan" class="form-control" required>
                            <option value="SAK" {{ $produk->satuan == 'SAK' ? 'selected' : '' }}>SAK</option>
                            <option value="DUS" {{ $produk->satuan == 'DUS' ? 'selected' : '' }}>DUS</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Isi per Satuan</label>
                        <input type="number" name="isi_per_satuan" class="form-control" value="{{ $produk->isi_per_satuan }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Isi</label>
                        <select name="jenis_isi" class="form-control" required>
                            <option value="PCS" {{ $produk->jenis_isi == 'PCS' ? 'selected' : '' }}>PCS</option>
                            <option value="KG" {{ $produk->jenis_isi == 'KG' ? 'selected' : '' }}>KG</option>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga Beli per Satuan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_beli_per_satuan" class="form-control" value="{{ $produk->harga_beli_per_satuan }}" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga Jual per Satuan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_jual_per_satuan" class="form-control" value="{{ $produk->harga_jual_per_satuan }}" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga Jual per Isi</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_jual_per_isi" class="form-control" value="{{ $produk->harga_jual_per_isi }}" required>
                        </div>
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