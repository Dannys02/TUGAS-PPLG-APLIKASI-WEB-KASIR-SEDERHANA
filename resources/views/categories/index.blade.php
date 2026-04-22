@extends('layouts.app')

@section('content')
<h1 class="header-title">Manajemen Kategori</h1>

<div class="card">
    <form action="{{ isset($editCategory) ? route('categories.update', $editCategory->id) : route('categories.store') }}" method="POST">
        @csrf
        @if(isset($editCategory))
            @method('PUT')
        @endif
        <div class="form-group">
            <label>Nama Kategori Baru</label>
            <input type="text" name="nama_kategori" class="form-control" required placeholder="Contoh: Minuman Dingin" value="{{ old('nama_kategori', $editCategory->nama_kategori ?? '') }}">
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($editCategory) ? 'Update Kategori' : '+ Tambah Kategori'}}</button>
    </form>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $c)
                <tr>
                    <td>{{ $c->id }}</td>
                    <td>{{ $c->nama_kategori }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $c->id) }}" class="btn btn-warning btn-sm"><i class="fa-solid fa-edit"></i> Edit</a>
                        <form action="{{ route('categories.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Belum ada kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
