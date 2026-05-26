@extends('layouts.app')

@section('content')
<h1 class="header-title"><i class="fa-solid fa-tags"></i> Manajemen Kategori</h1>

<div class="card">
  <form action="{{ isset($editCategory) ? route('categories.update', $editCategory->id) : route('categories.store') }}"
    method="POST">
    @csrf
    @if (isset($editCategory))
    @method('PUT')
    @endif
    <div class="form-group">
      <label><i class="fa-solid fa-tags"></i> Nama Kategori Baru</label>
      <input type="text" name="nama_kategori" class="form-control" required placeholder="Contoh: Minuman Dingin"
      value="{{ old('nama_kategori', $editCategory->nama_kategori ?? '') }}">
    </div>
    @error('nama_kategori')
    <span class="text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span><br
    class="mb-4">
    @enderror
    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
      <button type="submit"
        class="btn btn-primary">{{ isset($editCategory) ? 'Update Kategori' : '+ Tambah Kategori' }}</button>
      @if (isset($editCategory))
      <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
      @endif
    </div>
  </form>
</div>

<div class="card">
  <form method="GET" action="{{ route('categories.index') }}"
    style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
    <div style="display: flex; gap: 1rem; flex-direction: column; align-items: flex-start;">
      <input type="text" name="search" placeholder="🔍 Cari nama kategori..." value="{{ request('search') }}"
      class="form-control" style="max-width: 400px;">
      <div class="flex items-center gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-search"></i> Cari
        </button>

        @if(request()->filled('search'))
        <a href="{{ route('categories.index') }}" class="btn btn-danger">
          <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
        @endif
      </div>
    </div>
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
          <td><strong>{{ $loop->iteration }}</strong></td>
          <td>{{ $c->nama_kategori }}</td>
          <td style="display: flex; gap: 0.5rem; text-align: center;">
            <a href="{{ route('categories.edit', $c->id) }}" class="btn btn-warning btn-sm"><i
              class="fa-solid fa-edit"></i> Edit</a>
            <form action="{{ route('categories.destroy', $c->id) }}" method="POST"
              onsubmit="return confirm('Yakin hapus kategori {{ $c->nama_kategori }}? Semua menu dalam kategori ini juga akan terhapus.')"
              style="{{ isset($editCategory) ? "display: none;" : "display: inline;" }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i>
                Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="3" style="text-align: center; padding: 2rem;">Belum ada kategori.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="margin-top: 1.5rem;">
    {{ $categories->links() }}
  </div>
</div>
@endsection