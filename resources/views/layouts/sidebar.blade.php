<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Ramai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .nav-link.active {
            background-color: #53596B;
            border-radius: 5px;
        }
        .user-greeting {
            font-size: 0.9rem;
            margin-bottom: 15px;
            padding: 8px;
            background-color: #53596B;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="text-white p-3" style="width: 250px; min-height: 100vh; background-color: rgba(55, 62, 83, 1);">
            <h4 class="text-center">Toko Ramai</h4>
            <p class="text-center"><i class="bi bi-person-circle me-1"></i> Hi, {{ Auth::user()->username }}</p>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white d-flex justify-content-between align-items-center" id="produkToggle">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-box me-2"></i> Produk
                        </div>
                        <i class="bi bi-chevron-down text-white" id="produkChevron"></i>
                    </a>
                    <div class="collapse" id="produkSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a href="{{ route('daftarproduk') }}" class="nav-link text-white {{ request()->routeIs('daftarproduk') ? 'active' : '' }}">Daftar Produk</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('inputstok') }}" class="nav-link text-white {{ request()->routeIs('inputstok') ? 'active' : '' }}">Input Stok</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link text-white d-flex justify-content-between align-items-center" id="transaksiToggle">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-credit-card me-2"></i> Transaksi
                        </div>
                        <i class="bi bi-chevron-down text-white" id="transaksiChevron"></i>
                    </a>
                    <div class="collapse" id="transaksiSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a href="{{ route('transaksi') }}" class="nav-link text-white {{ request()->routeIs('transaksi') ? 'active' : '' }}">Buat Transaksi</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('transaksi.history') }}" class="nav-link text-white {{ request()->routeIs('transaksi.history') ? 'active' : '' }}">History Transaksi</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customer') }}" class="nav-link text-white {{ request()->routeIs('customer') ? 'active' : '' }}">
                        <i class="bi bi-person me-2"></i> Customer
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan') }}" class="nav-link text-white {{ request()->routeIs('laporan') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text me-2"></i> Laporan
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link text-white w-100 text-start" style="background-color: transparent; border: none;" onclick="return confirm('Apakah Anda yakin untuk logout?');">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>

    <script>
        // Toggle function for "Produk" submenu
        var produkSubmenu = document.getElementById('produkSubmenu');
        var produkChevron = document.getElementById('produkChevron');
        var produkToggle = document.getElementById('produkToggle');
        
        if (localStorage.getItem('produkOpen') === 'true') {
            produkSubmenu.classList.remove('collapse');
            produkChevron.classList.remove('bi-chevron-down');
            produkChevron.classList.add('bi-chevron-up');
        }

        produkToggle.addEventListener('click', function () {
            produkSubmenu.classList.toggle('collapse');
            produkChevron.classList.toggle('bi-chevron-down');
            produkChevron.classList.toggle('bi-chevron-up');

            localStorage.setItem('produkOpen', !produkSubmenu.classList.contains('collapse'));
        });

        // Toggle function for "Transaksi" submenu
        var transaksiSubmenu = document.getElementById('transaksiSubmenu');
        var transaksiChevron = document.getElementById('transaksiChevron');
        var transaksiToggle = document.getElementById('transaksiToggle');

        if (localStorage.getItem('transaksiOpen') === 'true') {
            transaksiSubmenu.classList.remove('collapse');
            transaksiChevron.classList.remove('bi-chevron-down');
            transaksiChevron.classList.add('bi-chevron-up');
        }

        transaksiToggle.addEventListener('click', function () {
            transaksiSubmenu.classList.toggle('collapse');
            transaksiChevron.classList.toggle('bi-chevron-down');
            transaksiChevron.classList.toggle('bi-chevron-up');

            localStorage.setItem('transaksiOpen', !transaksiSubmenu.classList.contains('collapse'));
        });
    </script>
  @yield('scripts')
</body>
</html>