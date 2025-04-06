@extends('layouts.sidebar')
@section('content')
<div class="container-fluid pt-4">
    <div class="card border-0 rounded-lg" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="card-body p-4">
            <h4 class="card-title text-dark mb-4">Tambah Produk</h4>
            
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form action="{{ route('daftarproduk.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama_produk" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Satuan</label>
                        <select name="satuan" class="form-control" required>
                            <option value="">-- Pilih Satuan --</option>
                            <option value="SAK">SAK</option>
                            <option value="DUS">DUS</option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Isi per Satuan</label>
                        <input type="number" name="isi_per_satuan" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Isi</label>
                        <select name="jenis_isi" class="form-control" required>
                            <option value="">-- Pilih Jenis Isi --</option>
                            <option value="PCS">PCS</option>
                            <option value="KG">KG</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Harga Beli per Satuan</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_beli_per_satuan" class="form-control" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Jual per Satuan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_jual_per_satuan" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Jual per Isi</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_jual_per_isi" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-light mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Stok Awal</h5>
                        <div class="mb-3">
                            <label class="form-label">Stok Satuan Utama</label>
                            <input type="number" name="stok_satuan_utama" class="form-control" required>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Tambah Produk</button>
                    <a href="{{ route('daftarproduk') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection