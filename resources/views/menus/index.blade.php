@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 px-4 py-1.5 rounded-lg">
                <i class="fa-solid fa-utensils text-2xl"></i>
            </div>
            Manajemen Menu
        </h1>
        <p class="text-gray-500 mt-1">Kelola daftar menu, harga, dan stok yang tersedia.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-100">
        {{ isset($editMenu) ? 'Edit Menu' : 'Tambah Menu Baru' }}
    </h2>
    <form action="{{ isset($editMenu) ? route('menus.update', $editMenu->id) : route('menus.store') }}" method="POST">
        @csrf
        @if (isset($editMenu))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Menu</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-utensils text-gray-400"></i>
                    </div>
                    <input type="text" name="nama_menu"
                        class="pl-10 w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white"
                        required placeholder="Contoh: Espresso"
                        value="{{ old('nama_menu', $editMenu->nama_menu ?? '') }}">
                </div>
            </div>

            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-tags text-gray-400"></i>
                    </div>
                    <select name="kategori_id" class="pl-10 w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white appearance-none" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" {{ old('kategori_id', $editMenu->kategori_id ?? '') == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga (Rp)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-money-bill text-gray-400"></i>
                    </div>
                    <input type="number" name="harga"
                        class="pl-10 w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white"
                        required min="0" placeholder="15000"
                        value="{{ old('harga', $editMenu->harga ?? '') }}">
                </div>
            </div>

            <div class="mb-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-box text-gray-400"></i>
                    </div>
                    <input type="number" name="stok"
                        class="pl-10 w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white"
                        required min="0" placeholder="50"
                        value="{{ old('stok', $editMenu->stok ?? '') }}">
                </div>
            </div>
        </div>

        @error('nama_menu')
            <p class="text-red-500 text-sm mt-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
        @enderror

        <div class="flex flex-col sm:flex-row gap-3 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2">
                <i class="fa-solid {{ isset($editMenu) ? 'fa-save' : 'fa-plus' }}"></i>
                {{ isset($editMenu) ? 'Simpan Perubahan' : 'Tambah Menu' }}
            </button>
            @if (isset($editMenu))
                <a href="{{ route('menus.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-colors text-center">
                    Batal
                </a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Menu</h2>
        <form method="GET" action="{{ route('menus.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" name="search" placeholder="Cari menu..." value="{{ request('search') }}"
                    class="pl-10 w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                Cari
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('menus.index') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg transition-colors" title="Reset">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-sm">
                    <th class="py-3 px-6 font-semibold text-gray-600  text-center">No</th>
                    <th class="py-3 px-6 font-semibold text-gray-600">Menu</th>
                    <th class="py-3 px-6 font-semibold text-gray-600">Kategori</th>
                    <th class="py-3 px-6 font-semibold text-gray-600">Harga</th>
                    <th class="py-3 px-6 font-semibold text-gray-600 text-center ">Stok</th>
                    <th class="py-3 px-6 font-semibold text-gray-600 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($menus as $m)
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="py-3 px-6 text-center text-gray-500">{{ $loop->iteration }}</td>
                        <td class="py-3 px-6 font-medium text-gray-800">{{ $m->nama_menu }}</td>
                        <td class="py-3 px-6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $m->category->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-6 font-medium text-gray-900">
                            Rp {{ number_format($m->harga, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-6 text-center">
                            @if ($m->stok > 10)
                                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-600">
                                    <i class="fa-solid fa-check-circle"></i> {{ $m->stok }}
                                </span>
                            @elseif($m->stok > 0)
                                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-amber-500">
                                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $m->stok }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-600">
                                    <i class="fa-solid fa-times-circle"></i> Habis
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('menus.edit', $m->id) }}" class="bg-amber-100 text-amber-700 hover:bg-amber-200 p-2 rounded-md transition-colors" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('menus.destroy', $m->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus menu {{ $m->nama_menu }}?')"
                                    class="{{ isset($editMenu) ? 'hidden' : 'inline' }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 p-2 rounded-md transition-colors" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="bg-gray-100 p-4 rounded-full mb-3 text-gray-400">
                                    <i class="fa-solid fa-utensils text-3xl"></i>
                                </div>
                                <p class="text-lg font-medium">Belum ada menu</p>
                                <p class="text-sm">Tambahkan menu baru ke daftar Anda.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($menus->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $menus->links() }}
        </div>
    @endif
</div>
@endsection
