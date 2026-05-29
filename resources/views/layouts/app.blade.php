<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('Logo.png') }}" type="image/png">
  <title>Admin {{ $globalUser->name }} | Aplikasi Kasir</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

  <button class="hamburger-toggle" id="hamburgerToggle">
    <i class="fa-solid fa-bars"></i>
  </button>

  <aside class="sidebar" id="sidebar">
    <div class="brand">
      @if ($globalUser && $globalUser->logo)
      <img src="{{ asset('storage/logos/' . $globalUser->logo) }}" alt="Logo"
      style="height: 60px; aspect-ratio: 1 / 1; object-fit: cover; border-radius: 50%;">
      @else
      <div style="
        height: 60px;
        width: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        ">
        <i class="fa-solid fa-gear" style="font-size: 40px;"></i>
      </div>
      @endif
      <span class="text-[16px] uppercase">{{ $globalUser->name ?? 'Kasir Kafe' }}</span>
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
      <li><a href="{{ route('settings.edit') }}" class="{{ request()->routeIs('settings.*') ? 'active' : '' }}"><i
        class="fa-solid fa-gear"></i>
        Pengaturan</a></li>
      <form action="{{ route('logout') }}" method="POST" style="margin-top: 2rem;">
        @csrf
        <button type="submit"
        class="logout-button"
          ><i class="fa-solid fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </ul>
  </aside>

  <div class="sidebar-overlay" id="sidebarOverlay"></div>

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
    // Hamburger Menu Toggle
    const hamburgerToggle = document.getElementById('hamburgerToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
      sidebar.classList.toggle('closed');
      sidebarOverlay.classList.toggle('active');
    }

    hamburgerToggle.addEventListener('click', toggleSidebar);
    sidebarOverlay.addEventListener('click', toggleSidebar);

    // Close sidebar when link is clicked
    const sidebarLinks = document.querySelectorAll('.sidebar a, .sidebar button');
    sidebarLinks.forEach(link => {
    link.addEventListener('click', () => {
    if (window.innerWidth <= 768) {
    sidebar.classList.add('closed');
    sidebarOverlay.classList.remove('active');
    }
    });
    });

    // Close sidebar on page load (mobile)
    if (window.innerWidth <= 768) {
      sidebar.classList.add('closed');
    }

    // Handle resize events
    window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
    sidebar.classList.remove('closed');
    sidebarOverlay.classList.remove('active');
    } else {
    sidebar.classList.add('closed');
    sidebarOverlay.classList.remove('active');
    }
    });

    // Auto hide alerts after 5 seconds
    setTimeout(() => {
    const alert = document.getElementById('alert');
    if (alert) {
    alert.style.animation = 'slideDown 0.3s ease reverse';
    setTimeout(() => {
    alert.style.display = 'none';
    }, 300);
    }
    }, 5000);
  </script>
</body>

</html>