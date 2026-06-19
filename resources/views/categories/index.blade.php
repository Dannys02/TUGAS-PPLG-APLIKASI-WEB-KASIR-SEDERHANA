@extends('layouts.app')

@section('content')
    <div class="flex items-center gap-3 mb-8">
        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
            <i class="fa-solid fa-tags text-xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Kategori</h1>
            <p class="text-sm text-slate-500">Kelola kelompok jenis menu untuk mempermudah navigasi pada kasir (POS)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        <div class="lg:col-span-1">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-base font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100 flex items-center gap-2">
                    <i class="fa-solid {{ isset($editCategory) ? 'fa-pen-to-square text-amber-500' : 'fa-plus text-blue-500' }}"></i>
                    {{ isset($editCategory) ? 'Edit Kategori Menu' : 'Tambah Kategori Baru' }}
                </h2>

                <form action="{{ isset($editCategory) ? route('categories.update', $editCategory->id) : route('categories.store') }}" method="POST" class="space-y-5">
                    @csrf
                    @if (isset($editCategory))
                        @method('PUT')
                    @endif

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Nama Kategori</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-4 text-slate-400">
                                <i class="fa-solid fa-tag"></i>
                            </span>
                            <input type="text" name="nama_kategori"
                                class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all"
                                required placeholder="Contoh: Makanan Utama, Minuman"
                                value="{{ old('nama_kategori', $editCategory->nama_kategori ?? '') }}">
                        </div>
                        @error('nama_kategori')
                            <p class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2.5 pt-2">
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-white {{ isset($editCategory) ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-500/10' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/10' }} font-semibold text-sm rounded-xl shadow-sm transition-colors">
                            <i class="fa-solid {{ isset($editCategory) ? 'fa-floppy-disk' : 'fa-plus' }}"></i>
                            {{ isset($editCategory) ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                        </button>

                        @if (isset($editCategory))
                            <a href="{{ route('categories.index') }}" class="w-full flex items-center justify-center px-4 py-3 border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 font-semibold text-sm rounded-xl transition-colors text-center">
                                Batal Edit
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

                <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h2 class="text-base font-bold text-slate-800">Daftar Kategori Sistem</h2>

                    <form method="GET" action="{{ route('categories.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative flex-grow sm:flex-grow-0 sm:w-64 flex items-center">
                            <span class="absolute left-3 text-slate-400 text-sm">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" placeholder="Cari nama kategori..." value="{{ request('search') }}"
                                class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl bg-white text-slate-800 text-xs focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        </div>

                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition-colors">
                            Cari
                        </button>

                        @if(request()->filled('search'))
                            <a href="{{ route('categories.index') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-xl transition-colors text-xs" title="Reset Pencarian">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                <th class="py-3.5 px-6 text-center w-20">No</th>
                                <th class="py-3.5 px-6">Nama Kategori Produk</th>
                                <th class="py-3.5 px-6 text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($categories as $c)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="py-3.5 px-6 text-center text-slate-400 font-medium text-xs">{{ $loop->iteration }}</td>
                                    <td class="py-3.5 px-6 font-semibold text-slate-700 group-hover:text-blue-600 transition-colors">{{ $c->nama_kategori }}</td>
                                    <td class="py-3.5 px-6 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('categories.edit', $c->id) }}" class="p-2 text-amber-600 bg-amber-50 border border-amber-100 hover:bg-amber-100 rounded-lg transition-colors" title="Ubah Nama">
                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $c->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus kategori {{ $c->nama_kategori }}? Semua produk di dalam kategori ini juga akan ikut terhapus otomatis.')"
                                                class="{{ isset($editCategory) ? 'hidden' : 'inline' }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-600 bg-red-50 border border-red-100 hover:bg-red-100 rounded-lg transition-colors" title="Hapus Permanen">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-14 h-14 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 mb-3">
                                                <i class="fa-solid fa-tags text-xl"></i>
                                            </div>
                                            <p class="text-sm font-bold text-slate-700">Data Kategori Kosong</p>
                                            <p class="text-xs text-slate-400 mt-1">Sistem belum mendeteksi adanya kategori produk. Gunakan formulir di sebelah kiri untuk menambahkannya.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($categories->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50/30">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
