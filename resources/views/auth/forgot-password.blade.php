<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lupa Password | Aplikasi Kasir</title>
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
            <p id="session-modal-message" class="text-xs text-slate-400 mb-4 leading-relaxed">
                Keterangan info sistem...
            </p>

            {{-- Area link reset (tersembunyi by default) --}}
            <div id="session-modal-link-area" class="hidden mb-4">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-left">
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Link Reset Password</p>
                    <p id="session-modal-link-text" class="text-[11px] text-blue-600 break-all leading-relaxed font-medium"></p>
                </div>
                <div class="flex gap-2 mt-3">
                    <button id="session-modal-btn-copy"
                        class="flex-1 px-4 py-2.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5">
                        <i class="fa-regular fa-copy text-sm"></i>
                        Salin Link
                    </button>
                    <a id="session-modal-btn-open" href="#" target="_self"
                        class="flex-1 px-4 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/10 rounded-xl transition-all duration-200 flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
                        Buka Link
                    </a>
                </div>
            </div>

            <div class="flex justify-center w-full">
                <button id="session-modal-btn-ok"
                    class="flex-1 px-5 py-3 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/5 rounded-xl transition-colors">
                    Oke, Mengerti
                </button>
            </div>
        </div>
    </div>

    {{-- Notifikasi Salin Berhasil (Toast) --}}
    <div id="copy-toast"
        class="fixed top-6 left-1/2 -translate-x-1/2 z-[10000] hidden items-center gap-2 bg-slate-800 text-white px-5 py-3 rounded-xl shadow-2xl text-xs font-semibold transition-all duration-300 opacity-0 -translate-y-2">
        <i class="fa-solid fa-check-circle text-emerald-400"></i>
        <span>Link berhasil disalin!</span>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-blue-600 py-8 px-6 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-blue-500 opacity-90"></div>
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
            <h1 class="text-3xl font-bold text-white relative z-10">Lupa Password</h1>
            <p class="text-blue-100 mt-2 text-sm relative z-10">
                Masukkan email Anda untuk mendapatkan link reset password
            </p>
        </div>

        <div class="p-8">
            <form action="{{ route('forgot-password.send') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block mb-2 text-sm font-semibold text-gray-700">
                        Email
                    </label>
                    <input type="email" id="email" name="email" placeholder="Email Anda"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300
          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
          bg-gray-50 text-gray-900 transition-colors" value="{{ old('email') }}">

                    @error('email')
                        <p class="error text-red-500 text-sm mt-2 transition-opacity duration-500 opacity-100">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700
          text-white font-semibold py-3 rounded-xl shadow-md transition duration-300 transform hover:-translate-y-0.5">
                    Kirim Link Reset
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
            type = 'success',
            resetUrl = null
        }) {
            const modal = document.getElementById('session-modal');
            const modalBox = modal.querySelector('div.max-w-sm');
            const modalTitle = document.getElementById('session-modal-title');
            const modalMessage = document.getElementById('session-modal-message');
            const modalIconContainer = document.getElementById('session-modal-icon-container');
            const modalIcon = document.getElementById('session-modal-icon');
            const btnOk = document.getElementById('session-modal-btn-ok');
            const linkArea = document.getElementById('session-modal-link-area');
            const linkText = document.getElementById('session-modal-link-text');
            const btnCopy = document.getElementById('session-modal-btn-copy');
            const btnOpen = document.getElementById('session-modal-btn-open');

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

            // Tampilkan area link jika ada reset URL
            if (resetUrl) {
                linkArea.classList.remove('hidden');
                linkText.innerText = resetUrl;
                btnOpen.href = resetUrl;

                // Handler salin link
                btnCopy.onclick = () => {
                    navigator.clipboard.writeText(resetUrl).then(() => {
                        showCopyToast();
                        btnCopy.innerHTML = '<i class="fa-solid fa-check text-sm"></i> Tersalin!';
                        btnCopy.classList.add('bg-emerald-50', 'text-emerald-600', 'border-emerald-200');
                        btnCopy.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
                        setTimeout(() => {
                            btnCopy.innerHTML = '<i class="fa-regular fa-copy text-sm"></i> Salin Link';
                            btnCopy.classList.remove('bg-emerald-50', 'text-emerald-600', 'border-emerald-200');
                            btnCopy.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
                        }, 2000);
                    });
                };
            } else {
                linkArea.classList.add('hidden');
            }

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

        function showCopyToast() {
            const toast = document.getElementById('copy-toast');
            toast.classList.remove('hidden');
            toast.classList.add('flex');
            setTimeout(() => {
                toast.classList.remove('opacity-0', '-translate-y-2');
                toast.classList.add('opacity-100', 'translate-y-0');
            }, 10);
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'translate-y-0');
                toast.classList.add('opacity-0', '-translate-y-2');
                setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('flex');
                }, 300);
            }, 2000);
        }

        // Tampilkan modal otomatis jika ada session
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('success'))
                showSessionModal({
                    title: 'Berhasil!',
                    message: '{{ session('success') }}',
                    type: 'success',
                    @if (session('reset_url'))
                        resetUrl: '{{ session('reset_url') }}'
                    @endif
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
