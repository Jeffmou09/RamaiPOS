@extends('layouts.sidebar')

@section('content')
<div class="container-fluid pt-4">
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 rounded-lg" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">Transaksi Berhasil!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Transaksi telah berhasil disimpan</h4>
                    <p class="text-muted">Terima kasih atas pembelian Anda</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="#" class="btn btn-primary" id="cetakNota">
                        <i class="bi bi-printer"></i> Cetak Nota
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-lg border-0" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Error Alert for JS validation -->
    <div class="alert alert-danger alert-dismissible fade show rounded-lg border-0 d-none" id="validation-error" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
        <span id="error-message"></span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <form id="transaksiForm" action="{{ route('transaksi.store') }}" method="POST">
        @csrf
        <div class="row mb-4">
            <!-- Card Tagihan -->
            <div class="col-md-8">
                <div class="card border-0 rounded-lg py-5 h-100 d-flex justify-content-center align-items-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <h1 class="font-weight-bold m-0">Tagihan: Rp <span id="total_tagihan">0</span></h1>
                </div>
            </div>

            <!-- Card Informasi Nota -->
            <div class="col-md-4">
                <div class="card border-0 rounded-lg py-3 px-4 h-100" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <div class="d-flex flex-column justify-content-center h-100">
                        <div class="mb-2">
                            <label class="form-label small mb-1">Tanggal:</label>
                            <input type="date" name="tanggal_transaksi" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" readonly>
                        </div>
                        <div>
                            <label class="form-label small mb-1">Customer:</label>
                            <input type="hidden" name="customer_id" id="customer_id">
                            <input type="text" id="nama_customer" class="form-control form-control-sm" list="customerList" placeholder="Cari customer...">
                            <datalist id="customerList">
                                @foreach($customerList as $customer)
                                    <option data-id="{{ $customer->id }}" value="{{ $customer->nama_customer }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Input Produk -->
        <div class="card border-0 rounded-lg p-4 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label small">Nama Produk</label>
                    <input type="hidden" name="produk_id" id="produk_id">
                    <input type="text" id="nama_produk" class="form-control" list="produkList" placeholder="Cari produk...">
                    <datalist id="produkList">
                        @foreach($produkList as $produk)
                            <option data-id="{{ $produk->id }}" value="{{ $produk->nama_produk }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label small">Harga</label>
                    <input type="text" class="form-control" id="harga_satuan" readonly>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label small">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah" value="1" min="1" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label small">Harga Akhir</label>
                    <input type="text" class="form-control" id="harga_akhir" readonly>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-primary" id="simpan_item">
                    <i class="fas fa-plus me-1"></i> Simpan Item
                </button>
            </div>
        </div>

        <!-- Tabel Barang Dibeli -->
        <div class="card border-0 rounded-lg p-4 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <h5 class="mb-3">Barang Dibeli</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Harga Jual</th>
                            <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel_barang">
                        <!-- Data akan diisi dengan JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Hidden input to store items data -->
        <input type="hidden" name="items_json" id="items_json" value="">
        <input type="hidden" name="jumlah_produk_terjual" id="jumlah_produk_terjual" value="0">
        <input type="hidden" name="total_transaksi" id="total_transaksi" value="0">

        <!-- Card Subtotal, Diskon, Total Akhir dan Deskripsi -->
        <div class="d-flex justify-content-end">
            <div class="card border-0 rounded-lg p-4 ms-auto" style="max-width: 400px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <h5 class="mb-3">Ringkasan Pembayaran</h5>
                
                <!-- Subtotal -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="me-2 mb-0">Subtotal:</label>
                    <h4 class="mb-0">Rp <span id="subtotal">0</span></h4>
                </div>

                <!-- Diskon -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="me-2 mb-0">Diskon:</label>
                    <input type="number" name="diskon" class="form-control w-50" id="diskon" placeholder="Masukkan diskon" value="0" min="0" oninput="validity.valid||(value='');">
                </div>

                <!-- Total Akhir -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <label class="me-2 mb-0">Total Akhir:</label>
                    <h4 class="mb-0">Rp <span id="total_akhir">0</span></h4>
                </div>
                
                <!-- Deskripsi -->
                <div class="mb-3">
                    <label class="form-label">Deskripsi Transaksi:</label>
                    <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3" placeholder="Tambahkan catatan atau deskripsi transaksi..."></textarea>
                </div>

                <!-- Tombol Simpan -->
                <button type="submit" class="btn btn-success w-100" id="simpan_transaksi">
                    <i class="fas fa-save me-1"></i> Simpan Transaksi
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const produkInput = document.getElementById("nama_produk");
    const hargaSatuanInput = document.getElementById("harga_satuan");
    const jumlahInput = document.getElementById("jumlah");
    const hargaAkhirInput = document.getElementById("harga_akhir");
    const produkIdInput = document.getElementById("produk_id");
    const simpanItemBtn = document.getElementById("simpan_item");
    const tabelBarang = document.getElementById("tabel_barang");
    const totalTagihan = document.getElementById("total_tagihan");
    const customerInput = document.getElementById("nama_customer");
    const customerIdInput = document.getElementById("customer_id");
    const itemsJsonInput = document.getElementById("items_json");
    const jumlahProdukTerjualInput = document.getElementById("jumlah_produk_terjual");
    const totalTransaksiInput = document.getElementById("total_transaksi");
    const validationError = document.getElementById("validation-error");
    const errorMessage = document.getElementById("error-message");

    const subtotalElement = document.getElementById("subtotal");
    const diskonInput = document.getElementById("diskon");
    const totalAkhirElement = document.getElementById("total_akhir");
    const transaksiForm = document.getElementById("transaksiForm");

    // Helper function to show errors
    function showError(message) {
        errorMessage.textContent = message;
        validationError.classList.remove('d-none');
        // Auto hide after 5 seconds
        setTimeout(() => {
            validationError.classList.add('d-none');
        }, 5000);
    }

    let produkData = {
        @foreach($produkList as $produk)
            "{{ $produk->nama_produk }}": {
                id: "{{ $produk->id }}",
                harga: "{{ $produk->harga_jual }}"
            },
        @endforeach
    };

    // Store customer names for validation
    let customerNames = [
        @foreach($customerList as $customer)
            "{{ $customer->nama_customer }}",
        @endforeach
    ];

    // Array untuk menyimpan semua item yang ditambahkan
    let items = [];

    // Menangani input customer
    customerInput.addEventListener("input", function() {
        const selectedOption = [...document.querySelectorAll("#customerList option")]
            .find(option => option.value === this.value);
        
        if (selectedOption) {
            customerIdInput.value = selectedOption.getAttribute("data-id");
        } else {
            customerIdInput.value = "";
        }
    });

    // Extra validation when customer input loses focus
    customerInput.addEventListener("blur", function() {
        if (this.value && !customerNames.includes(this.value)) {
            showError("Customer dengan nama '" + this.value + "' tidak ditemukan dalam database.");
            this.value = "";
            customerIdInput.value = "";
        }
    });

    produkInput.addEventListener("input", function () {
        const selectedProduk = produkData[this.value];
        if (selectedProduk) {
            produkIdInput.value = selectedProduk.id;
            hargaSatuanInput.value = selectedProduk.harga;
            jumlahInput.value = 1;
            updateHargaAkhir();
        } else {
            produkIdInput.value = "";
            hargaSatuanInput.value = "";
            hargaAkhirInput.value = "";
        }
    });

    // Extra validation when product input loses focus
    produkInput.addEventListener("blur", function() {
        if (this.value && !produkData[this.value]) {
            showError("Produk dengan nama '" + this.value + "' tidak ditemukan dalam database.");
            this.value = "";
            hargaSatuanInput.value = "";
            hargaAkhirInput.value = "";
            produkIdInput.value = "";
        }
    });

    jumlahInput.addEventListener("input", updateHargaAkhir);

    function updateHargaAkhir() {
        const selectedProduk = produkData[produkInput.value];
        if (!selectedProduk) return;

        let hargaAkhir = 0;
        let jumlah = jumlahInput.value || 1;
        hargaAkhir = selectedProduk.harga * jumlah;

        hargaAkhirInput.value = hargaAkhir.toFixed(2);
    }

    simpanItemBtn.addEventListener("click", function () {
        const namaProduk = produkInput.value;
        const produkId = produkIdInput.value;
        const hargaSatuan = hargaSatuanInput.value;
        const jumlah = jumlahInput.value;
        const hargaJual = hargaAkhirInput.value;

        if (!namaProduk || !hargaSatuan || !jumlah || !hargaJual || !produkId) {
            showError("Semua field produk harus diisi!");
            return;
        }

        // Validate product exists in database
        if (!produkData[namaProduk]) {
            showError("Produk dengan nama '" + namaProduk + "' tidak ditemukan dalam database.");
            return;
        }

        // Simpan item ke array items
        items.push({
            produk_id: produkId,
            nama_produk: namaProduk,
            harga_satuan: parseFloat(hargaSatuan),
            jumlah: parseInt(jumlah),
            sub_total: parseFloat(hargaJual)
        });

        // Update hidden input dengan data items
        itemsJsonInput.value = JSON.stringify(items);

        // Buat baris baru
        let row = tabelBarang.insertRow();
        row.setAttribute("data-harga", hargaJual); // Simpan harga sebagai atribut data-harga
        row.setAttribute("data-index", items.length - 1); // Simpan index untuk hapus

        row.innerHTML = `
            <td>${namaProduk}</td>
            <td>Rp ${parseFloat(hargaSatuan).toLocaleString()}</td>
            <td>${jumlah}</td>
            <td>Rp ${parseFloat(hargaJual).toLocaleString()}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger delete-item" data-index="${items.length - 1}">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        // Reset form
        produkInput.value = "";
        hargaSatuanInput.value = "";
        jumlahInput.value = "1";
        hargaAkhirInput.value = "";

        // Hitung ulang total tagihan dan subtotal
        updateTotalTagihan();
    });

    function updateTotalTagihan() {
        let total = 0;
        let totalItems = 0;

        items.forEach(item => {
            total += item.sub_total;
            totalItems += item.jumlah;
        });

        subtotalElement.textContent = total.toLocaleString();
        totalTagihan.textContent = total.toLocaleString();
        jumlahProdukTerjualInput.value = totalItems;

        // Perbarui total akhir dengan diskon
        updateTotalAkhir();
    }

    function updateTotalAkhir() {
        let subtotal = parseFloat(subtotalElement.textContent.replace(/,/g, "")) || 0;
        let diskon = parseFloat(diskonInput.value) || 0;
        let totalAkhir = subtotal - diskon;

        if (totalAkhir < 0) totalAkhir = 0; // Pastikan total akhir tidak negatif

        totalAkhirElement.textContent = totalAkhir.toLocaleString();
        totalTagihan.textContent = totalAkhir.toLocaleString(); // Perbarui total tagihan juga
        totalTransaksiInput.value = Math.round(totalAkhir); // Update hidden field untuk total transaksi (dibulatkan)
    }

    diskonInput.addEventListener("input", updateTotalAkhir);

    // Event delegation untuk tombol hapus
    document.addEventListener("click", function (event) {
        if (event.target.closest(".delete-item")) {
            const btn = event.target.closest(".delete-item");
            const index = parseInt(btn.getAttribute("data-index"));
            
            // Hapus item dari array
            items.splice(index, 1);
            
            // Update hidden input
            itemsJsonInput.value = JSON.stringify(items);
            
            // Hapus baris dari tabel
            btn.closest("tr").remove();
            
            // Update semua indeks pada baris yang tersisa
            const rows = tabelBarang.querySelectorAll("tr");
            rows.forEach((row, i) => {
                row.setAttribute("data-index", i);
                row.querySelector(".delete-item").setAttribute("data-index", i);
            });
            
            // Update total
            updateTotalTagihan();
        }
    });

    // Form submission validation
    transaksiForm.addEventListener("submit", function(e) {
        e.preventDefault();
        
        if (items.length === 0) {
            showError("Silakan tambahkan produk terlebih dahulu!");
            return;
        }
        
        // Validate customer if entered
        if (customerInput.value && !customerNames.includes(customerInput.value)) {
            showError("Customer dengan nama '" + customerInput.value + "' tidak ditemukan dalam database.");
            return;
        }
        
        // Jika customer kosong, buat null
        if (!customerIdInput.value) {
            customerIdInput.value = "";
        }
        
        this.submit();
    });
});

// Check for success session and show modal
@if(session('success'))
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
    
    // Store transaction ID in localStorage for printing
    @if(session()->has('transaction_id'))
        localStorage.setItem('lastTransactionId', '{{ session('transaction_id') }}');
    @endif
@endif

// Handle print button click
document.getElementById('cetakNota').addEventListener('click', function(e) {
    e.preventDefault();
    @if(session()->has('last_transaction_id'))
        // Use the ID directly from session
        window.open('/transaksi/cetak/{{ session("last_transaction_id") }}', '_blank');
    @else
        alert('Data transaksi tidak ditemukan!');
    @endif
});
</script>
@endsection