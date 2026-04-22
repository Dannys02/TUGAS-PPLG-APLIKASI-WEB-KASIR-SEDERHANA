<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kafe - Aplikasi Kasir</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <aside class="sidebar">
        <div class="brand">
            <i class="fa-solid fa-mug-hot"></i> Admin Kafe
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('pos.index') }}" class="{{ request()->routeIs('pos.*') ? 'active' : '' }}"><i class="fa-solid fa-cash-register"></i> Kasir (POS)</a></li>
            <li><a href="{{ route('menus.index') }}" class="{{ request()->routeIs('menus.*') ? 'active' : '' }}"><i class="fa-solid fa-utensils"></i> Manajemen Menu</a></li>
            <li><a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"><i class="fa-solid fa-tags"></i> Kategori</a></li>
            <li><a href="{{ route('transactions.history') }}" class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}"><i class="fa-solid fa-receipt"></i> Riwayat Transaksi</a></li>
        </ul>
    </aside>

    <main class="main-content">
        @if(session('success'))
            <div class="alert">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
