<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelatih - Statistik Pemain Bola</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    
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
    <style>
        body {
            background:
                radial-gradient(ellipse at 12% 10%, rgba(96, 165, 250, 0.3), transparent 24rem),
                radial-gradient(ellipse at 90% 85%, rgba(167, 139, 250, 0.24), transparent 26rem),
                linear-gradient(135deg, #EFF6FF, #F8FAFC 50%, #F5F3FF);
        }
        body > div > div > div:nth-child(2) {
            background: linear-gradient(140deg, #FFFFFF, #F8FAFF 55%, #F5F3FF) !important;
            border-color: #D7E3F4 !important;
            box-shadow: 0 24px 60px rgba(51, 65, 110, 0.14);
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
<body class="min-h-screen flex items-center justify-center p-4 bg-pitch-950 font-sans text-slate-200 antialiased">

    <div class="w-full max-w-sm space-y-6">
        
        <!-- Back Link -->
        <div>
            <a href="<?= site_url('/') ?>" class="inline-flex items-center gap-2 text-xs font-semibold text-emerald-300/80 hover:text-white transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Portal Pemain
            </a>
        </div>

        <!-- Clean Form Panel (Emerald Pitch) -->
        <div class="bg-pitch-900 border border-pitch-750 rounded-2xl p-7 shadow-xl space-y-6">
            
            <!-- Header -->
            <div class="text-center space-y-2">
                <div class="w-10 h-10 rounded-xl bg-pitch-850 border border-pitch-700 flex items-center justify-center text-emerald-400 mx-auto">
                    <i class="fa-solid fa-lock text-sm"></i>
                </div>
                <div>
                    <h1 class="font-display font-black text-xl text-white tracking-tight">MASUK PANEL PELATIH</h1>
                    <p class="text-xs text-emerald-200/70 mt-0.5">Khusus pelatih & staf evaluasi performa</p>
                </div>
            </div>

            <!-- Flash Alert -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="p-3 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-medium flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-exclamation text-sm flex-shrink-0"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-medium flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-check text-sm flex-shrink-0"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?= site_url('login') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>

                <div>
                    <label class="block text-[11px] font-mono font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama pengguna</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-400/60">
                            <i class="fa-solid fa-user text-xs"></i>
                        </div>
                        <input type="text" name="username" value="<?= old('username') ?>" required placeholder="Nama pengguna" class="w-full pl-9 pr-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-400 transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-mono font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kata sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-400/60">
                            <i class="fa-solid fa-key text-xs"></i>
                        </div>
                        <input type="password" name="password" required placeholder="Kata sandi" class="w-full pl-9 pr-3.5 py-2.5 bg-pitch-950 border border-pitch-750 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-400 transition-colors">
                    </div>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs uppercase tracking-wider transition-colors flex items-center justify-center gap-2">
                    <span>Masuk ke Dashboard</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </form>

        </div>

    </div>

</body>
</html>
