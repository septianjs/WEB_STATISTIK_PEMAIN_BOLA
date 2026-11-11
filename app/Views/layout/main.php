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

        /* Base / Standard Card (0 - 60) */
        .player-card {
            width: 100%;
            max-width: 230px;
            margin-inline: auto;
            padding: 3px;
            background: linear-gradient(145deg, #FDE68A, #F8FAFC 25%, #818CF8 62%, #F472B6);
            clip-path: polygon(0 0, 38% 0, 44% 1.5%, 50% 0, 56% 1.5%, 62% 0, 100% 0, 100% 94%, 50% 100%, 0 94%);
            transition: transform 0.25s ease, filter 0.25s ease, opacity 0.2s ease;
        }
        .player-card:hover {
            opacity: 0.98;
            transform: translateY(-2px);
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

        /* --- TIER 2: PRO (61 - 79) - Border Tebal, Lekukan Aerodinamis, Sapphire Cyber --- */
        .player-card.card-tier-pro {
            padding: 6px;
            background: linear-gradient(135deg, #38BDF8 0%, #E0F2FE 20%, #0284C7 45%, #0369A1 70%, #BAE6FD 85%, #0284C7 100%) !important;
            clip-path: polygon(
                14px 0,
                calc(50% - 24px) 0,
                calc(50% - 16px) 5px,
                calc(50% + 16px) 5px,
                calc(50% + 24px) 0,
                calc(100% - 14px) 0,
                100% 14px,
                100% calc(46% - 16px),
                calc(100% - 10px) 46%,
                100% calc(46% + 16px),
                100% calc(100% - 28px),
                calc(100% - 20px) calc(100% - 10px),
                50% 100%,
                20px calc(100% - 10px),
                0 calc(100% - 28px),
                0 calc(46% + 16px),
                10px 46%,
                0 calc(46% - 16px),
                0 14px
            ) !important;
            filter: drop-shadow(0 4px 14px rgba(2, 132, 199, 0.45));
        }
        .player-card.card-tier-pro:hover {
            transform: translateY(-4px);
            filter: drop-shadow(0 8px 24px rgba(2, 132, 199, 0.65));
        }
        .player-card.card-tier-pro .player-card-inner {
            clip-path: polygon(
                14px 0,
                calc(50% - 24px) 0,
                calc(50% - 16px) 5px,
                calc(50% + 16px) 5px,
                calc(50% + 24px) 0,
                calc(100% - 14px) 0,
                100% 14px,
                100% calc(46% - 16px),
                calc(100% - 10px) 46%,
                100% calc(46% + 16px),
                100% calc(100% - 28px),
                calc(100% - 20px) calc(100% - 10px),
                50% 100%,
                20px calc(100% - 10px),
                0 calc(100% - 28px),
                0 calc(46% + 16px),
                10px 46%,
                0 calc(46% - 16px),
                0 14px
            ) !important;
        }
        .player-card.card-tier-pro .player-card-art {
            background: linear-gradient(155deg, #082F49 0%, #0369A1 50%, #0284C7 100%) !important;
        }
        .player-card.card-tier-pro .player-card-stats {
            background: #F0F9FF !important;
        }
        .player-card.card-tier-pro .player-card-footer {
            background: #E0F2FE !important;
            border-top: 1px solid #BAE6FD;
        }
        .player-card.card-tier-pro .player-card-overall {
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.9), 0 0 12px rgba(56, 189, 248, 0.65) !important;
        }

        /* --- TIER 3: ELITE (80 - 100) - Bingkai Mahkota Apex Champion, Emas Mewah, Animasi Aura Tipis --- */
        @keyframes cardEliteAuraPulse {
            0%, 100% {
                filter: drop-shadow(0 0 10px rgba(250, 204, 21, 0.6)) drop-shadow(0 0 24px rgba(16, 185, 129, 0.35));
            }
            50% {
                filter: drop-shadow(0 0 18px rgba(251, 191, 36, 0.9)) drop-shadow(0 0 38px rgba(16, 185, 129, 0.6));
            }
        }
        @keyframes eliteShimmerSweep {
            0% { transform: translateX(-100%) rotate(25deg); }
            100% { transform: translateX(200%) rotate(25deg); }
        }

        .player-card.card-tier-elite {
            padding: 7px;
            background: linear-gradient(135deg, #FDE047 0%, #D97706 18%, #FFFBEB 38%, #F59E0B 60%, #78350F 80%, #FBBF24 100%) !important;
            clip-path: polygon(
                18px 0,
                calc(50% - 32px) 0,
                calc(50% - 20px) 7px,
                50% 0,
                calc(50% + 20px) 7px,
                calc(50% + 32px) 0,
                calc(100% - 18px) 0,
                100% 18px,
                100% 28%,
                calc(100% - 7px) 33%,
                100% 38%,
                100% 64%,
                calc(100% - 11px) 70%,
                100% 76%,
                100% calc(100% - 32px),
                calc(100% - 18px) calc(100% - 18px),
                50% 100%,
                18px calc(100% - 18px),
                0 calc(100% - 32px),
                0 76%,
                11px 70%,
                0 64%,
                0 38%,
                7px 33%,
                0 28%,
                0 18px
            ) !important;
            animation: cardEliteAuraPulse 3.5s ease-in-out infinite;
        }
        .player-card.card-tier-elite:hover {
            transform: translateY(-5px) scale(1.02);
            filter: drop-shadow(0 0 24px rgba(251, 191, 36, 0.95)) drop-shadow(0 0 45px rgba(16, 185, 129, 0.8)) !important;
        }
        .player-card.card-tier-elite .player-card-inner {
            clip-path: polygon(
                18px 0,
                calc(50% - 32px) 0,
                calc(50% - 20px) 7px,
                50% 0,
                calc(50% + 20px) 7px,
                calc(50% + 32px) 0,
                calc(100% - 18px) 0,
                100% 18px,
                100% 28%,
                calc(100% - 7px) 33%,
                100% 38%,
                100% 64%,
                calc(100% - 11px) 70%,
                100% 76%,
                100% calc(100% - 32px),
                calc(100% - 18px) calc(100% - 18px),
                50% 100%,
                18px calc(100% - 18px),
                0 calc(100% - 32px),
                0 76%,
                11px 70%,
                0 64%,
                0 38%,
                7px 33%,
                0 28%,
                0 18px
            ) !important;
        }
        .player-card.card-tier-elite .player-card-art {
            background: linear-gradient(155deg, #0A0F0D 0%, #064E3B 48%, #047857 80%, #B45309 100%) !important;
            position: relative;
        }
        .player-card.card-tier-elite .player-card-art::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(105deg, transparent 40%, rgba(254, 240, 138, 0.22) 50%, transparent 60%);
            animation: eliteShimmerSweep 5s ease-in-out infinite;
            pointer-events: none;
        }
        .player-card.card-tier-elite .player-card-stats {
            background: #FEFCE8 !important;
            color: #713F12 !important;
        }
        .player-card.card-tier-elite .player-card-stat span {
            color: #854D0E !important;
        }
        .player-card.card-tier-elite .player-card-stat strong {
            color: #451A03 !important;
        }
        .player-card.card-tier-elite .player-card-footer {
            background: #FEF9C3 !important;
            border-top: 1px solid #FDE047;
        }
        .player-card.card-tier-elite .player-card-overall {
            color: #FEF08A !important;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.95), 0 0 14px rgba(234, 179, 8, 0.8) !important;
        }

        /* --- TIER 4: LEGENDARY (90 - 100) - Bingkai Mahkota Prismatik, Holographic Border, Multi-Aura --- */
        @keyframes legendaryHoloBorder {
            0%   { background-position: 0% 50%;   filter: drop-shadow(0 0 14px rgba(255, 215, 0, 0.75)) drop-shadow(0 0 30px rgba(16, 185, 129, 0.45)); }
            25%  { background-position: 50% 100%; filter: drop-shadow(0 0 18px rgba(192, 132, 252, 0.75)) drop-shadow(0 0 36px rgba(56, 189, 248, 0.55)); }
            50%  { background-position: 100% 0%;  filter: drop-shadow(0 0 20px rgba(251, 113, 133, 0.75)) drop-shadow(0 0 38px rgba(250, 204, 21, 0.55)); }
            75%  { background-position: 50% 0%;   filter: drop-shadow(0 0 18px rgba(56, 189, 248, 0.75)) drop-shadow(0 0 34px rgba(16, 185, 129, 0.55)); }
            100% { background-position: 0% 50%;   filter: drop-shadow(0 0 14px rgba(255, 215, 0, 0.75)) drop-shadow(0 0 30px rgba(16, 185, 129, 0.45)); }
        }
        @keyframes legendaryShimmerSweep {
            0%   { transform: translateX(-150%) rotate(18deg); opacity: 0; }
            15%  { opacity: 1; }
            85%  { opacity: 1; }
            100% { transform: translateX(250%) rotate(18deg); opacity: 0; }
        }
        @keyframes legendaryInnerGlow {
            0%, 100% { box-shadow: inset 0 0 20px rgba(255, 215, 0, 0.08), inset 0 0 40px rgba(192, 132, 252, 0.05); }
            50%      { box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.14), inset 0 0 60px rgba(56, 189, 248, 0.08); }
        }

        .player-card.card-tier-legendary {
            padding: 8px;
            background: linear-gradient(270deg,
                #FFD700, #FF6B6B, #C084FC, #38BDF8, #10B981, #FBBF24, #FFD700
            ) !important;
            background-size: 400% 400% !important;
            clip-path: polygon(
                /* Crown — 5 peaks */
                0 14px,
                6% 0,
                12% 9px,
                28% 0,
                38% 6px,
                50% 0,
                62% 6px,
                72% 0,
                88% 9px,
                94% 0,
                100% 14px,
                /* Right blade notches */
                100% 26%,
                calc(100% - 5px) 30%,
                100% 34%,
                100% 54%,
                calc(100% - 8px) 59%,
                100% 64%,
                100% 78%,
                calc(100% - 5px) 82%,
                100% 86%,
                /* Bottom deep chevron */
                100% calc(100% - 28px),
                calc(100% - 22px) calc(100% - 10px),
                50% 100%,
                22px calc(100% - 10px),
                0 calc(100% - 28px),
                /* Left blade notches */
                0 86%,
                5px 82%,
                0 78%,
                0 64%,
                8px 59%,
                0 54%,
                0 34%,
                5px 30%,
                0 26%
            ) !important;
            animation: legendaryHoloBorder 4s ease infinite;
        }
        .player-card.card-tier-legendary:hover {
            transform: translateY(-6px) scale(1.03);
            filter: drop-shadow(0 0 28px rgba(255, 215, 0, 0.95)) drop-shadow(0 0 50px rgba(192, 132, 252, 0.7)) drop-shadow(0 0 70px rgba(16, 185, 129, 0.5)) !important;
        }
        .player-card.card-tier-legendary .player-card-inner {
            background: #030712 !important;
            clip-path: polygon(
                0 14px,
                6% 0,
                12% 9px,
                28% 0,
                38% 6px,
                50% 0,
                62% 6px,
                72% 0,
                88% 9px,
                94% 0,
                100% 14px,
                100% 26%,
                calc(100% - 5px) 30%,
                100% 34%,
                100% 54%,
                calc(100% - 8px) 59%,
                100% 64%,
                100% 78%,
                calc(100% - 5px) 82%,
                100% 86%,
                100% calc(100% - 28px),
                calc(100% - 22px) calc(100% - 10px),
                50% 100%,
                22px calc(100% - 10px),
                0 calc(100% - 28px),
                0 86%,
                5px 82%,
                0 78%,
                0 64%,
                8px 59%,
                0 54%,
                0 34%,
                5px 30%,
                0 26%
            ) !important;
            animation: legendaryInnerGlow 4s ease-in-out infinite;
        }
        .player-card.card-tier-legendary .player-card-art {
            background: linear-gradient(155deg, #030712 0%, #1A0A2E 28%, #0C1A30 50%, #064E3B 78%, #422006 100%) !important;
            position: relative;
        }
        .player-card.card-tier-legendary .player-card-art::after {
            content: '';
            position: absolute;
            top: -60%;
            left: -60%;
            width: 220%;
            height: 220%;
            background: linear-gradient(
                105deg,
                transparent 30%,
                rgba(255, 215, 0, 0.18) 42%,
                rgba(192, 132, 252, 0.12) 48%,
                rgba(56, 189, 248, 0.15) 52%,
                rgba(255, 215, 0, 0.10) 58%,
                transparent 70%
            );
            animation: legendaryShimmerSweep 3.5s ease-in-out infinite;
            pointer-events: none;
        }
        .player-card.card-tier-legendary .player-card-stats {
            background: linear-gradient(135deg, #0C0A09, #1C1917) !important;
        }
        .player-card.card-tier-legendary .player-card-stat span {
            color: #A8A29E !important;
        }
        .player-card.card-tier-legendary .player-card-stat strong {
            color: #FDE68A !important;
        }
        .player-card.card-tier-legendary .player-card-footer {
            background: linear-gradient(135deg, #0C0A09, #1C1917) !important;
            border-top: 1px solid rgba(255, 215, 0, 0.2);
        }
        .player-card.card-tier-legendary .player-card-link {
            color: #FDE68A !important;
        }
        .player-card.card-tier-legendary .player-card-ball {
            color: #A8A29E !important;
        }
        .player-card.card-tier-legendary .foot-indicator {
            color: #A8A29E !important;
        }
        .player-card.card-tier-legendary .dominant-foot {
            color: #FDE68A !important;
        }
        .player-card.card-tier-legendary .player-card-overall {
            color: #FFFFFF !important;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.9), 0 0 20px rgba(192, 132, 252, 0.6), 0 2px 8px rgba(0, 0, 0, 0.95) !important;
        }
        .player-card.card-tier-legendary .player-card-position {
            text-shadow: 0 0 8px rgba(56, 189, 248, 0.7), 0 2px 6px rgba(0, 0, 0, 0.9) !important;
        }
        .player-card.card-tier-legendary .player-card-name {
            background: rgba(3, 7, 18, 0.92) !important;
            border-top: 1px solid rgba(255, 215, 0, 0.15);
        }
        .player-card.card-tier-legendary .player-card-shirt {
            text-shadow: 0 1px 2px #000, 0 0 8px #FFD700, 0 0 14px rgba(255, 215, 0, 0.7) !important;
        }

        /* Tier Badges */
        .player-card-badges {
            position: absolute;
            top: 0.9rem;
            right: 0.9rem;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.3rem;
            z-index: 10;
        }
        .player-card-badges .player-card-captain {
            position: static;
        }
        .player-card-tier-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font: 800 0.54rem/1 "JetBrains Mono", monospace;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .tier-badge-pro {
            background: #0284C7;
            color: #FFFFFF;
            border: 1px solid #7DD3FC;
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.5);
        }
        .tier-badge-elite {
            background: linear-gradient(135deg, #F59E0B, #B45309);
            color: #FFFFFF;
            border: 1px solid #FDE68A;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.6);
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
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.7));
        }
        .player-card-overall {
            font: 800 1.9rem/0.95 "Space Grotesk", sans-serif;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.9), 0 1px 3px rgba(15, 23, 42, 0.95);
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
                        <i class="fa-solid fa-code-compare mr-1.5 text-xs text-emerald-400/80"></i> Bandingkan Pemain
                    </a>
                </nav>

                <!-- Right Side Actions -->
                <div class="flex items-center gap-3">
                    <?php if (session()->get('is_admin_logged_in')): ?>
                        <a href="<?= site_url('admin/dashboard') ?>" class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-pitch-750 bg-pitch-850 hover:bg-pitch-800 text-emerald-300 text-xs font-semibold tracking-wide transition-colors">
                            <i class="fa-solid fa-gauge text-xs text-emerald-400"></i>
                            <span>Panel Pelatih</span>
                        </a>
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
                <i class="fa-solid fa-code-compare text-xs"></i> Bandingkan Pemain
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
    <footer class="border-t border-pitch-750/80 bg-pitch-900 py-6 text-slate-400 text-xs mt-16 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded bg-pitch-850 border border-pitch-700 flex items-center justify-center text-emerald-400 text-xs">
                        <i class="fa-solid fa-futbol"></i>
                    </div>
                    <span class="font-display font-bold text-white tracking-wide">STATISTIK PEMAIN BOLA</span>
                </div>

                <div class="text-slate-400 font-mono text-[11px]">
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
