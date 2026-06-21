@extends('layouts.app')

@section('content')
    <div class="flex items-center gap-3 mb-8">
        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
            <i class="fa-solid fa-utensils text-xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Menu</h1>
            <p class="text-sm text-slate-500">Kelola daftar produk, harga jual, konfigurasi kategori, dan ketersediaan stok
                kasir</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-8">
        <h2 class="text-base font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2">
            <i class="fa-solid {{ isset($editMenu) ? 'fa-pen-to-square text-amber-500' : 'fa-plus text-blue-500' }}"></i>
            {{ isset($editMenu) ? 'Edit Informasi Menu' : 'Tambah Menu Baru' }}
        </h2>

        <form action="{{ isset($editMenu) ? route('menus.update', $editMenu->id) : route('menus.store') }}" method="POST">
            @csrf
            @if (isset($editMenu))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">Nama Menu</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-slate-400">
                            <i class="fa-solid fa-utensils text-sm"></i>
                        </span>
                        <input type="text" name="nama_menu"
                            class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all"
                            required placeholder="Contoh: Espresso Macchiato"
                            value="{{ old('nama_menu', $editMenu->nama_menu ?? '') }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">Kategori</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-slate-400 z-10 pointer-events-none">
                            <i class="fa-solid fa-tags text-sm"></i>
                        </span>
                        <select name="kategori_id"
                            class="w-full pl-11 pr-10 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all appearance-none cursor-pointer"
                            required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}"
                                    {{ old('kategori_id', $editMenu->kategori_id ?? '') == $c->id ? 'selected' : '' }}>
                                    {{ $c->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute right-4 text-slate-400 pointer-events-none">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">Harga Jual</label>
                    <div class="relative flex items-center">
                        <span
                            class="absolute left-4 text-slate-500 font-semibold text-xs bg-slate-200/60 px-2 py-1 rounded-md select-none">
                            Rp
                        </span>
                        <input type="number" name="harga"
                            class="w-full pl-14 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 text-sm font-medium focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all"
                            required min="0" placeholder="15000" value="{{ old('harga', $editMenu->harga ?? '') }}">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">Jumlah Stok</label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-slate-400">
                            <i class="fa-solid fa-box text-sm"></i>
                        </span>
                        <input type="number" name="stok"
                            class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all"
                            required min="0" placeholder="50" value="{{ old('stok', $editMenu->stok ?? '') }}">
                    </div>
                </div>
            </div>

            @error('nama_menu')
                <p class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-2">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </p>
            @enderror

            <div class="flex flex-col sm:flex-row gap-3 mt-6 justify-start">
                <button type="submit"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3 text-white {{ isset($editMenu) ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/10' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/10' }} font-semibold text-sm rounded-xl shadow-sm transition-colors">
                    <i class="fa-solid {{ isset($editMenu) ? 'fa-floppy-disk' : 'fa-plus' }}"></i>
                    {{ isset($editMenu) ? 'Simpan Perubahan' : 'Tambah Menu' }}
                </button>

                @if (isset($editMenu))
                    <a href="{{ route('menus.index') }}"
                        class="w-full sm:w-auto flex items-center justify-center px-6 py-3 border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 font-semibold text-sm rounded-xl transition-colors text-center">
                        Batal Edit
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

        <div
            class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-base font-bold text-slate-800">Daftar Menu Tersedia</h2>

            <form method="GET" action="{{ route('menus.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-grow sm:flex-grow-0 sm:w-64 flex items-center">
                    <span class="absolute left-3 text-slate-400 text-sm">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" placeholder="Cari masukan nama menu..."
                        value="{{ request('search') }}"
                        class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl bg-white text-slate-800 text-xs focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                </div>

                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition-colors">
                    Cari
                </button>

                @if (request()->filled('search'))
                    <a href="{{ route('menus.index') }}"
                        class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-xl transition-colors text-xs"
                        title="Reset Pencarian">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3.5 px-6 text-center w-20">No</th>
                        <th class="py-3.5 px-6">Nama Menu</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Harga</th>
                        <th class="py-3.5 px-6 text-center w-36">Stok</th>
                        <th class="py-3.5 px-6 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($menus as $m)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="py-3.5 px-6 text-center text-slate-400 font-medium text-xs">{{ $loop->iteration }}
                            </td>
                            <td
                                class="py-3.5 px-6 font-semibold text-slate-700 group-hover:text-blue-600 transition-colors">
                                {{ $m->nama_menu }}</td>
                            <td class="py-3.5 px-6">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100/60 shadow-sm">
                                    {{ $m->category->nama_kategori ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-6 font-bold text-slate-800 tracking-tight">
                                <span
                                    class="text-xs font-normal text-slate-400 mr-0.5">Rp</span>{{ number_format($m->harga, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                @if ($m->stok > 10)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        {{ $m->stok }}
                                    </span>
                                @elseif($m->stok > 0)
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ $m->stok }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Habis
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('menus.edit', $m->id) }}"
                                        class="p-2 text-amber-600 bg-amber-50 border border-amber-100 hover:bg-amber-100 rounded-lg transition-colors"
                                        title="Ubah Menu">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('menus.destroy', $m->id) }}" method="POST"
                                        onsubmit="confirmDelete(event, this, 'Apakah Anda yakin ingin menghapus menu {{ addslashes($m->nama_menu) }}?')"
                                        class="{{ isset($editMenu) ? 'hidden' : 'inline' }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-red-600 bg-red-50 border border-red-100 hover:bg-red-100 rounded-lg transition-colors"
                                            title="Hapus Item">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div
                                        class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-3">
                                        <i class="fa-solid fa-utensils text-xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-700">Data Menu Kosong</p>
                                    <p class="text-xs text-slate-400 mt-1">Sistem belum mendeteksi adanya data menu produk
                                        kuliner Anda. Gunakan form di atas untuk memasukkan menu baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($menus->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/30 overflow-x-auto">
                <div class="flex justify-center">
                    {{ $menus->onEachSide(0)->links() }}
                </div>
            </div>
        @endif
    </div>

    <div id="custom-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-150">
        <div
            class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-transform duration-150 shadow-xl flex flex-col items-center text-center">

            <div id="modal-icon-container" class="mb-4"></div>

            <h3 id="modal-title" class="text-lg font-bold text-slate-800 mb-1"></h3>
            <p id="modal-message" class="text-sm text-slate-500 mb-6"></p>

            <div class="flex gap-3 w-full">
                <button id="modal-btn-cancel" onclick="closeModal()"
                    class="w-full px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors font-medium hidden">Batal</button>
                <button id="modal-btn-ok"
                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors font-medium">Oke</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let modalTimer;

            // Fungsi khusus untuk menangani Delete Form
            function confirmDelete(event, formElement, message) {
                event.preventDefault(); // Cegah form langsung terkirim

                showModal({
                    title: 'Konfirmasi Hapus Data',
                    message: message,
                    type: 'error', // Memunculkan ikon silang merah / warning
                    showCancel: true,
                    onConfirm: () => {
                        formElement.submit(); // Submit form ke server jika klik Oke
                    }
                });
            }

            function showModal({
                title,
                message,
                type = 'info',
                showCancel = false,
                onConfirm = null
            }) {
                clearTimeout(modalTimer);

                const modal = document.getElementById('custom-modal');
                const modalBox = modal.querySelector('div.max-w-sm');
                const modalTitle = document.getElementById('modal-title');
                const modalMessage = document.getElementById('modal-message');
                const modalIconContainer = document.getElementById('modal-icon-container');
                const btnCancel = document.getElementById('modal-btn-cancel');
                const btnOk = document.getElementById('modal-btn-ok');

                modalTitle.textContent = title;
                modalMessage.textContent = message;

                let iconHtml = '';
                if (type === 'error') {
                    iconHtml = `<div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-2xl">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>`;
                    btnOk.className =
                        "w-full px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl transition-colors font-medium";
                    btnOk.textContent = "Ya, Hapus";
                } else {
                    iconHtml = `<div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                                <i class="fa-solid fa-info-circle"></i>
                            </div>`;
                    btnOk.className =
                        "w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors font-medium";
                    btnOk.textContent = "Oke";
                }

                modalIconContainer.innerHTML = iconHtml;

                if (showCancel) {
                    btnCancel.classList.remove('hidden');
                } else {
                    btnCancel.classList.add('hidden');
                }

                btnOk.onclick = function() {
                    closeModal();
                    if (typeof onConfirm === 'function') onConfirm();
                };

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modal.classList.add('opacity-100');
                    modalBox.classList.remove('scale-95');
                    modalBox.classList.add('scale-100');
                }, 10);
            }

            function closeModal() {
                const modal = document.getElementById('custom-modal');
                const modalBox = modal.querySelector('div.max-w-sm');
                modal.classList.remove('opacity-100');
                modalBox.classList.remove('scale-100');
                modalBox.classList.add('scale-95');

                modalTimer = setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 150);
            }
        </script>
    @endpush
@endsection
