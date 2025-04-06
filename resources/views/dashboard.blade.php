@extends('layouts.sidebar')
@section('content')
<div class="container-fluid pt-4">
    <div class="row mb-4">
        <!-- Total Produk Card -->
        <div class="col-md-4">
            <div class="card border-0 rounded-lg mb-3" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 text-dark">{{ number_format($totalProduk) }}</h1>
                    <p class="lead text-muted">Total Produk</p>
                </div>
            </div>
        </div>
        <!-- Total Customer Card -->
        <div class="col-md-4">
            <div class="card border-0 rounded-lg mb-3" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 text-dark">{{ number_format($totalCustomer) }}</h1>
                    <p class="lead text-muted">Total Customer</p>
                </div>
            </div>
        </div>
        <!-- Today's Total Income Card -->
        <div class="col-md-4">
            <div class="card border-0 rounded-lg mb-3" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="card-body text-center py-4">
                    <h1 class="display-4 text-primary">Rp {{ number_format($todayIncome) }}</h1>
                    <p class="lead text-muted">Total Income Hari Ini</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 rounded-lg h-100" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title text-dark mb-0">Income Per Hari</h5>
                        <div class="btn-group">
                            <select id="filterPeriode" class="form-select form-select-sm" onchange="changePeriod(this.value)">
                                <option value="week">Minggu Ini</option>
                                <option value="month" selected>Bulan Ini</option>
                                <option value="year">Tahun Ini</option>
                            </select>
                        </div>
                    </div>
                    <div style="position: relative; height: 300px;">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 rounded-lg" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title text-dark mb-0">Top 10 Produk Terlaris</h5>
                        <div class="btn-group">
                            <select id="filterProducts" class="form-select form-select-sm" onchange="changeProductFilter(this.value)">
                                <option value="week">Minggu Ini</option>
                                <option value="month" selected>Bulan Ini</option>
                                <option value="year">Tahun Ini</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="topProductsTable">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" width="50"></th>
                                    <th scope="col">Nama Produk</th>
                                    <th scope="col" class="text-end">DUS/SAK</th>
                                    <th scope="col" class="text-end">PCS/KG</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $index => $product)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->nama_produk }}</td>
                                    <td class="text-end">
                                        {{ $product->jumlah_satuan_besar > 0 ? number_format($product->jumlah_satuan_besar) . ' ' . ($product->satuan == 'DUS' || $product->satuan == 'SAK' ? $product->satuan : 'DUS/SAK') : '-' }}
                                    </td>
                                    <td class="text-end">
                                        {{ $product->jumlah_satuan_kecil > 0 ? number_format($product->jumlah_satuan_kecil) . ' ' . ($product->jenis_isi == 'PCS' || $product->jenis_isi == 'KG' ? $product->jenis_isi : 'PCS/KG') : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                                @if(count($topProducts) == 0)
                                <tr>
                                    <td colspan="6" class="text-center py-3">Tidak ada data produk</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 rounded-lg h-100" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <div class="card-body py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title text-dark mb-0">Top 5 Customer</h5>
                        <div class="btn-group">
                            <select id="filterCustomers" class="form-select form-select-sm" onchange="changeCustomerFilter(this.value)">
                                <option value="week">Minggu Ini</option>
                                <option value="month" selected>Bulan Ini</option>
                                <option value="year">Tahun Ini</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="topCustomersTable">
                            <thead class="bg-light">
                                <tr>
                                    <th scope="col" width="40"></th>
                                    <th scope="col">Customer</th>
                                    <th scope="col" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topCustomers as $index => $customer)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ $customer->nama_customer }}
                                        <div class="small text-muted">{{ $customer->jumlah_transaksi }} transaksi</div>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($customer->total_pembelian) }}</td>
                                </tr>
                                @endforeach
                                @if(count($topCustomers) == 0)
                                <tr>
                                    <td colspan="3" class="text-center py-3">Tidak ada data customer</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let incomeChart;
let chartTitle = 'Income Per Hari Dalam Bulan Ini';

window.onload = function() {
    const dates = {!! json_encode($dates) !!};
    const incomes = {!! json_encode($incomes) !!};
    updateChart(dates, incomes, 'month');
};

function changePeriod(period) {
    // Update chart title based on the selected period
    switch(period) {
        case 'week':
            chartTitle = 'Income Per Hari Dalam Minggu Ini';
            break;
        case 'month':
            chartTitle = 'Income Per Hari Dalam Bulan Ini';
            break;
        case 'year':
            chartTitle = 'Income Per Bulan Dalam Tahun Ini';
            break;
    }
    
    fetch(`/dashboard/get-income-data?period=${period}`)
        .then(response => response.json())
        .then(data => {
            updateChart(data.dates, data.incomes, period);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            alert('Gagal mengambil data. Silakan coba lagi nanti.');
        });
}

function changeProductFilter(period) {
    fetch(`/dashboard/get-filtered-data?period=${period}&type=products`)
        .then(response => response.json())
        .then(data => {
            updateTable('#topProductsTable', data, 'products');
        })
        .catch(error => {
            console.error('Error fetching product data:', error);
            alert('Gagal mengambil data produk. Silakan coba lagi nanti.');
        });
}

function changeCustomerFilter(period) {
    fetch(`/dashboard/get-filtered-data?period=${period}&type=customers`)
        .then(response => response.json())
        .then(data => {
            updateTable('#topCustomersTable', data, 'customers');
        })
        .catch(error => {
            console.error('Error fetching customer data:', error);
            alert('Gagal mengambil data customer. Silakan coba lagi nanti.');
        });
}

function updateTable(tableSelector, data, type) {
    const tableBody = document.querySelector(`${tableSelector} tbody`);
    tableBody.innerHTML = ''; // Clear existing rows

    if (data.length === 0) {
        const noDataRow = `
            <tr>
                <td colspan="${type === 'products' ? 4 : 3}" class="text-center py-3">
                    ${type === 'products' ? 'Tidak ada data produk' : 'Tidak ada data customer'}
                </td>
            </tr>
        `;
        tableBody.innerHTML = noDataRow;
        return;
    }

    data.forEach((item, index) => {
        let row = '';
        if (type === 'products') {
            row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_produk}</td>
                    <td class="text-end">
                        ${item.jumlah_satuan_besar > 0 
                            ? `${item.jumlah_satuan_besar.toLocaleString()} ${item.satuan === 'DUS' || item.satuan === 'SAK' ? item.satuan : 'DUS/SAK'}`
                            : '-'}
                    </td>
                    <td class="text-end">
                        ${item.jumlah_satuan_kecil > 0 
                            ? `${item.jumlah_satuan_kecil.toLocaleString()} ${item.jenis_isi === 'PCS' || item.jenis_isi === 'KG' ? item.jenis_isi : 'PCS/KG'}`
                            : '-'}
                    </td>
                </tr>
            `;
        } else {
            row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        ${item.nama_customer}
                        <div class="small text-muted">${item.jumlah_transaksi} transaksi</div>
                    </td>
                    <td class="text-end">Rp ${item.total_pembelian.toLocaleString()}</td>
                </tr>
            `;
        }
        tableBody.innerHTML += row;
    });
}

function updateChart(dates, incomes, period) {
    const ctx = document.getElementById('incomeChart')?.getContext('2d');
    if (!ctx) {
        console.error("Canvas 'incomeChart' tidak ditemukan.");
        return;
    }
    
    // If chart exists, destroy it before creating a new one
    if (incomeChart) {
        incomeChart.destroy();
    }
    
    incomeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Income (dalam Juta)',
                data: incomes,
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                tension: 0.4,
                borderWidth: 2,
                fill: true,
                pointBackgroundColor: '#4361ee'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#6c757d',
                        callback: function(value) {
                            return value.toFixed(2);
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    ticks: {
                        color: '#6c757d'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: chartTitle,
                    position: 'top',
                    font: {
                        size: 16
                    }
                },
                legend: {
                    labels: {
                        color: '#343a40'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toFixed(2) + ' Juta';
                        }
                    }
                }
            }
        }
    });
}
</script>
@endsection