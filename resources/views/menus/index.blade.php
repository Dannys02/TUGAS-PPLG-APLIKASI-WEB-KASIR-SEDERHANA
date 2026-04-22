@extends('layouts.app')

@section('content')
<h1 class="header-title">Manajemen Menu</h1>

<div class="card">
    <form action="{{ route('menus.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" required placeholder="Contoh: Espresso">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" required min="0" placeholder="15000">
            </div>
            <div class="form-group">
                <label>Stok Awal</label>
                <input type="number" name="stok" class="form-control" required min="0" placeholder="50">
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Menu</button>
    </form>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $m)
                <tr>
                    <td><strong>{{ $m->nama_menu }}</strong></td>
                    <td>{{ $m->category->nama_kategori ?? '-' }}</td>
                    <td style="color: var(--accent-color); font-weight: bold;">Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                    <td>
                        @if($m->stok > 10)
                            <span style="color: var(--success); font-weight: bold;">{{ $m->stok }}</span>
                        @else
                            <span style="color: var(--danger); font-weight: bold;">{{ $m->stok }}</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('menus.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada menu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
