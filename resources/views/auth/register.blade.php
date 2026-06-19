<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register | Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col space-y-4 items-center justify-center px-4 font-sans text-gray-800 antialiased">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-blue-600 py-8 px-6 text-center relative overflow-hidden">

            <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-blue-500 opacity-90"></div>
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>

            <h1 class="text-3xl font-bold text-white relative z-10">Daftar Akun</h1>
            <p class="text-blue-100 mt-2 text-sm relative z-10">
                Buat akun baru untuk mulai menggunakan aplikasi
            </p>
        </div>

        <div class="p-8">
            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="block mb-2 text-sm font-semibold text-[#5c4033]">
                        Nama Lengkap
                    </label>

                    <input type="text" id="name" name="name" placeholder="Nama Lengkap Anda"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300
          focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
          bg-gray-50 text-gray-900 transition-colors">

                    @error('name')
                        <p class="error text-red-500 text-sm mt-2 transition-opacity duration-500 opacity-100">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block mb-2 text-sm font-semibold text-[#5c4033]">
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
                    <label for="password" class="block mb-2 text-sm font-semibold text-[#5c4033]">
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

                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-[#5c4033]">
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
                    Register
                </button>

                <p class="text-center text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                        class="text-blue-600 font-semibold hover:text-blue-700 hover:underline">
                        Login di sini
                    </a>
                </p>
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
