<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | House Team</title>
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
        
        {{-- SIDEBAR --}}
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-ht-navy text-white transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-auto flex flex-col shadow-2xl"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            {{-- Logo Area --}}
            <div class="h-24 flex items-center justify-center border-b border-white/5 bg-ht-dark/50">
                <div class="text-center">
                    <h1 class="font-black text-2xl tracking-tighter text-white">HOUSE <span class="text-ht-blue">TEAM</span></h1>
                    <p class="text-[10px] uppercase tracking-[0.3em] text-slate-400 font-medium">Backoffice</p>
                </div>
            </div>
            
            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto scrollbar-hide">
                <p class="px-4 text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-4">Principal</p>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3.5 bg-gradient-to-r from-ht-blue to-ht-accent text-white rounded-xl text-sm font-bold shadow-glow transition-all group">
                    <svg class="w-5 h-5 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-4 py-3.5 text-slate-400 hover:bg-white/5 hover:text-white rounded-xl text-sm font-semibold transition-all group">
                    <svg class="w-5 h-5 group-hover:text-ht-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Meus Imóveis</span>
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

            {{-- User Profile Snippet --}}
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

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 bg-ht-navy/80 z-40 lg:hidden backdrop-blur-sm"></div>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            
            {{-- Top Header --}}
            <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 z-10 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-ht-navy transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="text-xl font-bold text-ht-navy">Visão Geral</h2>
                        <p class="text-xs text-slate-400 hidden sm:block">{{ \Carbon\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sistema Online</span>
                </div>
            </header>

            {{-- Content Scrollable --}}
            <div class="flex-1 overflow-y-auto p-8 bg-slate-50/50">
                
                {{-- Stats Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    
                    {{-- Card 1 --}}
                    <div class="bg-white p-6 rounded-2xl shadow-soft border border-slate-100 hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Total Imóveis</p>
                                <h3 class="text-4xl font-black text-ht-navy">{{ \App\Models\Property::count() }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-ht-blue group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-2 text-xs font-medium text-emerald-600 bg-emerald-50 w-fit px-2 py-1 rounded-lg">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            Portfólio Ativo
                        </div>
                        {{-- Decorative Blob --}}
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>

                    {{-- Card 2 --}}
                    <div class="bg-white p-6 rounded-2xl shadow-soft border border-slate-100 hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Venda</p>
                                <h3 class="text-4xl font-black text-ht-navy">{{ \App\Models\Property::where('status', 'Venda')->count() }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-6 overflow-hidden">
                            <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 70%"></div>
                        </div>
                    </div>

                    {{-- Card 3 --}}
                    <div class="bg-white p-6 rounded-2xl shadow-soft border border-slate-100 hover:-translate-y-1 transition-transform duration-300 relative overflow-hidden group">
                        <div class="flex justify-between items-start relative z-10">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Arrendamento</p>
                                <h3 class="text-4xl font-black text-ht-navy">{{ \App\Models\Property::where('status', 'Arrendamento')->count() }}</h3>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-6 overflow-hidden">
                            <div class="bg-purple-500 h-1.5 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>

                </div>

                {{-- Recent Table --}}
                <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h3 class="font-bold text-lg text-ht-navy">Últimas Adições</h3>
                            <p class="text-xs text-slate-400 mt-1">Imóveis adicionados recentemente ao sistema.</p>
                        </div>
                        <a href="{{ route('admin.properties.index') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-ht-blue hover:text-ht-navy transition-colors">
                            Ver Todos
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/80 text-[10px] uppercase text-slate-400 font-bold tracking-wider">
                                <tr>
                                    <th class="px-8 py-4 rounded-tl-lg">Imóvel</th>
                                    <th class="px-8 py-4">Valor</th>
                                    <th class="px-8 py-4">Zona</th>
                                    <th class="px-8 py-4">Estado</th>
                                    <th class="px-8 py-4 rounded-tr-lg text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach(\App\Models\Property::latest()->take(5)->get() as $property)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-xl bg-slate-200 overflow-hidden flex-shrink-0 relative shadow-sm border border-slate-200">
                                                @if($property->cover_image)
                                                    <img src="{{ asset('storage/'.$property->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-400 text-[10px]">Sem IMG</div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-ht-navy group-hover:text-ht-blue transition-colors text-sm line-clamp-1 max-w-[200px]">{{ $property->title }}</p>
                                                <p class="text-xs text-slate-400 mt-0.5">{{ $property->type }} &bull; {{ $property->bedrooms }} Quartos</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 font-bold text-slate-700 text-sm whitespace-nowrap">€ {{ number_format($property->price, 0, ',', '.') }}</td>
                                    <td class="px-8 py-4 text-sm font-medium text-slate-500">{{ $property->location }}</td>
                                    <td class="px-8 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $property->status == 'Venda' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $property->status == 'Venda' ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                            {{ $property->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <a href="{{ route('admin.properties.edit', $property) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-400 hover:bg-ht-blue hover:text-white hover:border-transparent transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>