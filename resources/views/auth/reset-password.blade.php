<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Aplikasi POS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen">
    <div class="bg-gray-800 p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-700">
        <h2 class="text-2xl font-bold text-white text-center mb-6">Reset Password Baru</h2>

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500 text-red-400 p-3 rounded-lg mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class=
        "space-y-4">
            @csrf
            <!-- Token tersembunyi dari URL -->
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ request()->email }}" required 
                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Password Baru</label>
                <input type="password" name="password" required 
                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-gray-300 text-sm font-medium mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required 
                    class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition shadow">
                Ubah Password
            </button>
        </form>
    </div>
</body>
</html>