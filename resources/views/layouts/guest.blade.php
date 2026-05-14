<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'Poliklinik' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous" />

    @vite(['resources/js/app.js', 'resources/css/app.css'])

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .brand-serif {
            font-family: 'Instrument Serif', serif;
        }
    </style>
</head>

<body style="min-height:100vh; background:linear-gradient(135deg,#1e2d6b 0%,#2d4499 60%,#1a2d7a 100%); display:flex; align-items:center; justify-content:center; padding:24px;">

    <!-- Pembungkus Form Login (Card Putih) -->
    <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-white shadow-2xl overflow-hidden sm:rounded-[32px]">
        
        <!-- Logo atau Judul -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-[#1e2d6b] brand-serif">Poliklinik</h1>
            <p class="text-slate-400 text-sm mt-2">Selamat datang kembali, silakan login.</p>
        </div>

        <!-- Isi Form Login (Slot) -->
        {{ $slot }}

    </div>

    @stack('scripts')
</body>
</html>