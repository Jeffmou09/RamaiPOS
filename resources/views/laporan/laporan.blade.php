@extends('layouts.sidebar')
@section('content')
<div class="container-fluid pt-4">
    <div class="card border-0 rounded-lg mb-4" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title text-dark m-0">Laporan Penjualan</h4>
            </div>
            
            <div class="row">
                <!-- List Transaksi Card -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 rounded-lg h-100" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary rounded-circle p-2 text-white me-3">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <h5 class="font-weight-bold m-0">Laporan Transaksi</h5>
                            </div>
                            <p class="text-muted small mb-3">
                                Menampilkan seluruh transaksi berdasarkan periode yang dipilih
                            </p>
                            
                            <!-- Filter Transaksi -->
                            <form id="formTransaksi" method="GET" action="{{ route('laporan.transaksi') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tanggal_awal_transaksi" class="form-label small">Tanggal Awal</label>
                                            <input type="date" class="form-control" id="tanggal_awal_transaksi" name="tanggal_awal" value="{{ date('Y-m-01') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tanggal_akhir_transaksi" class="form-label small">Tanggal Akhir</label>
                                            <input type="date" class="form-control" id="tanggal_akhir_transaksi" name="tanggal_akhir" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="btnLihatTransaksi" class="btn btn-primary w-100 mt-2">
                                    <i class="fas fa-file-export me-1"></i> Lihat Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Produk Terjual Card -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 rounded-lg h-100" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success rounded-circle p-2 text-white me-3">
                                    <i class="fas fa-box"></i>
                                </div>
                                <h5 class="font-weight-bold m-0">Laporan Produk Terlaris</h5>
                            </div>
                            <p class="text-muted small mb-3">
                                Menampilkan data produk yang terjual beserta jumlah dan total penjualannya
                            </p>
                            
                            <!-- Filter Produk -->
                            <form id="formProduk" method="GET" action="{{ route('laporan.produk') }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tanggal_awal_produk" class="form-label small">Tanggal Awal</label>
                                            <input type="date" class="form-control" id="tanggal_awal_produk" name="tanggal_awal" value="{{ date('Y-m-01') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tanggal_akhir_produk" class="form-label small">Tanggal Akhir</label>
                                            <input type="date" class="form-control" id="tanggal_akhir_produk" name="tanggal_akhir" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="btnLihatProduk" class="btn btn-success w-100 mt-2">
                                    <i class="fas fa-file-export me-1"></i> Lihat Laporan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Container for displaying results -->
            <div class="row mt-4" id="resultContainer" style="display: none;">
                <div class="col-12">
                    <div class="card border-0 rounded-lg" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bold mb-3" id="resultTitle">Laporan</h5>
                            <div class="table-responsive">
                                <table class="table table-hover" id="resultTable">
                                    <!-- Table content will be inserted here -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Button references
    const btnLihatTransaksi = document.getElementById('btnLihatTransaksi');
    const btnLihatProduk = document.getElementById('btnLihatProduk');
    
    // Form references
    const formTransaksi = document.getElementById('formTransaksi');
    const formProduk = document.getElementById('formProduk');
    
    // Date input references
    const tanggalAwalTransaksi = document.getElementById('tanggal_awal_transaksi');
    const tanggalAkhirTransaksi = document.getElementById('tanggal_akhir_transaksi');
    const tanggalAwalProduk = document.getElementById('tanggal_awal_produk');
    const tanggalAkhirProduk = document.getElementById('tanggal_akhir_produk');
    
    // Update action URL untuk form
    formTransaksi.setAttribute('action', '{{ route("laporan.transaksi.pdf") }}');
    formProduk.setAttribute('action', '{{ route("laporan.produk.pdf") }}');
    
    // Function to validate dates
    function validateDates(startDateInput, endDateInput) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (endDate < startDate) {
            alert('Tanggal akhir harus berada setelah tanggal awal.');
            return false;
        }
        return true;
    }
    
    // Event listener for Transaksi button with date validation
    btnLihatTransaksi.addEventListener('click', function(e) {
        if (validateDates(tanggalAwalTransaksi, tanggalAkhirTransaksi)) {
            formTransaksi.submit(); // Ini akan mendownload PDF langsung
        }
    });
    
    // Event listener for Produk button with date validation
    btnLihatProduk.addEventListener('click', function(e) {
        if (validateDates(tanggalAwalProduk, tanggalAkhirProduk)) {
            formProduk.submit(); // Ini akan mendownload PDF produk
        }
    });
});
</script>
@endsection