@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center gap-3 mb-8">
        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
            <i class="fa-solid fa-sliders text-xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Toko</h1>
            <p class="text-sm text-slate-500">Kelola informasi profil bisnis dan keamanan akun UMKM Anda</p>
        </div>
    </div>

    <!-- Main Card Settings -->
    <div class="p-6 sm:p-8 bg-white border border-slate-200 rounded-2xl shadow-sm">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- SECTION 1: Logo Bisnis -->
            <div class="pb-8 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-image text-blue-500"></i> Logo Bisnis
                </h3>

                <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-start">
                    <!-- Preview Box -->
                    <div class="flex-shrink-0">
                        <div id="logoPreviewContainer" class="w-32 h-32 rounded-2xl bg-slate-50 flex items-center justify-center border-2 border-dashed border-slate-200 relative overflow-hidden transition-all duration-300 hover:border-blue-400 group">
                            @if ($user->logo)
                                <img src="{{ asset('storage/logos/' . $user->logo) }}" alt="Logo" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-3 text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <i class="fa-solid fa-store text-3xl block mb-2"></i>
                                    <span class="text-xs font-medium block">Belum ada logo</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Upload Input Control -->
                    <div class="flex-1 w-full space-y-2">
                        <label for="logo" class="block text-sm font-medium text-slate-700">Pilih File Logo Baru</label>
                        <input type="file" name="logo" id="logo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-xl p-1 bg-slate-50" accept="image/*">
                        <p class="text-xs text-slate-400 flex items-center gap-1.5 pt-1">
                            <i class="fa-solid fa-circle-info text-blue-500"></i> Format: JPEG, PNG, JPG, GIF (Maksimal 2MB)
                        </p>
                        @error('logo')
                            <span class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Informasi Profil -->
            <div class="pb-8 border-b border-slate-100">
                <h3 class="text-base font-semibold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-blue-500"></i> Informasi Profil
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            <i class="fa-solid fa-user text-slate-400 mr-1.5"></i> Nama Lengkap / Bisnis
                        </label>
                        <input type="text" name="name" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all" required placeholder="Nama pemilik bisnis / kafe" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <span class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            <i class="fa-solid fa-envelope text-slate-400 mr-1.5"></i> Alamat Email
                        </label>
                        <input type="email" name="email" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all" required placeholder="email@bisnis.com" value="{{ old('email', $user->email) }}">
                        @error('email')
                            <span class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Kredensial Keamanan -->
            <div class="p-5 sm:p-6 bg-slate-50 border border-slate-200 rounded-2xl">
                <div class="mb-4">
                    <h3 class="text-base font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-blue-500"></i> Perbarui Kata Sandi
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">Biarkan kosong jika Anda tidak ingin mengubah password lama Anda</p>
                </div>

                <!-- Input Password Saat Ini -->
                <div class="space-y-2 max-w-md mb-5">
                    <label for="current_password" class="block text-sm font-medium text-slate-700">
                        <i class="fa-solid fa-key text-slate-400 mr-1.5"></i> Password Saat Ini
                    </label>
                    <div class="relative flex items-center">
                        <input type="password" id="current_password" name="current_password" placeholder="Masukkan sandi saat ini" class="w-full pl-4 pr-12 py-3 border border-slate-200 rounded-xl bg-white text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                        <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-lg text-slate-400 hover:bg-slate-100 transition-colors" onclick="togglePassword('current_password', this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-1">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Input Password Baru & Konfirmasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            <i class="fa-solid fa-lock text-slate-400 mr-1.5"></i> Kata Sandi Baru
                        </label>
                        <input type="password" name="password" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-white text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" placeholder="Minimal 8 karakter baru">
                        @error('password')
                            <span class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">
                            <i class="fa-solid fa-lock text-slate-400 mr-1.5"></i> Konfirmasi Kata Sandi Baru
                        </label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-white text-slate-800 text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all" placeholder="Ulangi sandi baru">
                        @error('password_confirmation')
                            <span class="text-xs font-medium text-red-500 flex items-center gap-1.5 mt-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Tombol Aksi / Submit -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 justify-end">
                <a href="{{ route('pos.index') }}" class="w-full sm:w-auto order-2 sm:order-1 flex items-center justify-center gap-2 px-5 py-3 border border-slate-200 text-slate-600 bg-white hover:bg-slate-50 font-semibold text-sm rounded-xl transition-colors">
                    <i class="fa-solid chimneys-left text-sm"></i> Kembali ke Kasir
                </a>
                <button type="submit" class="w-full sm:w-auto order-1 sm:order-2 flex items-center justify-center gap-2 px-6 py-3 text-white bg-blue-600 hover:bg-blue-700 font-semibold text-sm rounded-xl shadow-sm shadow-blue-500/10 transition-colors">
                    <i class="fa-solid fa-floppy-disk text-sm"></i> Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Live Image Preview Logo Bisnis
            const logoInput = document.getElementById('logo');
            logoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const previewContainer = document.getElementById('logoPreviewContainer');
                        previewContainer.innerHTML = '<img src="' + event.target.result + '" alt="Logo Preview" class="w-full h-full object-cover opacity-0 transition-opacity duration-300" id="previewImg">';
                        setTimeout(() => {
                            document.getElementById('previewImg').classList.remove('opacity-0');
                        }, 50);
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Modern Visibility Toggle Password Field
            function togglePassword(fieldId, element) {
                const field = document.getElementById(fieldId);
                const icon = element.querySelector('i');

                if (field.type === 'password') {
                    field.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash', 'text-blue-500');
                } else {
                    field.type = 'password';
                    icon.classList.remove('fa-eye-slash', 'text-blue-500');
                    icon.classList.add('fa-eye');
                }
            }
        </script>
    @endpush
@endsection
