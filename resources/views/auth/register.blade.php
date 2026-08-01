<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Aplikasi POS</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Hiasan Background / Bentuk Abstrak -->
    <div class="absolute -bottom-10 -left-10 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
    <div class="absolute -top-10 -right-10 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>

    <!-- Container Utama -->
    <div class="relative bg-white shadow-2xl rounded-2xl flex flex-col md:flex-row w-full max-w-3xl overflow-hidden m-4">
        
        <!-- Form Sisi Kiri (Register Form) -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Sign Up</h2>

            <!-- Pesan Error Validasi -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-xs p-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form dihubungkan ke route('register') dengan method POST -->
            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <!-- Input Nama Lengkap -->
                <div class="mb-4">
                    <div class="flex items-center border-b border-gray-300 py-2 focus-within:border-blue-500">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" class="w-full outline-none text-gray-700 text-sm bg-transparent" required>
                    </div>
                </div>

                <!-- Input Email -->
                <div class="mb-4">
                    <div class="flex items-center border-b border-gray-300 py-2 focus-within:border-blue-500">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" class="w-full outline-none text-gray-700 text-sm bg-transparent" required>
                    </div>
                </div>

                <!-- Input Password -->
                <div class="mb-4">
                    <div class="flex items-center border-b border-gray-300 py-2 focus-within:border-blue-500">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <input type="password" name="password" placeholder="Password" class="w-full outline-none text-gray-700 text-sm bg-transparent" required>
                    </div>
                </div>

                <!-- Input Konfirmasi Password -->
                <div class="mb-6">
                    <div class="flex items-center border-b border-gray-300 py-2 focus-within:border-blue-500">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full outline-none text-gray-700 text-sm bg-transparent" required>
                    </div>
                </div>

                <!-- Tombol Register -->
                <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-md">
                    Register
                </button>
            </form>
        </div>

        <!-- Sisi Kanan (Panel Informasi & Kembali ke Login) -->
        <div class="w-full md:w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 text-white p-8 md:p-12 flex flex-col justify-center items-center text-center">
            <h2 class="text-3xl font-bold mb-2">Welcome!</h2>
            <p class="text-blue-100 text-sm mb-6">Create your account to get started</p>
            <p class="text-xs text-blue-200 mb-3">Already have an account?</p>
            <!-- Tombol kembali ke Login -->
            <a href="{{ route('login') }}" class="border border-white text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-white hover:text-blue-700 transition duration-200">
                Log in
            </a>
        </div>

    </div>

</body>
</html>