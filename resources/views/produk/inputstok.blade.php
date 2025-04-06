@extends('layouts.sidebar')

@section('content')
<div class="container-fluid pt-4">
    <div class="card border-0 rounded-lg mb-4" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title text-dark m-0">Daftar Stok</h4>
            </div>

            <!-- Form Input Stok -->
            <form action="{{ route('inputstok.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
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
                        <select name="aksi_stok" class="form-select">
                            <option value="tambah">Tambah</option>
                            <option value="kurang">Kurang</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Satuan</label>
                        <select name="satuan" class="form-select">
                            <option value="DUS">Dus</option>
                            <option value="PCS">Pcs</option>
                            <option value="SAK">Sak</option>
                            <option value="KG">Kg</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Harga Beli</label>
                        <input type="number" name="harga_beli" class="form-control" required>
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
                </div>
            @endif

            <!-- Daftar Stok -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-light">
                            <th>Nama Produk</th>
                            <th>Stok Satuan</th>
                            <th>Satuan (Sak / Dus)</th>
                            <th>Isi</th>
                            <th>Jenis Isi (Kg / Pcs)</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stokProduk as $stok)
                            <tr>
                                <td>{{ $stok->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                                <td>{{ $stok->stok_satuan_utama }}</td>
                                <td>{{ $stok->produk->satuan }}</td>
                                <td>{{ $stok->stok_satuan_isi }}</td>
                                <td>{{ $stok->produk->jenis_isi }}</td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('produk.stokdetail', $stok->id) }}" class="btn btn-sm btn-outline-info">
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
</script>
@endsection