@extends('layouts.sidebar')

@section('content')
<div class="container-fluid pt-4">
    <div class="card border-0 rounded-lg mb-4" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title text-dark m-0">Daftar Stok</h4>
            </div>

            <!-- Form Input Stok -->
            <form action="{{ route('inputstok.store') }}" method="POST" class="mb-4 w-100">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nama Produk</label>
                    <input type="hidden" name="produk_id" id="produk_id">
                    <input type="text" id="nama_produk" class="form-control" list="produkList" placeholder="Cari produk...">
                    <datalist id="produkList">
                        @foreach($produkList as $produk)
                            <option data-id="{{ $produk->id }}" value="{{ $produk->nama_produk }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Aksi Stok</label>
                    <select name="aksi_stok" id="aksi_stok" class="form-select">
                        <option value="tambah">Tambah</option>
                        <option value="kurang">Kurang</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" name="harga_beli" id="harga_beli" class="form-control" required>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Daftar Stok -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-light">
                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th>Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produkList as $produk)
                            <tr>
                                <td>{{ $produk->id }}</td>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->stok }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('produk.stokdetail', $produk->id) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
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

<script>
    document.getElementById('nama_produk').addEventListener('input', function() {
        let input = this.value;
        let options = document.getElementById('produkList').options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].value === input) {
                document.getElementById('produk_id').value = options[i].getAttribute('data-id');
                break;
            }
        }
    });

     // Handle aksi_stok change to disable harga_beli when "Kurang" is selected
     document.getElementById('aksi_stok').addEventListener('change', function() {
        const hargaBeliInput = document.getElementById('harga_beli');
        if (this.value === 'kurang') {
            hargaBeliInput.disabled = true;
            hargaBeliInput.value = 0; // Reset value when disabled
            hargaBeliInput.removeAttribute('required'); // Remove required attribute
        } else {
            hargaBeliInput.disabled = false;
            hargaBeliInput.setAttribute('required', 'required'); // Add required attribute back
        }
    });

    // Initialize form state on page load
    window.addEventListener('DOMContentLoaded', function() {
        const aksiStok = document.getElementById('aksi_stok');
        if (aksiStok.value === 'kurang') {
            const hargaBeliInput = document.getElementById('harga_beli');
            hargaBeliInput.disabled = true;
            hargaBeliInput.value = 0;
            hargaBeliInput.removeAttribute('required');
        }
    });
</script>
@endsection