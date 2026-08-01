<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Aplikasi POS</title>
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
            <h2 class="text-2xl font-bold text-center text-blue-600 mb-2">Reset Password</h2>
            <p class="text-xs text-center text-gray-400 mb-6">Enter your email to receive a reset link</p>

            <!-- Pesan Error Validasi -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-xs p-3 rounded-lg mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Status Sukses Kirim Link -->
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-600 text-xs p-3 rounded-lg mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form dihubungkan ke route('password.email') dengan method POST -->
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                
                <!-- Input Email -->
                <div class="mb-6">
                    <div class="flex items-center border-b border-gray-300 py-2 focus-within:border-blue-500">
                        <svg class="w-5 h-5 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" class="w-full border-0 outline-none focus:ring-0 text-gray-700 text-sm bg-transparent" required>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-md">
                    Email Password Reset Link
                </button>
            </form>
        </div>

        <!-- Sisi Kanan (Panel Informasi & Kembali ke Login) -->
        <div class="w-full md:w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 text-white p-8 md:p-12 flex flex-col justify-center items-center text-center">
            <h2 class="text-3xl font-bold mb-2">Remembered?</h2>
            <p class="text-blue-100 text-sm mb-6">Back to your account login page</p>
            <!-- Tombol kembali ke Login -->
            <a href="{{ route('login') }}" class="border border-white text-white px-6 py-2 rounded-full text-sm font-semibold hover:bg-white hover:text-blue-700 transition duration-200">
                Log in
            </a>
        </div>

    </div>

</body>
</html>