@extends('layouts.sidebar')

@section('content')
<div class="container-fluid pt-4">
    <div class="card border-0 rounded-lg mb-4" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="card-body p-4">
            <div class="mb-4">
                <h4 class="card-title text-dark">Edit Customer</h4>
            </div>
            
            <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Nama Customer</label>
                        <input type="text" name="nama_customer" class="form-control" value="{{ $customer->nama_customer }}" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ $customer->alamat }}" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control" value="{{ $customer->no_hp }}" required>
                    </div>
                    <div class="col-md-12 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                        <a href="{{ route('customer') }}" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-times me-1"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection