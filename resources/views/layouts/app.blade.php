<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('Logo.png') }}" type="image/png">
    <title>Admin Kafe | Aplikasi Kasir</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <aside class="sidebar">
        <div class="brand">
            <img src="{{ asset('Logo.png') }}" style="height: 60px; width: 60px;" alt="Logo Admin Kafe"> Admin Kafe
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('pos.index') }}" class="{{ request()->routeIs('pos.*') ? 'active' : '' }}"><i
                        class="fa-solid fa-cash-register"></i> Kasir (POS)</a></li>
            <li><a href="{{ route('menus.index') }}" class="{{ request()->routeIs('menus.*') ? 'active' : '' }}"><i
                        class="fa-solid fa-utensils"></i> Manajemen Menu</a></li>
            <li><a href="{{ route('categories.index') }}"
                    class="{{ request()->routeIs('categories.*') ? 'active' : '' }}"><i class="fa-solid fa-tags"></i>
                    Kategori</a></li>
            <li><a href="{{ route('transactions.history') }}"
                    class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}"><i
                        class="fa-solid fa-receipt"></i> Riwayat Transaksi</a></li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="no-underline text-left text-white/80 px-4 py-3 rounded-[var(--radius)] transition-all duration-300 ease-in-out font-medium block w-full hover:translate-x-[5px] hover:text-red-600 hover:bg-[#6F4E37] transform">
                    <i class="fa-solid fa-sign-out-alt"></i> Logout
                </button>
            </form>

        </ul>
    </aside>

    <main class="main-content">
        @if (session('success'))
            <div id="alert" class="alert alert-success bg-green-600">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div id="alert" class="alert alert-error bg-red-600">
                <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')

    <script>
        setTimeout(() => {
            document.getElementById('alert').style.display = 'none';
        }, 5000);
    </script>
</body>

</html>
