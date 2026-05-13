@extends('layouts.app')

@section('content')
    <h1 class="header-title"><i class="fa-solid fa-gear"></i> Pengaturan Profil</h1>

    <div class="card">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Logo Section -->
            <div style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border-color);">
                <label style="display: block; margin-bottom: 1rem; font-weight: 600; color: var(--primary-dark);">
                    <i class="fa-solid fa-image"></i> Logo Bisnis
                </label>
                <div style="display: flex; gap: 2rem; align-items: flex-start;">
                    <div style="flex: 0 0 auto;">
                        <div style="width: 150px; height: 150px; border-radius: 12px; background: var(--bg-color); display: flex; align-items: center; justify-content: center; border: 2px dashed var(--border-color); position: relative; overflow: hidden;">
                            @if ($user->logo && file_exists(public_path($user->logo)))
                                <img src="{{ asset($user->logo) }}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: cover;">
                            @else
                                <div style="text-align: center; color: var(--text-muted);">
                                    <i class="fa-solid fa-image" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                                    <small>Belum ada logo</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div style="flex: 1;">
                        <input type="file" name="logo" id="logo" class="form-control" accept="image/*" style="margin-bottom: 1rem;">
                        <small style="color: var(--text-muted);">
                            <i class="fa-solid fa-info-circle"></i> Format: JPEG, PNG, JPG, GIF (Maksimal 2MB)
                        </small>
                        @error('logo')
                            <br><span class="text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div class="form-group">
                    <label><i class="fa-solid fa-user"></i> Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required placeholder="Nama pemilik bisnis"
                        value="{{ old('name', $user->name) }}">
                    @error('name')
                        <span class="text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label><i class="fa-solid fa-envelope"></i> Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="email@bisnis.com"
                        value="{{ old('email', $user->email) }}">
                    @error('email')
                        <span class="text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

            </div>

            <!-- Password Section -->
            <div style="padding: 1.5rem; background: rgba(111, 78, 55, 0.05); border-radius: 12px; margin-bottom: 2rem; border-left: 4px solid var(--primary-color);">
                <h3 style="color: var(--primary-dark); margin-bottom: 1rem; font-size: 1rem;">
                    <i class="fa-solid fa-lock"></i> Ubah Password
                </h3>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1rem;">
                    Kosongkan jika tidak ingin mengubah password
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label><i class="fa-solid fa-lock"></i> Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter">
                        @error('password')
                            <span class="text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-lock"></i> Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                        @error('password_confirmation')
                            <span class="text-red-600"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-check"></i> Simpan Perubahan
                </button>
                <a href="{{ route('pos.index') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Preview logo image
            const logoInput = document.getElementById('logo');
            logoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const preview = document.querySelector('[style*="width: 150px"]');
                        preview.innerHTML = '<img src="' + event.target.result + '" alt="Logo Preview" style="max-width: 100%; max-height: 100%; object-fit: cover;">';
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
    @endpush
@endsection
