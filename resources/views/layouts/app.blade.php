<!DOCTYPE html>
<html lang="es" class="bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Pagos Mineros') - SCPM SaaS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts: Plus Jakarta Sans, Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Lucide Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: #020617;
            color: #f8fafc;
            min-height: 100vh;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace !important;
            letter-spacing: -0.02em;
        }
        /* Custom scrollbar Cyan Gradient */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #020617;
        }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #06b6d4, #38bdf8);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #0891b2, #0284c7);
        }
        /* Glassmorphism card utilities Premium Cyan High-Contrast */
        .glass-card {
            background: rgba(15, 23, 42, 0.98) !important;
            backdrop-filter: blur(32px) !important;
            -webkit-backdrop-filter: blur(32px) !important;
            border: 1.5px solid rgba(56, 189, 248, 0.45) !important;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.85) !important;
            position: relative;
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-card:hover {
            border-color: rgba(56, 189, 248, 0.65) !important;
            box-shadow: 0 20px 50px rgba(6, 182, 212, 0.28) !important;
        }
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2.5px;
            background: linear-gradient(90deg, #06b6d4, #38bdf8, #6366f1, transparent);
            opacity: 0.95;
            z-index: 10;
        }
        .cyan-glow {
            box-shadow: 0 0 22px rgba(6, 182, 212, 0.3);
        }
        .sky-glow {
            box-shadow: 0 0 22px rgba(56, 189, 248, 0.3);
        }
        
        /* Premium buttons */
        button, .btn, [type="submit"], [type="button"] {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
        }
        
        button[type="submit"]:hover, [type="submit"]:hover {
            box-shadow: 0 0 24px rgba(6, 182, 212, 0.4);
            transform: translateY(-1.5px);
        }

        .btn-vibrant-cyan {
            background: linear-gradient(135deg, #06b6d4 0%, #38bdf8 50%, #0284c7 100%) !important;
            background-size: 200% auto !important;
            color: #020617 !important;
            font-weight: 700 !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.3) !important;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }
        .btn-vibrant-amber {
            background: linear-gradient(135deg, #06b6d4 0%, #0284c7 50%, #6366f1 100%) !important;
            background-size: 200% auto !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(6, 182, 212, 0.3) !important;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }
        .btn-vibrant-success {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 50%, #06b6d4 100%) !important;
            background-size: 200% auto !important;
            color: #020617 !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3) !important;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }
        .btn-vibrant-danger {
            background: linear-gradient(135deg, #ef4444 0%, #f43f5e 50%, #ef4444 100%) !important;
            background-size: 200% auto !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3) !important;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }
        
        .btn-vibrant-cyan:hover, .btn-vibrant-amber:hover, .btn-vibrant-success:hover, .btn-vibrant-danger:hover {
            background-position: right center !important;
            transform: translateY(-2.5px) !important;
        }

        /* Float Screen Toast System */
        .toast-item {
            background: rgba(15, 23, 42, 0.92) !important;
            backdrop-filter: blur(18px) !important;
            -webkit-backdrop-filter: blur(18px) !important;
            border-radius: 16px !important;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.6) !important;
            position: relative;
            overflow: hidden;
            border: 1.5px solid transparent;
            animation: toastSlideIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: all 0.35s ease;
        }
        .toast-success {
            border-color: rgba(6, 182, 212, 0.45) !important;
            box-shadow: 0 0 28px rgba(6, 182, 212, 0.25) !important;
        }
        .toast-danger {
            border-color: rgba(244, 63, 94, 0.45) !important;
            box-shadow: 0 0 28px rgba(244, 63, 94, 0.25) !important;
        }
        @keyframes toastSlideIn {
            from { opacity: 0; transform: translateX(120%) scale(0.9); }
            to { opacity: 1; transform: translateX(0) scale(1); }
        }
        .toast-progress {
            position: absolute; bottom: 0; left: 0; height: 3px; width: 100%; animation: toastTimer linear forwards;
        }
        .toast-success .toast-progress { background: linear-gradient(90deg, #06b6d4, #38bdf8); animation-duration: 4.5s; }
        .toast-danger .toast-progress { background: linear-gradient(90deg, #ef4444, #f43f5e); animation-duration: 5.5s; }
        @keyframes toastTimer { from { width: 100%; } to { width: 0%; } }

        /* Inputs */
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], select, textarea {
            background: rgba(15, 23, 42, 0.65) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(56, 189, 248, 0.18) !important;
            border-radius: 12px !important;
            color: #f8fafc !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, input[type="number"]:focus, input[type="date"]:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: rgba(6, 182, 212, 0.8) !important;
            box-shadow: 0 0 18px rgba(6, 182, 212, 0.35) !important;
            background: rgba(15, 23, 42, 0.85) !important;
        }

        .nav-item { transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1) !important; }
        .nav-item:hover { transform: translateX(6px) !important; color: #38bdf8 !important; }
        .nav-item i { transition: transform 0.35s cubic-bezier(0.25, 0.8, 0.25, 1), color 0.35s ease !important; }
        .nav-item:hover i { transform: scale(1.2) rotate(6deg) !important; color: #06b6d4 !important; filter: drop-shadow(0 0 6px rgba(6, 182, 212, 0.7)) !important; }
        .nav-item.active-nav-item i { color: #38bdf8 !important; filter: drop-shadow(0 0 8px rgba(56, 189, 248, 0.5)) !important; }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased selection:bg-cyan-500 selection:text-slate-950 min-h-screen">
    
    <!-- Root Container: Flex Layout with Natural Scroll -->
    <div class="min-h-screen flex flex-col md:flex-row bg-slate-950 relative" x-data="{ searchModalOpen: false, mobileOpen: false }">
        
        <!-- Futuristic Mining Background Image Overlay -->
        <div class="fixed inset-0 pointer-events-none z-0 bg-cover bg-center bg-no-repeat opacity-15 scale-105 filter blur-[2px]" style="background-image: url('{{ asset('bg.jpg') }}');"></div>
        <div class="fixed inset-0 pointer-events-none z-0 bg-slate-950/90 backdrop-blur-[4px]"></div>
        <canvas id="particle-canvas" class="fixed inset-0 pointer-events-none z-0 opacity-20"></canvas>

        <!-- Desktop Sidebar (Sticky Top 0, Independent Scroll) -->
        <aside style="width: 280px; min-width: 280px; max-width: 280px;" class="no-print hidden md:flex flex-col h-screen sticky top-0 bg-slate-900/95 backdrop-blur-xl border-r border-slate-800/80 z-20 shadow-2xl flex-shrink-0">
            <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                <!-- Brand Header -->
                <div class="flex items-center flex-shrink-0 px-6 space-x-3.5 pb-4 border-b border-slate-800/80">
                    <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 via-sky-500 to-indigo-600 cyan-glow">
                        <i class="fa-solid fa-gem text-slate-950 text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-sky-300 to-indigo-300 uppercase">
                            SCP MINERO
                        </h1>
                        <p class="text-[10px] font-extrabold tracking-widest text-cyan-400 uppercase">SaaS Enterprise ERP</p>
                    </div>
                </div>
                
                <!-- Nav Groups (SaaS Modules) -->
                <nav class="mt-6 flex-1 px-3 space-y-3 relative overflow-y-auto" id="main-nav"
                     x-data="{
                         openGroup: '{{ request()->routeIs('dashboard', 'reportes.*') ? 'reportes' : (request()->routeIs('trabajadores.*', 'contratos.*', 'pagos.*', 'anticipos.*', 'cajas.*') ? 'personal' : (request()->routeIs('ventas-cargas.*', 'egresos.*', 'ingresos.*', 'compradores.*') ? 'mineral' : 'admin')) }}'
                     }">

                    <!-- DASHBOARD GENERAL (ACCESO DIRECTO) -->
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center justify-between px-4 py-3.5 text-xs font-black uppercase tracking-wider rounded-2xl transition-all duration-200 border-2 {{ request()->routeIs('dashboard') ? 'text-cyan-300 bg-gradient-to-r from-cyan-500/20 via-sky-500/10 to-slate-900 border-cyan-400/60 shadow-lg shadow-cyan-500/20' : 'text-slate-200 bg-slate-950/80 border-slate-800 hover:text-cyan-300 hover:bg-slate-900 hover:border-cyan-500/40' }}">
                        <span class="flex items-center gap-3">
                            <i class="fa-solid fa-gauge-high text-cyan-400 text-base group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                            DASHBOARD GENERAL
                        </span>
                        <i class="fa-solid fa-chevron-right text-xs text-cyan-400 opacity-60 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                    </a>

                    <!-- MÓDULO 2: GESTIÓN DE PERSONAL & NÓMINAS -->
                    <div class="rounded-2xl overflow-hidden bg-slate-950/60 border border-slate-800/80">
                        <button @click="openGroup = (openGroup === 'personal' ? '' : 'personal')" 
                                class="w-full flex items-center justify-between px-3.5 py-3 text-xs font-extrabold uppercase tracking-wider text-slate-200 hover:text-rose-400 transition-colors bg-slate-900/80">
                            <span class="flex items-center gap-2.5">
                                <i class="fa-solid fa-users-gear text-rose-400 text-sm"></i>
                                GESTIÓN DE PERSONAL & NÓMINAS
                            </span>
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openGroup === 'personal' }"></i>
                        </button>
                        
                        <div x-show="openGroup === 'personal'" class="p-1.5 space-y-1 bg-slate-950/80 border-t border-slate-800/40">
                            <a href="{{ route('cajas.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ (request()->routeIs('cajas.*') && !request('tipo')) ? 'text-emerald-300 bg-emerald-500/15 border-emerald-500/30 shadow-lg shadow-emerald-500/10' : 'text-slate-300 hover:text-emerald-300 hover:bg-slate-900/90 hover:border-emerald-500/20' }}">
                                <i class="fa-solid fa-building-columns w-5 text-center mr-2 text-sm text-emerald-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Fondo y Recarga Banco
                            </a>
                            <a href="{{ route('pagos.create') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('pagos.*') ? 'text-rose-300 bg-rose-500/15 border-rose-500/30 shadow-lg shadow-rose-500/10' : 'text-slate-300 hover:text-rose-300 hover:bg-slate-900/90 hover:border-rose-500/20' }}">
                                <i class="fa-solid fa-calculator w-5 text-center mr-2 text-sm text-rose-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Liquidación & Planillas de Pago
                            </a>
                            <a href="{{ route('anticipos.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('anticipos.*') ? 'text-purple-300 bg-purple-500/15 border-purple-500/30 shadow-lg shadow-purple-500/10' : 'text-slate-300 hover:text-purple-300 hover:bg-slate-900/90 hover:border-purple-500/20' }}">
                                <i class="fa-solid fa-hand-holding-dollar w-5 text-center mr-2 text-sm text-purple-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Anticipos & Vales Diarios
                            </a>
                            <a href="{{ route('trabajadores.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('trabajadores.*') ? 'text-sky-300 bg-sky-500/15 border-sky-500/30 shadow-lg shadow-sky-500/10' : 'text-slate-300 hover:text-sky-300 hover:bg-slate-900/90 hover:border-sky-500/20' }}">
                                <i class="fa-solid fa-user-group w-5 text-center mr-2 text-sm text-sky-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Registro de Personal
                            </a>
                            <a href="{{ route('contratos.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('contratos.*') ? 'text-indigo-300 bg-indigo-500/15 border-indigo-500/30 shadow-lg shadow-indigo-500/10' : 'text-slate-300 hover:text-indigo-300 hover:bg-slate-900/90 hover:border-indigo-500/20' }}">
                                <i class="fa-solid fa-file-contract w-5 text-center mr-2 text-sm text-indigo-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Registro de Contratos
                            </a>
                            <a href="{{ route('reportes.index', ['tab' => 'trabajador']) }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request('tab') === 'trabajador' ? 'text-cyan-300 bg-cyan-500/15 border-cyan-500/30 shadow-lg shadow-cyan-500/10' : 'text-slate-300 hover:text-cyan-300 hover:bg-slate-900/90 hover:border-cyan-500/20' }}">
                                <i class="fa-solid fa-chart-line w-5 text-center mr-2 text-sm text-cyan-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Reportes de Personal
                            </a>
                        </div>
                    </div>

                    <!-- MÓDULO 3: COMERCIALIZACIÓN Y PRODUCCIÓN DE MINERAL -->
                    <div class="rounded-2xl overflow-hidden bg-slate-950/60 border border-slate-800/80">
                        <button @click="openGroup = (openGroup === 'mineral' ? '' : 'mineral')" 
                                class="w-full flex items-center justify-between px-3.5 py-3 text-xs font-extrabold uppercase tracking-wider text-slate-200 hover:text-emerald-400 transition-colors bg-slate-900/80">
                            <span class="flex items-center gap-2.5">
                                <i class="fa-solid fa-truck-ramp-box text-emerald-400 text-sm"></i>
                                MINERÍA & VOLQUETAS
                            </span>
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openGroup === 'mineral' }"></i>
                        </button>
                        
                        <div x-show="openGroup === 'mineral'" class="p-1.5 space-y-1 bg-slate-950/80 border-t border-slate-800/40">
                            <a href="{{ route('bocaminas.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('bocaminas.*') ? 'text-amber-300 bg-amber-500/15 border-amber-500/30 shadow-lg shadow-amber-500/10' : 'text-slate-300 hover:text-amber-300 hover:bg-slate-900/90 hover:border-amber-500/20' }}">
                                <i class="fa-solid fa-mountain w-5 text-center mr-2 text-sm text-amber-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Registro de Bocaminas
                            </a>
                            <a href="{{ route('produccion.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('produccion.*') ? 'text-cyan-300 bg-cyan-500/15 border-cyan-500/30 shadow-lg shadow-cyan-500/10' : 'text-slate-300 hover:text-cyan-300 hover:bg-slate-900/90 hover:border-cyan-500/20' }}">
                                <i class="fa-solid fa-cubes w-5 text-center mr-2 text-sm text-cyan-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Producción Minera
                            </a>
                            <a href="{{ route('ventas-cargas.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('ventas-cargas.*') ? 'text-emerald-300 bg-emerald-500/15 border-emerald-500/30 shadow-lg shadow-emerald-500/10' : 'text-slate-300 hover:text-emerald-300 hover:bg-slate-900/90 hover:border-emerald-500/20' }}">
                                <i class="fa-solid fa-truck-field w-5 text-center mr-2 text-sm text-emerald-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Ventas (Volquetas/Sacos)
                            </a>
                            <a href="{{ route('egresos.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('egresos.*') ? 'text-rose-300 bg-rose-500/15 border-rose-500/30 shadow-lg shadow-rose-500/10' : 'text-slate-300 hover:text-rose-300 hover:bg-slate-900/90 hover:border-rose-500/20' }}">
                                <i class="fa-solid fa-cart-shopping w-5 text-center mr-2 text-sm text-rose-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Compras Mineral & Gastos
                            </a>
                            <a href="{{ route('compradores.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('compradores.*') ? 'text-amber-300 bg-amber-500/15 border-amber-500/30 shadow-lg shadow-amber-500/10' : 'text-slate-300 hover:text-amber-300 hover:bg-slate-900/90 hover:border-amber-500/20' }}">
                                <i class="fa-solid fa-handshake w-5 text-center mr-2 text-sm text-amber-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Compradores Mineral
                            </a>
                            <a href="{{ route('cajas.index', ['tipo' => 'comercial']) }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ (request()->routeIs('cajas.*') && request('tipo') === 'comercial') ? 'text-cyan-300 bg-cyan-500/15 border-cyan-500/30 shadow-lg shadow-cyan-500/10' : 'text-slate-300 hover:text-cyan-300 hover:bg-slate-900/90 hover:border-cyan-500/20' }}">
                                <i class="fa-solid fa-vault w-5 text-center mr-2 text-sm text-cyan-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Caja Operativa Comercial
                            </a>
                        </div>
                    </div>

                    <!-- MÓDULO 4: ADMINISTRACIÓN Y SOCIOS -->
                    <div class="rounded-2xl overflow-hidden bg-slate-950/60 border border-slate-800/80">
                        <button @click="openGroup = (openGroup === 'admin' ? '' : 'admin')" 
                                class="w-full flex items-center justify-between px-3.5 py-3 text-xs font-extrabold uppercase tracking-wider text-slate-200 hover:text-sky-400 transition-colors bg-slate-900/80">
                            <span class="flex items-center gap-2.5">
                                <i class="fa-solid fa-folder-tree text-sky-400 text-sm"></i>
                                ADMINISTRACIÓN & SOCIOS
                            </span>
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openGroup === 'admin' }"></i>
                        </button>
                        
                        <div x-show="openGroup === 'admin'" class="p-1.5 space-y-1 bg-slate-950/80 border-t border-slate-800/40">
                            <a href="{{ route('socios.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('socios.*') ? 'text-sky-300 bg-sky-500/15 border-sky-500/30 shadow-lg shadow-sky-500/10' : 'text-slate-300 hover:text-sky-300 hover:bg-slate-900/90 hover:border-sky-500/20' }}">
                                <i class="fa-solid fa-id-card w-5 text-center mr-2 text-sm text-sky-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Socios Cooperativistas
                            </a>
                            <a href="{{ route('contabilidad.index') }}" class="group flex items-center px-3 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 border border-transparent {{ request()->routeIs('contabilidad.*') ? 'text-emerald-300 bg-emerald-500/15 border-emerald-500/30 shadow-lg shadow-emerald-500/10' : 'text-slate-300 hover:text-emerald-300 hover:bg-slate-900/90 hover:border-emerald-500/20' }}">
                                <i class="fa-solid fa-book-bookmark w-5 text-center mr-2 text-sm text-emerald-400 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                                Contabilidad General
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Mobile Header Drawer (only visible on mobile screens) -->
        <div class="no-print md:hidden fixed top-0 w-full bg-slate-900/95 backdrop-blur-xl border-b border-slate-800 z-40">
            <div class="flex items-center justify-between h-16 px-4">
                <div class="flex items-center space-x-3">
                    <div class="flex items-center justify-center w-8 h-8 rounded-xl bg-gradient-to-br from-cyan-400 via-sky-500 to-indigo-600 cyan-glow">
                        <i class="fa-solid fa-gem text-slate-950 text-sm"></i>
                    </div>
                    <h1 class="text-base font-extrabold tracking-wider text-cyan-400 uppercase">SCP MINERO</h1>
                </div>
                <button @click="mobileOpen = !mobileOpen" class="text-slate-300 hover:text-cyan-400 focus:outline-none p-2">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
            
            <div x-show="mobileOpen" @click.away="mobileOpen = false" class="px-3 pt-2 pb-4 space-y-2.5 bg-slate-900/95 border-b border-slate-800 max-h-[85vh] overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm font-black text-cyan-400 bg-slate-950 border border-cyan-500/30 rounded-xl flex items-center gap-2">
                    <i class="fa-solid fa-gauge-high"></i> DASHBOARD GENERAL
                </a>
                <div class="border border-slate-800 rounded-xl overflow-hidden">
                    <div class="px-4 py-1.5 bg-slate-950 text-[10px] font-bold text-rose-400 uppercase">Gestión de Personal & Nóminas</div>
                    <a href="{{ route('cajas.index') }}" class="block px-4 py-2 text-sm font-bold text-emerald-400">Fondo y Recarga Banco</a>
                    <a href="{{ route('pagos.create') }}" class="block px-4 py-2 text-sm font-bold text-rose-400">Liquidación y Pago de Personal</a>
                    <a href="{{ route('pagos.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Historial y Planillas Pagadas</a>
                    <a href="{{ route('anticipos.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Anticipos y Adelantos</a>
                    <a href="{{ route('trabajadores.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Registro de Personal</a>
                    <a href="{{ route('contratos.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Registro de Contratos</a>
                    <a href="{{ route('reportes.index', ['tab' => 'trabajador']) }}" class="block px-4 py-2 text-sm font-bold text-cyan-400">Reportes de Personal</a>
                </div>
                <div class="border border-slate-800 rounded-xl overflow-hidden">
                    <div class="px-4 py-1.5 bg-slate-950 text-[10px] font-bold text-emerald-400 uppercase">Mineral & Volquetas</div>
                    <a href="{{ route('ventas-cargas.index') }}" class="block px-4 py-2 text-sm font-bold text-emerald-400">🚛 Ventas (Volquetas/Sacos)</a>
                    <a href="{{ route('egresos.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">📉 Compras Mineral & Gastos</a>
                    <a href="{{ route('compradores.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">🤝 Compradores / Comercializadoras</a>
                    <a href="{{ route('cajas.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">🏦 Caja Operativa Comercial</a>
                </div>
                <div class="border border-slate-800 rounded-xl overflow-hidden">
                    <div class="px-4 py-1.5 bg-slate-950 text-[10px] font-bold text-sky-400 uppercase">Administración & Socios</div>
                    <a href="{{ route('socios.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Socios Cooperativistas</a>
                    <a href="{{ route('bocaminas.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Bocaminas</a>
                    <a href="{{ route('produccion.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Producción Minera</a>
                    <a href="{{ route('prestamos.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Préstamos & Créditos</a>
                    <a href="{{ route('utilidades.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Distribución Utilidades</a>
                    <a href="{{ route('contabilidad.index') }}" class="block px-4 py-2 text-sm font-bold text-slate-200">Contabilidad General</a>
                </div>
                
                <form action="{{ route('logout') }}" method="POST" class="block w-full pt-2">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2.5 rounded-xl text-sm font-bold text-red-400 hover:bg-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Right Content Column (Flex-1, Smooth Document Scroll) -->
        <div class="flex-1 min-w-0 flex flex-col relative z-30">
            
            <!-- Top Navbar Header (Sticky Top 0) -->
            <header class="no-print hidden md:flex items-center justify-between h-20 bg-slate-900/80 backdrop-blur-xl border-b border-slate-800/80 px-8 flex-shrink-0 sticky top-0 z-20">
                <!-- Left: Breadcrumbs -->
                <div class="flex items-center space-x-3 text-sm">
                    <a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-cyan-400 transition-colors flex items-center gap-1.5 font-medium">
                        <i class="fa-solid fa-house text-xs"></i>
                        <span>Inicio</span>
                    </a>
                    <span class="text-slate-600">/</span>
                    <span class="text-cyan-400 font-bold tracking-wide uppercase text-xs bg-cyan-500/10 px-3 py-1 rounded-full border border-cyan-500/20">
                        @yield('title', 'Tablero')
                    </span>
                </div>

                <!-- Center: Quick Search Button -->
                <button @click="searchModalOpen = true" class="flex items-center space-x-3 px-4 py-2 bg-slate-950/60 hover:bg-slate-950 border border-slate-800 hover:border-cyan-500/40 rounded-xl text-slate-400 text-xs transition duration-200 w-72">
                    <i class="fa-solid fa-magnifying-glass text-cyan-400"></i>
                    <span class="flex-1 text-left">Buscar módulos, socios...</span>
                    <kbd class="px-2 py-0.5 text-[10px] font-mono font-semibold text-slate-300 bg-slate-800 rounded border border-slate-700">Ctrl K</kbd>
                </button>

                <!-- Right: Server Status, Clock & Profile Dropdown -->
                <div class="flex items-center space-x-5" x-data="{ profileOpen: false, notifyOpen: false }">
                    <div class="flex items-center space-x-2 bg-slate-950/60 px-3 py-1.5 rounded-full border border-slate-800">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-ping"></span>
                        <span class="text-xs text-slate-300 font-medium">Servidor Activo</span>
                    </div>

                    <span id="realtime-clock" class="text-xs text-slate-300 font-mono bg-slate-950/60 px-3 py-1.5 rounded-full border border-slate-800"></span>

                    <!-- Notifications -->
                    <div class="relative">
                        <button @click="notifyOpen = !notifyOpen" class="w-10 h-10 rounded-xl bg-slate-950/60 border border-slate-800 text-slate-300 hover:text-cyan-400 flex items-center justify-center transition hover:border-cyan-500/30">
                            <i class="fa-solid fa-bell text-sm"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-cyan-400 rounded-full"></span>
                        </button>

                        <div x-show="notifyOpen" @click.away="notifyOpen = false" class="absolute right-0 mt-3 w-80 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-4 z-50">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider">Notificaciones</h4>
                                <span class="text-[10px] bg-cyan-500/20 text-cyan-400 px-2 py-0.5 rounded-full font-mono">Al día</span>
                            </div>
                            <div class="py-3 text-xs text-slate-400 text-center">
                                No hay alertas urgentes pendientes.
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative">
                        <button @click="profileOpen = !profileOpen" class="flex items-center space-x-3 p-1.5 rounded-xl hover:bg-slate-800/60 transition border border-transparent hover:border-slate-800">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cyan-400 to-sky-600 flex items-center justify-center text-slate-950 font-bold text-sm shadow-md">
                                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                            </div>
                            <div class="text-left hidden lg:block">
                                <p class="text-xs font-bold text-slate-200 truncate">{{ Auth::user()->name ?? 'Administrador' }}</p>
                                <p class="text-[10px] text-cyan-400 font-semibold uppercase tracking-wider">SUPER ADMIN</p>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-500"></i>
                        </button>

                        <div x-show="profileOpen" @click.away="profileOpen = false" class="absolute right-0 mt-3 w-56 bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-2 z-50">
                            <div class="px-3 py-2 border-b border-slate-800/80">
                                <p class="text-xs font-bold text-slate-100">{{ Auth::user()->name ?? 'Usuario Administrador' }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@scpminero.bol' }}</p>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="mt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-3 py-2 text-xs font-bold text-rose-400 hover:bg-rose-500/10 rounded-xl transition">
                                    <i class="fa-solid fa-right-from-bracket mr-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Quick Search Modal (Ctrl + K) -->
            <div x-show="searchModalOpen" @keydown.escape.window="searchModalOpen = false" class="fixed inset-0 z-50 flex items-start justify-center pt-24 p-4 bg-slate-950/80 backdrop-blur-md" style="display: none;">
                <div @click.away="searchModalOpen = false" class="w-full max-w-xl bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl overflow-hidden glass-card">
                    <div class="p-4 border-b border-slate-800 flex items-center space-x-3">
                        <i class="fa-solid fa-magnifying-glass text-cyan-400 text-lg"></i>
                        <input type="text" placeholder="Buscar módulo (ej. Ventas, Anticipos, Trabajadores)..." class="w-full bg-transparent text-sm text-slate-100 focus:outline-none border-none">
                        <kbd @click="searchModalOpen = false" class="cursor-pointer text-xs bg-slate-800 text-slate-400 px-2 py-1 rounded">ESC</kbd>
                    </div>
                    <div class="p-3 max-h-80 overflow-y-auto space-y-1 text-xs">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-3 py-1">Accesos Rápidos</p>
                        <a href="{{ route('ventas-cargas.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400">
                            <i class="fa-solid fa-truck-ramp-box w-6"></i> Ventas de Cargas de Mineral
                        </a>
                        <a href="{{ route('pagos.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400">
                            <i class="fa-solid fa-receipt w-6"></i> Pagos y Recibos a Personal
                        </a>
                        <a href="{{ route('cajas.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400">
                            <i class="fa-solid fa-vault w-6"></i> Caja General y Movimientos
                        </a>
                        <a href="{{ route('trabajadores.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400">
                            <i class="fa-solid fa-user-group w-6"></i> Registro de Personal y Trabajadores
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Body Scrollable Content -->
            <main class="flex-1 p-4 md:p-8 pt-20 md:pt-8 bg-slate-950/40 relative z-10">
                
                <!-- Floating Toast Notifications -->
                <div id="toast-container" class="no-print fixed top-6 right-6 z-50 flex flex-col space-y-4 max-w-sm w-full">
                    @if(session('success'))
                        <div class="toast-item toast-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false }, 4500)">
                            <div class="flex items-start p-4">
                                <div class="flex-shrink-0 text-emerald-400">
                                    <i class="fa-solid fa-circle-check text-xl animate-bounce"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-450">Operación Exitosa</p>
                                    <p class="text-sm text-slate-100 font-semibold mt-1">{{ session('success') }}</p>
                                </div>
                                <button @click="show = false" class="ml-4 text-slate-500 hover:text-slate-300 transition duration-150">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div class="toast-progress bg-emerald-500"></div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="toast-item toast-danger" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false }, 5500)">
                            <div class="flex items-start p-4">
                                <div class="flex-shrink-0 text-rose-400">
                                    <i class="fa-solid fa-circle-xmark text-xl animate-bounce"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-rose-400">Atención / Error</p>
                                    <ul class="mt-1 text-sm text-slate-100 list-disc list-inside font-medium space-y-0.5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <button @click="show = false" class="ml-4 text-slate-500 hover:text-slate-300 transition duration-150">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                            <div class="toast-progress bg-rose-500"></div>
                        </div>
                    @endif
                </div>

                <!-- Blade View Slot -->
                @yield('content')
                
            </main>
        </div>
    </div>

    <!-- Live Realtime Clock Script & Particles Animation -->
    <script>
        function updateClock() {
            const clockEl = document.getElementById('realtime-clock');
            if (!clockEl) return;
            const now = new Date();
            const dateStr = now.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
            const timeStr = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            clockEl.textContent = `${dateStr} - ${timeStr}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Canvas Particles Animation
        const canvas = document.getElementById('particle-canvas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let width = canvas.width = window.innerWidth;
            let height = canvas.height = window.innerHeight;
            
            window.addEventListener('resize', () => {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            });
            
            const particles = [];
            const maxParticles = 35;
            
            class Spark {
                constructor() { this.reset(); }
                reset() {
                    this.x = Math.random() * width;
                    this.y = height + Math.random() * 20;
                    this.speedY = Math.random() * 0.8 + 0.2;
                    this.size = Math.random() * 2.0 + 1.0;
                    this.life = 1;
                    this.decay = Math.random() * 0.003 + 0.001;
                    this.opacity = Math.random() * 0.75 + 0.15;
                }
                update() {
                    this.y -= this.speedY;
                    this.life -= this.decay;
                    if (this.life <= 0 || this.y < -15) this.reset();
                }
                draw() {
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(6, 182, 212, ${this.life * this.opacity})`;
                    ctx.shadowBlur = this.life > 0.5 ? this.size * 3 : 0;
                    ctx.shadowColor = 'rgb(6, 182, 212)';
                    ctx.fill();
                    ctx.shadowBlur = 0;
                }
            }
            
            for (let i = 0; i < maxParticles; i++) particles.push(new Spark());
            
            function animate() {
                ctx.clearRect(0, 0, width, height);
                for (let p of particles) { p.update(); p.draw(); }
                requestAnimationFrame(animate);
            }
            animate();
        }

        // Global Lucide & Confirmation Modal Handler
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }

            document.addEventListener('keydown', (e) => {
                if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                    e.preventDefault();
                    const bodyAlpine = document.querySelector('body')._x_dataStack;
                    if (bodyAlpine && bodyAlpine[0]) {
                        bodyAlpine[0].searchModalOpen = !bodyAlpine[0].searchModalOpen;
                    }
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
