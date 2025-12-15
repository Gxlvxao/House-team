<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Team Consultores</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        'ht-navy': '#020617', 
                        'ht-primary': '#1e3a8a',
                        'ht-accent': '#dc2626',
                        'ht-dark': '#020617',
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.3)',
                    }
                }
            }
        }
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        
        .glass-nav {
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 selection:bg-ht-accent selection:text-white">

    <nav class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[95%] md:w-auto transition-all duration-500"
         x-data="{ isOpen: false, isScrolled: false }"
         @scroll.window="isScrolled = (window.pageYOffset > 20)">
         
        <div class="glass-nav rounded-full px-6 py-3 shadow-glass flex justify-between items-center md:gap-10 transition-all duration-300"
             :class="isScrolled ? 'py-3' : 'py-4'">
            
            <a href="{{ route('home') }}" class="block">
                <img src="{{ asset('img/logo.png') }}" alt="House Team" class="h-8 md:h-10 w-auto">
            </a>

            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">Home</a>
                <a href="{{ route('about') }}" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">Equipa</a>
                <a href="{{ route('portfolio') }}" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">Imóveis</a>
                
                <div class="relative group">
                    <button class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all flex items-center gap-1">
                        Ferramentas
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div class="absolute left-1/2 -translate-x-1/2 top-full mt-4 w-48 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top overflow-hidden">
                        <a href="{{ route('tools.credit') }}" class="block px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-ht-accent transition text-center">Crédito</a>
                        <a href="{{ route('tools.gains') }}" class="block px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-ht-accent transition text-center">Mais-Valias</a>
                        <a href="{{ route('tools.imt') }}" class="block px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-ht-accent transition text-center">IMT</a>
                    </div>
                </div>
            </div>

            <div class="hidden md:block ml-4">
                <a href="{{ route('contact') }}" class="bg-ht-accent text-white px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-red-700 hover:shadow-lg hover:shadow-red-500/30 transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                    Contacte-nos
                </a>
            </div>

            <button @click="isOpen = !isOpen" class="md:hidden text-white p-1 rounded-full hover:bg-white/10 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <div x-show="isOpen" x-collapse class="md:hidden mt-2 mx-auto w-[95%]">
            <div class="glass-nav rounded-2xl p-4 shadow-2xl flex flex-col gap-2">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl bg-white/5 text-white text-sm font-bold text-center">Home</a>
                <a href="{{ route('about') }}" class="block px-4 py-3 rounded-xl hover:bg-white/5 text-white text-sm font-bold text-center transition">Equipa</a>
                <a href="{{ route('portfolio') }}" class="block px-4 py-3 rounded-xl hover:bg-white/5 text-white text-sm font-bold text-center transition">Imóveis</a>
                <div class="grid grid-cols-3 gap-2 border-t border-white/10 pt-2 mt-2">
                    <a href="{{ route('tools.credit') }}" class="bg-white/5 rounded-lg p-2 text-center text-[10px] font-bold text-slate-300 hover:bg-white/10 hover:text-white">Crédito</a>
                    <a href="{{ route('tools.gains') }}" class="bg-white/5 rounded-lg p-2 text-center text-[10px] font-bold text-slate-300 hover:bg-white/10 hover:text-white">Mais-Valias</a>
                    <a href="{{ route('tools.imt') }}" class="bg-white/5 rounded-lg p-2 text-center text-[10px] font-bold text-slate-300 hover:bg-white/10 hover:text-white">IMT</a>
                </div>
                <a href="{{ route('contact') }}" class="block px-4 py-3 rounded-xl bg-ht-accent text-white text-sm font-bold text-center mt-2 shadow-lg">Contacte-nos</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-ht-navy text-white pt-24 pb-12 border-t border-white/5">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-white/10 pb-12">
                <div class="col-span-1 md:col-span-2">
                    <img src="{{ asset('img/logo.png') }}" alt="House Team" class="h-12 mb-6 opacity-90 grayscale hover:grayscale-0 transition-all">
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md">
                        Broker Empreendedor. Uma abordagem moderna ao imobiliário, focada na transparência, tecnologia e resultados.
                    </p>
                </div>
                <div>
                    <h5 class="text-xs font-bold uppercase tracking-widest mb-6 text-ht-accent">Menu</h5>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">Equipa</a></li>
                        <li><a href="{{ route('portfolio') }}" class="hover:text-white transition">Imóveis</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-xs font-bold uppercase tracking-widest mb-6 text-ht-accent">Contactos</h5>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li>+351 910 739 610</li>
                        <li>dmgmaia@remax.pt</li>
                        <li>Av. Casal Ribeiro 12B</li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 text-center text-xs text-slate-500 uppercase tracking-widest">
                © 2025 House Team Consultores.
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 50 });
    </script>
</body>
</html>