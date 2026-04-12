<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - E-Perpus Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl flex flex-row-reverse overflow-hidden max-w-4xl w-full">
            
            <div class="hidden md:flex md:w-1/2 bg-blue-700 flex-col justify-center items-center p-12 text-center text-white relative overflow-hidden">
                <svg class="w-24 h-24 mb-6 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h2 class="text-3xl font-bold mb-2 z-10">BERGABUNG SEKARANG</h2>
                <p class="text-blue-200 z-10">Akses ribuan koleksi buku digital secara gratis.</p>
                
                <div class="absolute -bottom-20 -right-20 w-64 h-64 border-4 border-blue-500 rounded-full opacity-20"></div>
                <div class="absolute -top-20 -left-20 w-64 h-64 border-4 border-blue-500 rounded-full opacity-20"></div>
            </div>

            <div class="w-full md:w-1/2 p-8 md:p-12">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Daftar Akun E-Perpus Baru</h3>

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul class="list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                            placeholder="Masukkan nama lengkap Anda">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                            placeholder="Masukkan email aktif">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                            placeholder="Buat kata sandi">
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                            placeholder="Ulangi kata sandi">
                    </div>
                    
                    <div class="flex items-start mb-6">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">Saya setuju dengan <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a> E-Perpus</label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300 shadow-md">
                        DAFTAR SEKARANG
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 underline">Masuk di sini</a>
                </p>
                
                <div class="mt-8 pt-4 border-t border-gray-100 text-center">
                    <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Sistem Informasi Perpus Digital.</p>
                    <p class="text-xs text-gray-400 mt-1">Developed by Ahdan Muzaki</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>