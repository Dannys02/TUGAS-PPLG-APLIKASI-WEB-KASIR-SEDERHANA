<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Login | Aplikasi Kasir</title>

  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#f8f5f2] min-h-screen flex items-center justify-center px-4">
  <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">

    <div class="bg-[#6f4e37] py-8 px-6 text-center">
      <h1 class="text-3xl font-bold text-white">Aplikasi Kasir</h1>
      <p class="text-[#f3e9dc] mt-2 text-sm">
        Selamat datang kembali
      </p>
    </div>

    <div class="p-8">
      <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
          <label for="email" class="block mb-2 text-sm font-semibold text-[#5c4033]">
            Email
          </label>
          <input type="email" id="email" name="email" placeholder="Email Anda"
          class="w-full px-4 py-3 rounded-xl border border-[#d6c3b3]
          focus:outline-none focus:ring-2 focus:ring-[#6f4e37]
          bg-[#fffdfb]">

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
          class="w-full px-4 py-3 rounded-xl border border-[#d6c3b3]
          focus:outline-none focus:ring-2 focus:ring-[#6f4e37]
          bg-[#fffdfb]">

          @error('password')
          <p class="error text-red-500 text-sm mt-2 transition-opacity duration-500 opacity-100">
            {{ $message }}
          </p>
          @enderror
        </div>

        <button type="submit"
          class="w-full bg-[#6f4e37] hover:bg-[#5c4033]
          text-white font-semibold py-3 rounded-xl transition duration-300">
          Login
        </button>
        <div>
          <p class="text-sm text-center text-[#5c4033]">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[#6f4e37] font-semibold hover:underline">
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
  </script>

</body>
</html>