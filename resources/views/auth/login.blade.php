<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Aplikasi Kasir</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col space-y-4 items-center justify-center px-4 font-sans text-gray-800 antialiased">

    @if (session('success'))
        <div id="alert" class="alert alert-success bg-green-600 mt-6">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-blue-600 py-8 px-6 text-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-blue-500 opacity-90"></div>
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
            <h1 class="text-3xl font-bold text-white relative z-10">Aplikasi Kasir</h1>
            <p class="text-blue-100 mt-2 text-sm relative z-10">
                Selamat datang kembali
            </p>
        </div>

        <div class="p-8">
            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block mb-2 text-sm font-semibold text-gray-700">
                        Email
                    </label>
                    <input type="email" id="email" name="email" placeholder="Email Anda"
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
                        Password
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

                <p class="text-sm text-gray-600">
                    Lupa password?
                    <a href="{{ route('forgot-password') }}" class="text-blue-600 font-semibold hover:text-blue-700 hover:underline">
                        Reset password
                    </a>
                </p>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700
          text-white font-semibold py-3 rounded-xl shadow-md transition duration-300 transform hover:-translate-y-0.5">
                    Login
                </button>
                <div>
                    <p class="text-sm text-center text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:text-blue-700 hover:underline">
                            Daftar di sini
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

        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alert = document.getElementById('alert');
            if (alert) {
                alert.style.animation = 'slideDown 0.3s ease reverse';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            }
        }, 5000);
    </script>

</body>

</html>
