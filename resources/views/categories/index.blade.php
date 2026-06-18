@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <div class="bg-blue-100 text-blue-600 px-4 py-1.5 rounded-lg">
                <i class="fa-solid fa-tags text-2xl"></i>
            </div>
            Manajemen Kategori
        </h1>
        <p class="text-gray-500 mt-1">Kelola kategori menu untuk aplikasi kasir Anda.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah/Edit Kategori -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2 border-gray-100">
                {{ isset($editCategory) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
            </h2>
            <form action="{{ isset($editCategory) ? route('categories.update', $editCategory->id) : route('categories.store') }}" method="POST">
                @csrf
                @if (isset($editCategory))
                    @method('PUT')
                @endif
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-tag text-gray-400"></i>
                        </div>
                        <input type="text" name="nama_kategori"
                            class="pl-10 w-full rounded-lg border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-gray-50 hover:bg-white"
                            required placeholder="Contoh: Minuman Dingin"
                            value="{{ old('nama_kategori', $editCategory->nama_kategori ?? '') }}">
                    </div>
                    @error('nama_kategori')
                        <p class="text-red-500 text-sm mt-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors shadow-sm flex justify-center items-center gap-2">
                        <i class="fa-solid {{ isset($editCategory) ? 'fa-save' : 'fa-plus' }}"></i>
                        {{ isset($editCategory) ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                    </button>
                    @if (isset($editCategory))
                        <a href="{{ route('categories.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition-colors text-center">
                            Batal
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Kategori -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
                <h2 class="text-xl font-semibold text-gray-800">Daftar Kategori</h2>
                <form method="GET" action="{{ route('categories.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative flex-grow sm:flex-grow-0 sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" placeholder="Cari kategori..." value="{{ request('search') }}"
                            class="pl-10 w-full rounded-lg border-gray-300 border px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors text-sm font-medium">
                        Cari
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('categories.index') }}" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2 rounded-lg transition-colors" title="Reset">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-sm">
                            <th class="py-3 px-6 font-semibold text-gray-600 text-center">No</th>
                            <th class="py-3 px-6 font-semibold text-gray-600">Nama Kategori</th>
                            <th class="py-3 px-6 font-semibold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($categories as $c)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="py-3 px-6 text-center text-gray-500">{{ $loop->iteration }}</td>
                                <td class="py-3 px-6 font-medium text-gray-800">{{ $c->nama_kategori }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('categories.edit', $c->id) }}" class="bg-amber-100 text-amber-700 hover:bg-amber-200 p-2 rounded-md transition-colors" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $c->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus kategori {{ $c->nama_kategori }}? Semua menu dalam kategori ini juga akan terhapus.')"
                                            class="{{ isset($editCategory) ? 'hidden' : 'inline' }}">
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
                                <td colspan="3" class="py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-100 p-4 rounded-full mb-3 text-gray-400">
                                            <i class="fa-solid fa-tags text-3xl"></i>
                                        </div>
                                        <p class="text-lg font-medium">Belum ada kategori</p>
                                        <p class="text-sm">Tambahkan kategori baru untuk mulai mengelola menu.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
