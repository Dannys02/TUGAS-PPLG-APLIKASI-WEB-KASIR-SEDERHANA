<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lupa Password | Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-[#f8f5f2] min-h-screen flex flex-col space-y-4 items-center justify-center px-4">
    @if (session('success'))
        <div id="alert" class="alert alert-success bg-green-600">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">

        <div class="bg-[#6f4e37] py-8 px-6 text-center">
            <h1 class="text-3xl font-bold text-white">Lupa Password</h1>
            <p class="text-[#f3e9dc] mt-2 text-sm">
                Masukkan email Anda untuk mendapatkan link reset password
            </p>
        </div>

        <div class="p-8">
            <form action="{{ route('forgot-password.send') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block mb-2 text-sm font-semibold text-[#5c4033]">
                        Email
                    </label>
                    <input type="email" id="email" name="email" placeholder="Email Anda"
                        class="w-full px-4 py-3 rounded-xl border border-[#d6c3b3]
          focus:outline-none focus:ring-2 focus:ring-[#6f4e37]
          bg-[#fffdfb]"
                        required>

                    @error('email')
                        <p class="error text-red-500 text-sm mt-2 transition-opacity duration-500 opacity-100">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <button type="submit"
                    class="w-full bg-[#6f4e37] hover:bg-[#5c4033]
          text-white font-semibold py-3 rounded-xl transition duration-300">
                    Kirim Link Reset
                </button>

                <div class="text-center">
                    <p class="text-sm text-[#5c4033]">
                        Kembali ke halaman
                        <a href="{{ route('login') }}" class="text-[#6f4e37] font-semibold hover:underline">
                            Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tunggu sampai seluruh DOM selesai dimuat
        document.addEventListener("DOMContentLoaded", () => {
            // Ambil semua elemen dengan class 'error'
            const errorMessages = document.querySelectorAll(".error");

            if (errorMessages.length > 0) {
                // Setelah 4.5 detik, buat teksnya pudar perlahan (fade out)
                setTimeout(() => {
                    errorMessages.forEach(el => {
                        el.classList.remove("opacity-100");
                        el.classList.add("opacity-0");
                    });
                }, 4500);

                // Tepat di detik ke-5, hilangkan space/ruang elemennya (display: none)
                setTimeout(() => {
                    errorMessages.forEach(el => {
                        el.style.display = "none";
                    });
                }, 5000);
            }
        });
    </script>

</body>

</html>
