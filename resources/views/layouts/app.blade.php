<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Poliklinik' }}</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f3f4f6; margin: 0; }
        .app-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar Tetap di Kiri */
        .sidebar-fixed { width: 260px; background-color: #1e2d6b; color: white; position: fixed; height: 100vh; z-index: 50; }

        /* Area Konten Utama */
        .main-content { flex: 1; margin-left: 260px; display: flex; flex-direction: column; }

        /* NAVBAR HEADER PUTIH */
        .navbar-top {
            background-color: white;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between; /* Ini yang bikin kiri-kanan */
            padding: 0 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .nav-left-text { font-weight: 700; color: #1e2d6b; font-size: 1.1rem; }
        
        .nav-right-profile { display: flex; align-items: center; gap: 12px; }
        .profile-info { text-align: right; line-height: 1.2; }
        .profile-name { font-weight: 600; font-size: 0.9rem; color: #334155; }
        .profile-role { font-size: 0.75rem; color: #94a3b8; }
        .avatar-circle { width: 40px; height: 40px; background: #2d4499; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }

        .main-scroll { padding: 2rem; flex: 1; }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <!-- SIDEBAR -->
        <div class="sidebar-fixed">
            @include('components.partials.sidebar')
        </div>

        <div class="main-content">
            <!-- NAVBAR ATAS (PUTIH) -->
            <header class="navbar-top">
                <!-- Teks Paling Kiri -->
                <div class="nav-left-text">
                    {{ $title ?? 'Dashboard' }}
                </div>

                <!-- Profil Paling Kanan -->
                <div class="nav-right-profile">
                    <div class="profile-info">
                        <div class="profile-name">Iqbal</div>
                        <div class="profile-role">Admin Poliklinik</div>
                    </div>
                    <div class="avatar-circle">I</div>
                </div>
            </header>

            <!-- ISI KONTEN -->
            <main class="main-scroll">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>