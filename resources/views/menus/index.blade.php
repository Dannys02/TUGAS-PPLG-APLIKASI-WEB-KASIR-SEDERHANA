@extends('layouts.app')

@section('content')
    <h1 class="header-title"><i class="fa-solid fa-utensils"></i> Manajemen Menu</h1>

    <div class="card">
        <form action="{{ isset($editMenu) ? route('menus.update', $editMenu->id) : route('menus.store') }}" method="POST">
            @csrf
            @if (isset($editMenu))
                @method('PUT')
            @endif
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label><i class="fa-solid fa-utensils"></i> Nama Menu</label>
                    <input type="text" name="nama_menu" class="form-control" required placeholder="Contoh: Espresso"
                        value="{{ old('nama_menu', $editMenu->nama_menu ?? '') }}">
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-tags"></i> Kategori</label>
                    <select name="kategori_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}"
                                {{ old('kategori_id', $editMenu->kategori_id ?? '') == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-money-bill"></i> Harga (Rp)</label>
                    <input type="number" name="harga" class="form-control" required min="0" placeholder="15000"
                        value="{{ old('harga', $editMenu->harga ?? '') }}">
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-box"></i> Stok Awal</label>
                    <input type="number" name="stok" class="form-control" required min="0" placeholder="50"
                        value="{{ old('stok', $editMenu->stok ?? '') }}">
                </div>
            </div>

            @error('nama_menu')
                <span class="text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span><br
                    class="mb-4">
            @enderror

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                    {{ isset($editMenu) ? 'Update Menu' : 'Tambah Menu' }}</button>
                @if (isset($editMenu))
                    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Batal</a>
                @endif
            </div>
        </form>
    </div>

    <div class="card">
        <form method="GET" action="{{ route('menus.index') }}"
            style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem; flex-direction: column; align-items: flex-start;">
                <input type="search" name="search" placeholder="🔍 Cari nama menu..." value="{{ request('search') }}"
                    class="form-control" style="max-width: 400px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
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
                            <td><strong>{{ $loop->iteration }}</strong></td>
                            <td><strong>{{ $m->nama_menu }}</strong></td>
                            <td>{{ $m->category->nama_kategori ?? '-' }}</td>
                            <td style="color: var(--primary-dark); font-weight: bold;">Rp
                                {{ number_format($m->harga, 0, ',', '.') }}</td>
                            <td>
                                @if ($m->stok > 10)
                                    <span style="color: var(--success); font-weight: bold;"><i
                                            class="fa-solid fa-check-circle"></i> {{ $m->stok }}</span>
                                @elseif($m->stok > 0)
                                    <span style="color: var(--warning); font-weight: bold;"><i
                                            class="fa-solid fa-exclamation-circle"></i> {{ $m->stok }}</span>
                                @else
                                    <span style="color: var(--danger); font-weight: bold;"><i
                                            class="fa-solid fa-times-circle"></i> {{ $m->stok }}</span>
                                @endif
                            </td>
                            <td style="display: flex; gap: 0.5rem; justify-content: flex-start; flex-wrap: wrap;">
                                <a href="{{ route('menus.edit', $m->id) }}" class="btn btn-warning btn-sm"><i
                                        class="fa-solid fa-pen-to-square"></i> Edit</a>
                                <form action="{{ route('menus.destroy', $m->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus menu {{ $m->nama_menu }}?')"
                                        class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">Belum ada menu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1.5rem;">
            {{ $menus->links() }}
        </div>
    </div>
@endsection
