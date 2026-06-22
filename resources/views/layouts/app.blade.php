<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if ($globalUser && $globalUser->logo && file_exists(storage_path('app/public/logos/' . $globalUser->logo)))
        <link rel="icon" href="{{ asset('storage/logos/' . $globalUser->logo) }}">
    @else
        <link rel="icon" href="{{ asset('Logo.png') }}">
    @endif
    <title>Dashboard | Aplikasi Kasir</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <button class="hamburger-toggle" id="hamburgerToggle">
        <i class="fa-solid fa-bars"></i>
    </button>

    <aside class="sidebar closed" id="sidebar">
        <!-- Brand / Header Sidebar -->
        <div class="brand">
            @if ($globalUser && $globalUser->logo)
                <img src="{{ asset('storage/logos/' . $globalUser->logo) }}" alt="Logo" class="brand-logo">
            @else
                <div class="brand-logo-fallback">
                    <i class="fa-solid fa-store"></i>
                </div>
            @endif
            <div class="brand-details">
                <span class="brand-text">{{ $globalUser->name ?? 'Kasir' }}</span>
                <span class="brand-badge"><i class="fa-solid fa-circle text-xs text-emerald-400 mr-1 animate-pulse"></i>
                    Online</span>
            </div>
        </div>

        <!-- Navigasi Menu -->
        <div class="nav-container">
            <!-- Kelompok Utama -->
            <div class="menu-group">
                <span class="menu-group-title">Menu Utama</span>
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pos.index') }}" class="{{ request()->routeIs('pos.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-cash-register"></i> <span>Kasir (POS)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.history') }}"
                            class="{{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-receipt"></i> <span>Riwayat Transaksi</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Kelompok Manajemen -->
            <div class="menu-group">
                <span class="menu-group-title">Manajemen Data</span>
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('menus.index') }}"
                            class="{{ request()->routeIs('menus.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-utensils"></i> <span>Daftar Menu</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}"
                            class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-tags"></i> <span>Kategori Produk</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Kelompok Lainnya -->
            <div class="menu-group">
                <span class="menu-group-title">Sistem</span>
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('settings.edit') }}"
                            class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-sliders"></i> <span>Pengaturan Toko</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Footer Sidebar -->
        <div class="sidebar-footer">
            <form id="formLogout" action="{{ route('logout') }}" method="POST">
                @csrf
                <button id="logoutButton" type="submit" class="logout-button">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="main-content">
        {{-- Modal Notifikasi Session --}}
        <div id="session-modal"
            class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300">
            <div
                class="w-full max-w-sm transform overflow-hidden rounded-2xl bg-white p-6 text-center shadow-2xl transition-all scale-95 duration-300 border border-slate-100">
                <div id="session-modal-icon-container"
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100/60">
                    <i id="session-modal-icon" class="fa-solid fa-circle-check text-xl"></i>
                </div>
                <h3 id="session-modal-title" class="text-base font-bold text-slate-800 mb-1.5">Notifikasi</h3>
                <p id="session-modal-message" class="text-xs text-slate-400 mb-6 leading-relaxed">
                    Keterangan info sistem...
                </p>
                <div class="flex justify-center w-full">
                    <button id="session-modal-btn-ok"
                        class="flex-1 px-5 py-3 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/5 rounded-xl transition-colors">
                        Oke, Mengerti
                    </button>
                </div>
            </div>
        </div>

        @yield('content')
    </main>

    @stack('scripts')

    <script>
        // Hamburger Menu Toggle
        const hamburgerToggle = document.getElementById('hamburgerToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const logoutButton = document.getElementById('logoutButton');

        logoutButton.disabled = true;
        setTimeout(() => {
            logoutButton.disabled = false;
        }, 5000);

        function toggleSidebar() {
            sidebar.classList.toggle('closed');
            sidebarOverlay.classList.toggle('active');
        }
        hamburgerToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // logout submit protection
        document.getElementById('formLogout').addEventListener('submit', function() {
            logoutButton.disabled = true;
            logoutButton.innerText = 'Memproses...';
        });

        // Close sidebar when link is clicked
        sidebar.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('closed');
                sidebarOverlay.classList.remove('active');
            }
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

        // --- ENGINE MODAL SESSION (Tema Putih-Biru) ---
        function showSessionModal({
            title,
            message,
            type = 'success'
        }) {
            const modal = document.getElementById('session-modal');
            const modalBox = modal.querySelector('div.max-w-sm');
            const modalTitle = document.getElementById('session-modal-title');
            const modalMessage = document.getElementById('session-modal-message');
            const modalIconContainer = document.getElementById('session-modal-icon-container');
            const modalIcon = document.getElementById('session-modal-icon');
            const btnOk = document.getElementById('session-modal-btn-ok');

            // Reset styling - selalu menggunakan tema putih-biru
            modalIconContainer.className =
                'mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100/60';
            btnOk.className =
                'flex-1 px-5 py-3 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/5 rounded-xl transition-colors';

            if (type === 'success') {
                modalIcon.className = 'fa-solid fa-circle-check text-xl';
            } else if (type === 'error') {
                modalIconContainer.className =
                    'mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-500 border border-blue-100/60';
                modalIcon.className = 'fa-solid fa-circle-exclamation text-xl';
            } else {
                modalIcon.className = 'fa-solid fa-circle-info text-xl';
            }

            modalTitle.innerText = title;
            modalMessage.innerText = message;

            // Tampilkan modal dengan transisi halus
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);

            // Tutup modal saat tombol OK diklik
            btnOk.onclick = () => closeSessionModal();
        }

        function closeSessionModal() {
            const modal = document.getElementById('session-modal');
            const modalBox = modal.querySelector('div.max-w-sm');
            modal.classList.remove('opacity-100');
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 150);
        }

        // Tampilkan modal otomatis jika ada session
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                showSessionModal({
                    title: 'Berhasil!',
                    message: '{{ session('success') }}',
                    type: 'success'
                });
            @endif

            @if (session('error'))
                showSessionModal({
                    title: 'Terjadi Kesalahan',
                    message: '{{ session('error') }}',
                    type: 'error'
                });
            @endif
        });
    </script>
</body>

</html>
