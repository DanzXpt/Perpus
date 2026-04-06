<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PerpusID</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased">

    <div class="flex min-h-screen">
        @include('layouts.sidebar')

        <main class="flex-1 ml-64 p-10">
            <header class="flex justify-between items-center mb-10">
                <div class="italic">
                    <h1 class="text-3xl font-semibold text-slate-800 tracking-tighter">
                        Selamat Datang,
                    </h1>
                    <span class="text-indigo-600 text-1xl font-semibold">{{ auth()->user()->name }}!</span>
                </div>
                <div class="bg-amber-200 px-6 py-3 rounded-xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <i class="fa-regular fa-calendar text-indigo-500"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </div>
            </header>

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", showConfirmButton: false, timer: 2000, customClass: { popup: 'rounded-[2rem]' } });
        @endif
    </script>
    @stack('scripts')
</body>

</html>