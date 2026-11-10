<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Statistik Pemain Bola') ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans + JetBrains Mono for Tabular Stats -->
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
                        turf: {
                            emerald: '#2563EB',
                            mint: '#3B82F6',
                            forest: '#1D4ED8',
                            light: '#EFF6FF',
                        },
                        emerald: {
                            50: '#EFF6FF',
                            100: '#DBEAFE',
                            200: '#BFDBFE',
                            300: '#1D4ED8',
                            400: '#1E40AF',
                            500: '#2563EB',
                            600: '#1D4ED8',
                            700: '#1E40AF',
                            800: '#1E3A8A',
                            900: '#172554',
                            950: '#0F172A',
                        },
                        sky: {
                            50: '#F0F9FF',
                            100: '#E0F2FE',
                            200: '#BAE6FD',
                            300: '#0284C7',
                            400: '#0369A1',
                            500: '#0284C7',
                            600: '#0369A1',
                            700: '#075985',
                            800: '#0C4A6E',
                            900: '#082F49',
                            950: '#082F49',
                        },
                        captain: {
                            gold: '#F59E0B',
                            light: '#FDE68A',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg-canvas: #F8FAFC;
            --surface-panel: #FFFFFF;
            --border-subtle: #CBD5E1;
            --text-primary: #1E3A8A;
            --text-secondary: #3B5B8A;
            --emerald-accent: #2563EB;
        }

        body {
            background:
                radial-gradient(ellipse at 8% 2%, rgba(96, 165, 250, 0.18), transparent 34rem),
                radial-gradient(ellipse at 92% 18%, rgba(167, 139, 250, 0.13), transparent 30rem),
                var(--bg-canvas);
            color: #1E3A8A;
            font-feature-settings: "cv02", "cv03", "cv04", "cv11";
            -webkit-font-smoothing: antialiased;
        }

        body > header {
            background: linear-gradient(105deg, rgba(255, 255, 255, 0.98), rgba(239, 246, 255, 0.97) 65%, rgba(245, 243, 255, 0.97));
            border-color: #D7E3F4 !important;
        }

        main > section:first-child {
            background: linear-gradient(115deg, #EFF6FF 0%, #F5F3FF 52%, #F0F9FF 100%) !important;
            border-color: #D7E3F4 !important;
        }

        .tabular-nums {
            font-variant-numeric: tabular-nums;
        }

        .editorial-panel {
            background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFF 58%, #F5F3FF 100%);
            border: 1px solid #D7E3F4;
            box-shadow: 0 12px 30px rgba(51, 65, 110, 0.07);
        }

        .editorial-panel-hover {
            transition: border-color 0.2s ease, background-color 0.2s ease;
        }
        .editorial-panel-hover:hover {
            border-color: #A5B4FC;
            background: linear-gradient(135deg, #FFFFFF, #EFF6FF);
            box-shadow: 0 14px 32px rgba(51, 65, 110, 0.12);
        }

        .metric-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.22);
            box-shadow: 0 8px 18px rgba(51, 65, 110, 0.06);
        }
        .metric-card::after {
            content: "";
            position: absolute;
            width: 4.5rem;
            height: 4.5rem;
            right: -1.5rem;
            top: -1.7rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.55);
        }
        .metric-card:nth-child(4n + 1) { background: linear-gradient(135deg, #EAF3FF, #F5F8FF) !important; }
        .metric-card:nth-child(4n + 2) { background: linear-gradient(135deg, #E9F8FF, #F2FAFF) !important; }
        .metric-card:nth-child(4n + 3) { background: linear-gradient(135deg, #F0EDFF, #F8F6FF) !important; }
        .metric-card:nth-child(4n + 4) { background: linear-gradient(135deg, #FFF4E5, #FFFAF1) !important; }

        .player-card {
            width: 100%;
            max-width: 230px;
            margin-inline: auto;
            padding: 3px;
            background: linear-gradient(145deg, #FDE68A, #F8FAFC 25%, #818CF8 62%, #F472B6);
            clip-path: polygon(0 0, 38% 0, 44% 1.5%, 50% 0, 56% 1.5%, 62% 0, 100% 0, 100% 94%, 50% 100%, 0 94%);
            transition: opacity 0.2s ease;
        }
        .player-card:hover {
            opacity: 0.96;
        }
        .player-card:nth-child(4n + 2) {
            background: linear-gradient(145deg, #67E8F9, #F8FAFC 28%, #60A5FA 65%, #A78BFA);
        }
        .player-card:nth-child(4n + 3) {
            background: linear-gradient(145deg, #C4B5FD, #F8FAFC 28%, #F472B6 68%, #FBBF24);
        }
        .player-card:nth-child(4n + 4) {
            background: linear-gradient(145deg, #FCD34D, #F8FAFC 28%, #FB7185 68%, #C084FC);
        }
        .player-card-inner {
            overflow: hidden;
            background: #FFFFFF;
            clip-path: polygon(0 0, 38% 0, 44% 1.5%, 50% 0, 56% 1.5%, 62% 0, 100% 0, 100% 94%, 50% 100%, 0 94%);
        }
        .player-card-art {
            height: 13rem;
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, #FB7185 0%, #F472B6 40%, #818CF8 100%);
        }
        .player-card:nth-child(4n + 2) .player-card-art {
            background: linear-gradient(145deg, #22D3EE, #60A5FA 55%, #A78BFA);
        }
        .player-card:nth-child(4n + 3) .player-card-art {
            background: linear-gradient(145deg, #A78BFA, #F472B6 60%, #FB7185);
        }
        .player-card:nth-child(4n + 4) .player-card-art {
            background: linear-gradient(145deg, #FBBF24, #FB7185 58%, #C084FC);
        }
        .player-card-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 18%;
        }
        .player-card-rating {
            position: absolute;
            top: 1.1rem;
            left: 1rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            color: #FFFFFF;
        }
        .player-card-overall {
            font: 800 1.9rem/0.95 "Space Grotesk", sans-serif;
        }
        .player-card-position {
            margin-top: 0.25rem;
            font: 800 1.25rem/1 "JetBrains Mono", monospace;
            text-transform: uppercase;
            text-shadow: 0 2px 6px rgba(15, 23, 42, 0.8), 0 0 2px rgba(15, 23, 42, 0.9);
        }
        .player-card-captain {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.3rem 0.5rem;
            border-radius: 9999px;
            background: #FFFFFF;
            color: #92400E;
            font: 800 0.58rem/1 "JetBrains Mono", monospace;
        }
        .player-card-name {
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: 0;
            padding: 0.4rem 0.65rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #FFFFFF;
            background: rgba(15, 23, 42, 0.84);
        }
        .player-card-name h3 {
            overflow: hidden;
            font: 800 0.98rem/1.1 "Space Grotesk", sans-serif;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #FFFFFF;
        }
        .player-card-shirt {
            flex-shrink: 0;
            font: 700 0.72rem/1 "JetBrains Mono", monospace;
            text-shadow: 0 1px 2px #000, 0 0 8px #22D3EE, 0 0 14px rgba(34, 211, 238, 0.9) !important;
        }
        .player-card-stats {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 0.1rem;
            padding: 0.45rem 0.25rem;
            background: #FDF2F8;
            color: #1E293B;
        }
        .player-card-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1.15;
        }
        .player-card-stat span {
            color: #475569;
            font: 700 0.55rem/1.2 "JetBrains Mono", monospace;
        }
        .player-card-stat strong {
            color: #0F172A;
            font: 800 0.78rem/1.2 "JetBrains Mono", monospace;
        }
        .player-card-footer {
            min-height: 2.7rem;
            padding: 0.2rem 0.7rem 0.65rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #FFFFFF;
        }
        .player-card-feet {
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }
        .player-card .foot-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            font: 700 0.48rem/1 "JetBrains Mono", monospace;
        }
        .player-card .foot-indicator svg {
            width: 0.7rem;
            height: 0.9rem;
        }
        .player-card-ball {
            color: #64748B;
            font-size: 0.95rem;
        }
        .player-card-link {
            color: #1D4ED8;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .bg-pitch-850 {
            background: linear-gradient(135deg, #F0F7FF, #F5F3FF) !important;
        }

        .bg-pitch-800 {
            background: linear-gradient(135deg, #DBEAFE, #EDE9FE) !important;
        }

        body > footer {
            background: linear-gradient(105deg, #EFF6FF, #F5F3FF 60%, #F0F9FF);
            border-color: #D7E3F4 !important;
        }

        .bg-emerald-500.text-white,
        .bg-sky-500.text-white,
        .bg-amber-500.text-white,
        .bg-red-500.text-white {
            color: #FFFFFF !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
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
        .dominant-foot { color: #15803D !important; }
        .muted-foot { color: #94A3B8 !important; }

        /* Print styles */
        @media print {
            header, footer, .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .editorial-panel {
                border: 1px solid #cccccc !important;
                background: #ffffff !important;
                color: #000000 !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col font-sans antialiased text-slate-200">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-pitch-900/95 border-b border-pitch-750/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand / Crest -->
                <div class="flex items-center gap-3">
                    <a href="<?= site_url('/') ?>" class="flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-lg bg-pitch-850 border border-pitch-700 flex items-center justify-center text-emerald-400 group-hover:border-emerald-400 transition-colors">
                            <i class="fa-solid fa-futbol text-sm"></i>
                        </div>
                        <div>
                            <span class="font-display font-bold text-base tracking-tight text-white flex items-center gap-1.5 leading-none">
                                STATISTIK <span class="text-emerald-400">PEMAIN</span>
                            </span>
                            <span class="block text-[10px] text-emerald-300/80 font-mono tracking-wider uppercase mt-0.5">Portal Resmi Performa Tim</span>
                        </div>
                    </a>
                </div>

                <!-- Primary Nav Tabs -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="<?= site_url('/') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold tracking-wide transition-colors <?= current_url() == site_url('/') ? 'bg-pitch-800 text-emerald-300 border border-pitch-700' : 'text-slate-300 hover:text-white hover:bg-pitch-850' ?>">
                        <i class="fa-solid fa-users-line mr-1.5 text-xs text-emerald-400/80"></i> Skuad Tim
                    </a>
                    <a href="<?= site_url('leaderboard') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold tracking-wide transition-colors <?= strpos(current_url(), 'leaderboard') !== false ? 'bg-pitch-800 text-emerald-300 border border-pitch-700' : 'text-slate-300 hover:text-white hover:bg-pitch-850' ?>">
                        <i class="fa-solid fa-chart-simple mr-1.5 text-xs text-emerald-400/80"></i> Papan Peringkat
                    </a>
                    <a href="<?= site_url('compare') ?>" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold tracking-wide transition-colors <?= strpos(current_url(), 'compare') !== false ? 'bg-pitch-800 text-emerald-300 border border-pitch-700' : 'text-slate-300 hover:text-white hover:bg-pitch-850' ?>">
                        <i class="fa-solid fa-code-compare mr-1.5 text-xs text-emerald-400/80"></i> Komparasi Head-to-Head
                    </a>
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-3">
                    <?php if (session()->get('is_admin_logged_in')): ?>
                        <a href="<?= site_url('logout') ?>" class="p-1.5 rounded-lg text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-colors" title="Logout">
                            <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        </a>
                    <?php else: ?>
                        <a href="<?= site_url('login') ?>" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg border border-pitch-750 bg-pitch-850 hover:bg-pitch-800 hover:border-emerald-500/50 text-slate-200 hover:text-white text-xs font-semibold tracking-wide transition-colors">
                            <i class="fa-solid fa-lock text-xs text-emerald-400"></i>
                            <span>Login Pelatih</span>
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Hamburger -->
                    <button id="mobileMenuToggle" class="md:hidden p-2 rounded-lg bg-pitch-850 border border-pitch-750 text-slate-300 hover:text-white focus:outline-none" aria-label="Menu">
                        <i class="fa-solid fa-bars text-sm"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div id="mobileNavMenu" class="hidden md:hidden border-t border-pitch-750 bg-pitch-900 px-4 py-3 space-y-2">
            <a href="<?= site_url('/') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold <?= current_url() == site_url('/') ? 'bg-pitch-800 text-emerald-300' : 'text-slate-300 hover:bg-pitch-850' ?>">
                <i class="fa-solid fa-users-line text-xs"></i> Skuad Tim
            </a>
            <a href="<?= site_url('leaderboard') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold <?= strpos(current_url(), 'leaderboard') !== false ? 'bg-pitch-800 text-emerald-300' : 'text-slate-300 hover:bg-pitch-850' ?>">
                <i class="fa-solid fa-chart-simple text-xs"></i> Papan Peringkat
            </a>
            <a href="<?= site_url('compare') ?>" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold <?= strpos(current_url(), 'compare') !== false ? 'bg-pitch-800 text-emerald-300' : 'text-slate-300 hover:bg-pitch-850' ?>">
                <i class="fa-solid fa-code-compare text-xs"></i> Komparasi Head-to-Head
            </a>
            <?php if (!session()->get('is_admin_logged_in')): ?>
                <div class="pt-2 border-t border-pitch-750">
                    <a href="<?= site_url('login') ?>" class="flex items-center justify-center gap-2 w-full py-2 rounded-lg bg-pitch-850 border border-pitch-750 text-white font-medium text-xs">
                        <i class="fa-solid fa-lock text-emerald-400"></i> Login Pelatih
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Clean Pitch Footer -->
    <footer class="border-t border-pitch-750/80 bg-pitch-900 py-8 text-slate-400 text-xs mt-16 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded bg-pitch-850 border border-pitch-700 flex items-center justify-center text-emerald-400 text-xs">
                        <i class="fa-solid fa-futbol"></i>
                    </div>
                    <div>
                        <span class="font-display font-bold text-white tracking-wide">STATISTIK PEMAIN BOLA</span>
                        <span class="text-emerald-300/70 ml-2">Sistem Evaluasi Sepak Bola Profesional</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-slate-400 font-mono text-[11px]">
                    <span>Kapten: <strong class="text-white">Jose #10</strong></span>
                    <span>•</span>
                    <span>Emerald Pitch Theme</span>
                    <span>•</span>
                    <span>&copy; <?= date('Y') ?></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Navigation Toggle Script -->
    <script>
        document.getElementById('mobileMenuToggle')?.addEventListener('click', function() {
            const menu = document.getElementById('mobileNavMenu');
            menu.classList.toggle('hidden');
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
