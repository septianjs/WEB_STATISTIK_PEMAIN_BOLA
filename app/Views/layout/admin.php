<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Panel Pelatih - Statistik Pemain') ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS with light football palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Space Grotesk"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        pitch: {
                            950: '#F8FAFC',
                            900: '#FFFFFF',
                            850: '#F1F5F9',
                            800: '#E2E8F0',
                            750: '#CBD5E1',
                            700: '#94A3B8',
                            600: '#64748B',
                            500: '#2563EB',
                            400: '#1D4ED8',
                        },
                        emerald: {
                            50: '#EFF6FF', 100: '#DBEAFE', 200: '#BFDBFE',
                            300: '#1D4ED8', 400: '#1E40AF', 500: '#2563EB',
                            600: '#1D4ED8', 700: '#1E40AF', 800: '#1E3A8A',
                            900: '#172554', 950: '#0F172A',
                        },
                        sky: {
                            50: '#F0F9FF', 100: '#E0F2FE', 200: '#BAE6FD',
                            300: '#0284C7', 400: '#0369A1', 500: '#0284C7',
                            600: '#0369A1', 700: '#075985', 800: '#0C4A6E',
                            900: '#082F49', 950: '#082F49',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background:
                radial-gradient(ellipse at 90% 0%, rgba(167, 139, 250, 0.13), transparent 28rem),
                radial-gradient(ellipse at 25% 80%, rgba(96, 165, 250, 0.1), transparent 32rem),
                #F8FAFC;
            color: #1E3A8A;
            -webkit-font-smoothing: antialiased;
        }
        aside {
            background: linear-gradient(180deg, #0F172A 0%, #172554 55%, #111827 100%) !important;
            border-color: #334155 !important;
        }
        aside > div:first-child {
            border-color: #334155 !important;
        }
        aside > div:first-child .bg-pitch-850 {
            background: #1E293B !important;
            border-color: #475569 !important;
        }
        aside > div:first-child .text-white {
            color: #F8FAFC !important;
        }
        aside > div:first-child [class*="text-emerald-"] {
            color: #93C5FD !important;
        }
        aside nav > div {
            color: #93C5FD !important;
        }
        aside nav a,
        aside > div a {
            transition: none !important;
        }
        aside nav a,
        aside nav a span,
        aside nav a i {
            color: #CBD5E1 !important;
        }
        aside nav a i {
            color: #60A5FA !important;
        }
        aside nav a.sidebar-menu-active,
        aside nav a.sidebar-menu-active span,
        aside nav a.sidebar-menu-active i {
            color: #6EE7B7 !important;
        }

        aside nav a[href*="reports/download"] {
            border: 0 !important;
            background: transparent !important;
        }
        aside nav a[href*="stats/create"] {
            color: #6EE7B7 !important;
            background: transparent !important;
        }
        aside nav a[href*="stats/create"].sidebar-menu-active,
        aside nav a[href*="stats/create"].sidebar-menu-active span,
        aside nav a[href*="stats/create"].sidebar-menu-active i {
            color: #6EE7B7 !important;
        }
        aside nav a[href*="players/create"] {
            color: #6EE7B7 !important;
            background: transparent !important;
        }
        aside nav a[href*="players/create"].sidebar-menu-active,
        aside nav a[href*="players/create"].sidebar-menu-active span,
        aside nav a[href*="players/create"].sidebar-menu-active i {
            color: #6EE7B7 !important;
        }
        aside > div:last-child {
            border-color: #334155 !important;
        }
        aside > div:last-child a {
            color: #FDA4AF !important;
            border-color: #7F1D1D !important;
            background: rgba(127, 29, 29, 0.25) !important;
        }
        body > div > header {
            background: linear-gradient(105deg, #FFFFFF, #EFF6FF 70%, #F5F3FF) !important;
            border-color: #D7E3F4 !important;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }
        .admin-panel {
            background: linear-gradient(135deg, #FFFFFF, #F8FAFF 58%, #F5F3FF);
            border: 1px solid #D7E3F4;
            box-shadow: 0 12px 30px rgba(51, 65, 110, 0.07);
        }
        .bg-pitch-850 {
            background: linear-gradient(135deg, #F0F7FF, #F5F3FF) !important;
        }
        .bg-pitch-800 {
            background: linear-gradient(135deg, #DBEAFE, #EDE9FE) !important;
        }
        .bg-emerald-500.text-white,
        .bg-sky-500.text-white,
        .bg-amber-500.text-white,
        .bg-red-500.text-white {
            color: #FFFFFF !important;
        }
        .text-white { color: #1E3A8A !important; }
        [class*="text-slate-200"] { color: #1E3A8A !important; }
        [class*="text-slate-300"] { color: #1E40AF !important; }
        [class*="text-slate-400"] { color: #3B5B8A !important; }
        [class*="text-slate-500"] { color: #64748B !important; }
        [class*="text-emerald-"],
        [class*="text-sky-"],
        [class*="text-cyan-"],
        [class*="text-blue-"] { color: #1E40AF !important; }
        [class*="text-indigo-"],
        [class*="text-violet-"],
        [class*="text-purple-"],
        [class*="text-fuchsia-"] { color: #6D28D9 !important; }
        [class*="text-red-"],
        [class*="text-rose-"],
        [class*="text-pink-"] { color: #B91C1C !important; }
        [class*="text-amber-"],
        [class*="text-yellow-"],
        [class*="text-orange-"] { color: #92400E !important; }
        [class*="text-green-"],
        [class*="text-teal-"],
        [class*="text-lime-"] { color: #166534 !important; }
    </style>
</head>
<body class="min-h-screen flex bg-pitch-950 font-sans antialiased text-slate-200">

    <!-- Sidebar Navigation -->
    <aside class="w-64 bg-pitch-900 border-r border-pitch-750/80 flex flex-col fixed inset-y-0 z-40">
        
        <!-- Header Brand -->
        <div class="h-16 flex items-center px-6 border-b border-pitch-750/80 gap-3">
            <div class="w-8 h-8 rounded-lg bg-pitch-850 border border-pitch-700 flex items-center justify-center text-emerald-400">
                <i class="fa-solid fa-futbol text-xs"></i>
            </div>
            <div>
                <span class="font-display font-bold text-white text-sm tracking-tight block leading-none">PANEL PELATIH</span>
                <span class="text-[10px] text-emerald-400 font-mono tracking-widest uppercase">Sistem Manajemen</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
            <div class="px-3 pt-2 pb-1 text-[10px] font-mono font-bold text-emerald-400/80 uppercase tracking-widest">Menu Utama</div>

            <a href="<?= site_url('admin/dashboard') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold <?= strpos(current_url(), 'admin/dashboard') !== false || current_url() == site_url('admin') ? 'sidebar-menu-active' : 'text-slate-300 hover:text-blue-700' ?>">
                <i class="fa-solid fa-chart-pie w-4 text-center text-emerald-400/80"></i>
                <span>Dashboard</span>
            </a>

            <a href="<?= site_url('admin/players') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold <?= strpos(current_url(), 'admin/players') !== false && strpos(current_url(), 'admin/players/create') === false ? 'sidebar-menu-active' : 'text-slate-300 hover:text-blue-700' ?>">
                <i class="fa-solid fa-users w-4 text-center text-emerald-400/80"></i>
                <span>Data Pemain</span>
            </a>

            <a href="<?= site_url('admin/stats') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold <?= strpos(current_url(), 'admin/stats') !== false && strpos(current_url(), 'admin/stats/create') === false ? 'sidebar-menu-active' : 'text-slate-300 hover:text-blue-700' ?>">
                <i class="fa-solid fa-clipboard-list w-4 text-center text-emerald-400/80"></i>
                <span>Kelola Statistik</span>
            </a>

            <a href="<?= site_url('admin/leaderboard') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold <?= strpos(current_url(), 'admin/leaderboard') !== false ? 'sidebar-menu-active' : 'text-slate-300 hover:text-blue-700' ?>">
                <i class="fa-solid fa-chart-simple w-4 text-center text-emerald-400/80"></i>
                <span>Papan Peringkat</span>
            </a>

            <a href="<?= site_url('admin/compare') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold <?= strpos(current_url(), 'admin/compare') !== false ? 'sidebar-menu-active' : 'text-slate-300 hover:text-blue-700' ?>">
                <i class="fa-solid fa-code-compare w-4 text-center text-emerald-400/80"></i>
                <span>Bandingkan Pemain</span>
            </a>

            <a href="<?= site_url('admin/reports/download') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold">
                <i class="fa-solid fa-file-arrow-down w-4 text-center text-emerald-400/80"></i>
                <span>Download Laporan PDF</span>
            </a>

            <div class="px-3 pt-4 pb-1 text-[10px] font-mono font-bold text-emerald-400/80 uppercase tracking-widest">Aksi Cepat</div>

            <a href="<?= site_url('admin/stats/create') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold <?= strpos(current_url(), 'admin/stats/create') !== false ? 'sidebar-menu-active' : '' ?>">
                <i class="fa-solid fa-plus w-4 text-center"></i>
                <span>Input Nilai Baru</span>
            </a>

            <a href="<?= site_url('admin/players/create') ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold <?= strpos(current_url(), 'admin/players/create') !== false ? 'sidebar-menu-active' : '' ?>">
                <i class="fa-solid fa-user-plus w-4 text-center"></i>
                <span>Tambah Pemain</span>
            </a>

        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-pitch-750/80">
            <a href="<?= site_url('logout') ?>" class="flex items-center justify-center gap-2 w-full py-2 px-3 rounded-lg text-red-400 bg-red-500/10 hover:text-red-700 border border-red-500/30 text-xs font-bold">
                <i class="fa-solid fa-power-off text-xs"></i> Keluar
            </a>
        </div>
    </aside>

    <!-- Main Content Container with left offset for fixed sidebar -->
    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Topbar -->
        <header class="h-16 bg-pitch-900 border-b border-pitch-750/80 sticky top-0 z-30 px-8 flex items-center justify-between">
            <div>
                <h2 class="font-display font-bold text-base text-white"><?= esc($title ?? 'Dashboard Pelatih') ?></h2>
            </div>

            <div class="flex items-center gap-4">
                <a href="<?= site_url('/') ?>" class="inline-flex items-center gap-2 text-xs font-medium px-3 py-1.5 rounded-lg bg-pitch-850 border border-pitch-750 text-slate-300 hover:text-white transition-colors">
                    <i class="fa-solid fa-eye text-emerald-400 text-xs"></i> Halaman Pemain
                </a>
                <div class="h-4 w-px bg-pitch-750"></div>
                <div class="text-right">
                    <span class="text-[10px] font-mono text-emerald-400/80 mt-0.5 block">Pelatih</span>
                </div>
            </div>
        </header>

        <!-- Flash Message Alerts -->
        <div class="px-8 pt-6">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="p-3.5 mb-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center justify-between text-xs font-medium">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-check text-sm"></i>
                        <span><?= session()->getFlashdata('success') ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="p-3.5 mb-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 flex items-center justify-between text-xs font-medium">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid fa-circle-exclamation text-sm"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Content Area -->
        <main class="p-8 flex-1">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Footer -->
        <footer class="px-8 py-4 border-t border-pitch-800 text-[11px] text-slate-500 flex items-center justify-between font-mono">
            <span>&copy; <?= date('Y') ?> Statistik Pemain Bola</span>
        </footer>

    </div>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
