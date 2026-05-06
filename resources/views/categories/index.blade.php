@extends('layouts.app')

@section('content')
    <h1 class="header-title">Manajemen Kategori</h1>

    <div class="card">
        <form action="{{ isset($editCategory) ? route('categories.update', $editCategory->id) : route('categories.store') }}"
            method="POST">
            @csrf
            @if (isset($editCategory))
                @method('PUT')
            @endif
            <div class="form-group">
                <label>Nama Kategori Baru</label>
                <input type="text" name="nama_kategori" class="form-control" required placeholder="Contoh: Minuman Dingin"
                    value="{{ old('nama_kategori', $editCategory->nama_kategori ?? '') }}">
            </div>
            @error('nama_kategori')
                <span class="text-red-600">{{ $message }}</span><br class="mb-4">
            @enderror
            <button type="submit"
                class="btn btn-primary {{ $errors->has('nama_kategori') ? 'mt-4' : '' }}">{{ isset($editCategory) ? 'Update Kategori' : '+ Tambah Kategori' }}</button>
            @if (isset($editCategory))
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
            @endif
        </form>
    </div>

    <div class="rounded-lg mb-6">
        <form method="GET" action="{{ route('categories.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="w-full">
                <input type="text" name="search" placeholder="Cari nama kategori..." value="{{ request('search') }}"
                    class="w-full p-4 rounded-lg">
            </div>
            <button type="submit" class="btm btn-primary text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $c)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $c->nama_kategori }}</td>
                            <td style="display: flex; gap: 0.5rem; justify-content: flex-start;">
                                <a href="{{ route('categories.edit', $c->id) }}" class="btn btn-warning btn-sm"><i
                                        class="fa-solid fa-edit"></i> Edit</a>
                                <form action="{{ route('categories.destroy', $c->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus kategori {{ $c->nama_kategori }}? Semua menu dalam kategori ini juga akan terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i>
                                        Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center;">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                    <div style="margin-top: 1rem;">
                        {{ $categories->links() }}
                    </div>
                </tbody>
            </table>
        </div>
    </div>
@endsection
