<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi POS</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Hiasan Background / Bentuk Abstrak -->
    <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
    <div class="absolute -top-10 -right-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>

    <!-- Container Utama -->
    <div class="relative bg-white shadow-2xl rounded-2xl flex flex-col md:flex-row w-full max-w-3xl overflow-hidden m-4">
        
        <!-- Form Sisi Kiri -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Log in</h2>

            <!-- Pesan Error Validasi / Login Gagal -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-xs p-3 rounded-lg mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Status Sukses -->
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-600 text-xs p-3 rounded-lg mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Input Email -->
                <div class="mb-4">
                    <div class="flex items-center border-b border-gray-300 py-2 focus-within:border-blue-500">
                        <svg class="w-5 h-5 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <!-- Tambahan border-0 outline-none focus:ring-0 agar kotak hilang -->
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" class="w-full border-0 outline-none focus:ring-0 text-gray-700 text-sm bg-transparent" required>
                    </div>
                </div>

                <!-- Input Password -->
                <div class="mb-4">
                    <div class="flex items-center border-b border-gray-300 py-2 focus-within:border-blue-500">
                        <svg class="w-5 h-5 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <!-- Tambahan border-0 outline-none focus:ring-0 agar kotak hilang -->
                        <input type="password" name="password" placeholder="Password" class="w-full border-0 outline-none focus:ring-0 text-gray-700 text-sm bg-transparent" required>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6 text-xs text-gray-500">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="hover:underline text-blue-500">Forgot password?</a>
                </div>

                <!-- Tombol Login -->
                <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-md">
                    Log in
                </button>
            </form>
        </div>

        <!-- Sisi Kanan (Welcome Panel) -->
        <div class="w-full md:w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 text-white p-8 md:p-12 flex flex-col justify-center items-center text-center">
            <h2 class="text-3xl font-bold mb-2">Welcome Back!</h2>
            <p class="text-blue-100 text-sm mb-6">Please enter your details</p>
            <p class="text-xs text-blue-200 mb-3">Don't have an account?</p>
            <a href="{{ route('register') }}" class="border border-white text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-white hover:text-blue-700 transition duration-200">
                Sign Up
            </a>
        </div>

    </div>

</body>
</html>