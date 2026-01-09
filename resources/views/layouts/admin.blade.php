<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'House Team') | Backoffice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Montserrat', 'sans-serif'] },
                    colors: {
                        'ht-navy': '#0f172a',
                        'ht-dark': '#020617',
                        'ht-blue': '#3b82f6',
                        'ht-accent': '#6366f1',
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'glow': '0 4px 20px 0px rgba(59, 130, 246, 0.15)',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-600 antialiased selection:bg-ht-blue selection:text-white">

    <div class="flex h-screen overflow-hidden bg-slate-50" x-data="{ sidebarOpen: false }">
        
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-ht-navy text-white transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-2xl"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <div class="h-24 flex items-center justify-center border-b border-white/5 bg-ht-dark/50">
                <div class="text-center">
                    <h1 class="font-black text-2xl tracking-tighter text-white">HOUSE <span class="text-ht-blue">TEAM</span></h1>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400 font-medium">Backoffice</p>
                </div>
            </div>
            
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto scrollbar-hide">
                <p class="px-4 text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-4">Principal</p>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-ht-blue to-ht-accent text-white shadow-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} rounded-xl text-sm font-bold transition-all group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-white/90' : 'group-hover:text-ht-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.properties.*') ? 'bg-gradient-to-r from-ht-blue to-ht-accent text-white shadow-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} rounded-xl text-sm font-semibold transition-all group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.properties.*') ? 'text-white/90' : 'group-hover:text-ht-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Meus Imóveis</span>
                </a>

                <a href="{{ route('admin.consultants.index') }}" class="flex items-center gap-3 px-4 py-3.5 {{ request()->routeIs('admin.consultants.*') ? 'bg-gradient-to-r from-ht-blue to-ht-accent text-white shadow-glow' : 'text-slate-400 hover:bg-white/5 hover:text-white' }} rounded-xl text-sm font-semibold transition-all group">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.consultants.*') ? 'text-white/90' : 'group-hover:text-ht-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Equipe</span>
                </a>

                <p class="px-4 text-[10px] uppercase tracking-wider text-slate-500 font-bold mt-8 mb-4">Atalhos</p>

                <a href="{{ route('admin.properties.create') }}" class="flex items-center gap-3 px-4 py-3.5 text-slate-400 hover:bg-white/5 hover:text-white rounded-xl text-sm font-semibold transition-all group">
                    <svg class="w-5 h-5 group-hover:text-green-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Novo Imóvel</span>
                </a>

                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 text-slate-400 hover:bg-white/5 hover:text-white rounded-xl text-sm font-semibold transition-all group">
                    <svg class="w-5 h-5 group-hover:text-ht-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Ver Site Online</span>
                </a>
            </nav>

            <div class="p-4 border-t border-white/5 bg-ht-dark/30">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-ht-blue to-purple-500 flex items-center justify-center text-white font-bold text-xs shadow-lg">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-white leading-none">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">Administrador</p>
                    </div>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-red-400 hover:text-white hover:bg-red-500/10 border border-red-500/20 hover:border-red-500 rounded-lg transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sair
                    </button>
                </form>
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-ht-navy/80 z-40 lg:hidden backdrop-blur-sm"></div>

        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 z-10 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-ht-navy transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="text-xl font-bold text-ht-navy">@yield('header_title', 'Visão Geral')</h2>
                        <p class="text-xs text-slate-400 hidden sm:block">{{ \Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sistema Online</span>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8 bg-slate-50/50">
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 text-emerald-700 border border-emerald-200 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-6 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>