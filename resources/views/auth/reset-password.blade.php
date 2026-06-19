<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password | Aplikasi Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="bg-gray-50 min-h-screen flex flex-col space-y-4 items-center justify-center px-4 font-sans text-gray-800 antialiased">

    {{-- Modal Notifikasi Session --}}
    <div id="session-modal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 transition-opacity duration-300">
        <div
            class="w-full max-w-sm transform overflow-hidden rounded-2xl bg-white p-6 text-center shadow-2xl transition-all scale-95 duration-300 border border-slate-100">
            <div id="session-modal-icon-container"
                class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100/60">
                <i id="session-modal-icon" class="fa-solid fa-circle-check text-xl"></i>
            </div>
            <h3 id="session-modal-title" class="text-base font-bold text-slate-800 mb-1.5">Notifikasi</h3>
            <p id="session-modal-message" class="text-xs text-slate-400 mb-6 leading-relaxed">
                Keterangan info sistem...
            </p>
            <div class="flex justify-center w-full">
                <button id="session-modal-btn-ok"
                    class="flex-1 px-5 py-3 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/5 rounded-xl transition-colors">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-blue-600 py-8 px-6 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-blue-500 opacity-90"></div>
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
            <h1 class="text-3xl font-bold text-white relative z-10">Reset Password</h1>
            <p class="text-blue-100 mt-2 text-sm relative z-10">
                Buat password baru untuk akun Anda
            </p>
        </div>

        <div class="p-8">
            <form action="{{ route('password.update', ['token' => $token]) }}" method="POST" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block mb-2 text-sm font-semibold text-gray-700">
                        Email
                    </label>
                    <input type="email" id="email" name="email" placeholder="Email Anda"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300
          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
          bg-gray-50 text-gray-900 transition-colors">

                    @error('email')
                        <p class="error text-red-500 text-sm mt-2 transition-opacity duration-500 opacity-100">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-semibold text-gray-700">
                        Password Baru
                    </label>
                    <input type="password" id="password" name="password" placeholder="********"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300
          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
          bg-gray-50 text-gray-900 transition-colors">

                    @error('password')
                        <p class="error text-red-500 text-sm mt-2 transition-opacity duration-500 opacity-100">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-gray-700">
                        Konfirmasi Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="********"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300
          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
          bg-gray-50 text-gray-900 transition-colors">

                    @error('password_confirmation')
                        <p class="error text-red-500 text-sm mt-2 transition-opacity duration-500 opacity-100">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700
          text-white font-semibold py-3 rounded-xl shadow-md transition duration-300 transform hover:-translate-y-0.5">
                    Reset Password
                </button>

                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Kembali ke halaman
                        <a href="{{ route('login') }}"
                            class="text-blue-600 font-semibold hover:text-blue-700 hover:underline">
                            Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // --- ENGINE MODAL SESSION (Tema Putih-Biru) ---
        function showSessionModal({
            title,
            message,
            type = 'success'
        }) {
            const modal = document.getElementById('session-modal');
            const modalBox = modal.querySelector('div.max-w-sm');
            const modalTitle = document.getElementById('session-modal-title');
            const modalMessage = document.getElementById('session-modal-message');
            const modalIconContainer = document.getElementById('session-modal-icon-container');
            const modalIcon = document.getElementById('session-modal-icon');
            const btnOk = document.getElementById('session-modal-btn-ok');

            // Reset styling - selalu menggunakan tema putih-biru
            modalIconContainer.className =
                'mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 border border-blue-100/60';
            btnOk.className =
                'flex-1 px-5 py-3 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/5 rounded-xl transition-colors';

            if (type === 'success') {
                modalIcon.className = 'fa-solid fa-circle-check text-xl';
            } else if (type === 'error') {
                modalIconContainer.className =
                    'mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-500 border border-blue-100/60';
                modalIcon.className = 'fa-solid fa-circle-exclamation text-xl';
            } else {
                modalIcon.className = 'fa-solid fa-circle-info text-xl';
            }

            modalTitle.innerText = title;
            modalMessage.innerText = message;

            // Tampilkan modal dengan transisi halus
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.add('opacity-100');
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);

            // Tutup modal saat tombol OK diklik
            btnOk.onclick = () => closeSessionModal();
        }

        function closeSessionModal() {
            const modal = document.getElementById('session-modal');
            const modalBox = modal.querySelector('div.max-w-sm');
            modal.classList.remove('opacity-100');
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 150);
        }

        // Tampilkan modal otomatis jika ada session
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                showSessionModal({
                    title: 'Berhasil!',
                    message: '{{ session('success') }}',
                    type: 'success'
                });
            @endif

            // Auto fade-out validasi error pada input
            const errorMessages = document.querySelectorAll('.error');
            if (errorMessages.length > 0) {
                setTimeout(() => {
                    errorMessages.forEach(el => {
                        el.classList.remove('opacity-100');
                        el.classList.add('opacity-0');
                    });
                }, 4500);
                setTimeout(() => {
                    errorMessages.forEach(el => {
                        el.style.display = 'none';
                    });
                }, 5000);
            }
        });
    </script>

</body>

</html>
