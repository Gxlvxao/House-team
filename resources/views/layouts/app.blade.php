<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <title>@yield('title', 'House Team Consultores')</title>
    
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
                        'ht-gold': '#c5a059', // Cor Premium da Consultora
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
        
        .glass-nav {
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 
    {{ Str::startsWith(Route::currentRouteName(), 'consultant.') ? 'selection:bg-ht-gold' : 'selection:bg-ht-accent' }} 
    selection:text-white">

    {{-- MENU DE NAVEGAÇÃO INTELIGENTE --}}
    <nav class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[95%] md:w-auto transition-all duration-500"
         x-data="{ isOpen: false, isScrolled: false }"
         @scroll.window="isScrolled = (window.pageYOffset > 20)">
         
        <div class="glass-nav rounded-full px-6 py-3 shadow-glass flex justify-center items-center md:gap-6 transition-all duration-300"
             :class="isScrolled ? 'py-3' : 'py-4'">
            
            {{-- DESKTOP MENU --}}
            <div class="hidden md:flex items-center gap-1">
                @if(Str::startsWith(Route::currentRouteName(), 'consultant.'))
                    {{-- === MENU DA CONSULTORA (NAVY & GOLD) === --}}
                    
                    {{-- LOGO DA CONSULTORA NA NAVBAR --}}
                    <a href="#home" class="mr-4 hover:opacity-80 transition-opacity">
                        <img src="{{ asset('img/logo/casaacasa.png') }}" 
                             alt="Casa a Casa" 
                             class="h-8 w-auto object-contain brightness-0 invert" 
                             onerror="this.style.display='none'">
                    </a>

                    <a href="#home" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">{{ __('consultant_lp.menu_home') }}</a>
                    <a href="#about" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">{{ __('consultant_lp.menu_about') }}</a>
                    <a href="#testimonials" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">{{ __('consultant_lp.menu_feedback') }}</a>
                    <a href="#portfolio" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">{{ __('consultant_lp.menu_portfolio') }}</a>

                    {{-- DROPDOWN FERRAMENTAS (GOLD) --}}
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all flex items-center gap-1">
                            {{ __('menu.tools') }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div class="absolute left-1/2 -translate-x-1/2 top-full mt-4 w-48 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top overflow-hidden">
                            {{-- Links com hover DOURADO --}}
                            <a href="{{ route('consultant.tools.credit', ['domain' => $consultant->domain ?? 'casaacasa.pt']) }}" class="block px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-ht-gold transition text-center">{{ __('menu.credit') }}</a>
                            <a href="{{ route('consultant.tools.gains', ['domain' => $consultant->domain ?? 'casaacasa.pt']) }}" class="block px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-ht-gold transition text-center">{{ __('menu.gains') }}</a>
                            <a href="{{ route('consultant.tools.imt', ['domain' => $consultant->domain ?? 'casaacasa.pt']) }}" class="block px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-ht-gold transition text-center">{{ __('menu.imt') }}</a>
                        </div>
                    </div>

                @else
                    {{-- === MENU DA HOUSE TEAM (PADRÃO) === --}}
                    
                    {{-- LOGO DA HOUSE TEAM NA NAVBAR --}}
                    {{-- Opcional: Se quiser a logo da HT aqui também, descomente --}}
                    {{-- <a href="{{ route('home') }}" class="mr-4 hover:opacity-80 transition-opacity">
                        <img src="{{ asset('img/logo.png') }}" class="h-8 w-auto brightness-0 invert">
                    </a> --}}

                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">{{ __('menu.home') }}</a>
                    <a href="{{ route('about') }}" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">{{ __('menu.team') }}</a>
                    <a href="{{ route('portfolio') }}" class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all">{{ __('menu.properties') }}</a>
                    
                    {{-- DROPDOWN FERRAMENTAS (RED) --}}
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-wider text-white hover:bg-white/10 transition-all flex items-center gap-1">
                            {{ __('menu.tools') }}
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div class="absolute left-1/2 -translate-x-1/2 top-full mt-4 w-48 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top overflow-hidden">
                            <a href="{{ route('tools.credit') }}" class="block px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-ht-accent transition text-center">{{ __('menu.credit') }}</a>
                            <a href="{{ route('tools.gains') }}" class="block px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-ht-accent transition text-center">{{ __('menu.gains') }}</a>
                            <a href="{{ route('tools.imt') }}" class="block px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-100 hover:text-ht-accent transition text-center">{{ __('menu.imt') }}</a>
                        </div>
                    </div>
                @endif
            </div>

            <div class="hidden md:flex items-center gap-6 ml-4">
                {{-- LÓGICA DO SELETOR E BOTÃO (DESKTOP) --}}
                @if(Str::startsWith(Route::currentRouteName(), 'consultant.'))
                    
                    <div class="flex items-center gap-2 text-[10px] font-bold tracking-widest">
                        <a href="{{ route('lang.switch', 'pt') }}" class="{{ app()->getLocale() == 'pt' ? 'text-ht-gold scale-110' : 'text-slate-400 hover:text-white transition' }}" title="Português">PT</a>
                        <span class="text-white/20">|</span>
                        <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'text-ht-gold scale-110' : 'text-slate-400 hover:text-white transition' }}" title="English">EN</a>
                    </div>

                    <a href="#contact" class="bg-ht-gold text-white px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-yellow-600 hover:shadow-lg hover:shadow-yellow-500/30 transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                        {{ __('consultant_lp.menu_contact') }}
                    </a>

                @else
                    <a href="{{ route('contact') }}" class="bg-ht-accent text-white px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-red-700 hover:shadow-lg hover:shadow-red-500/30 transition-all transform hover:-translate-y-0.5 whitespace-nowrap">
                        {{ __('menu.contact_us') }}
                    </a>
                @endif
            </div>

            {{-- MENU HAMBURGUER (MOBILE) --}}
            <div class="md:hidden flex w-full justify-between items-center">
                 {{-- SE QUISER A LOGO NO MOBILE TAMBÉM: --}}
                 {{-- @if(Str::startsWith(Route::currentRouteName(), 'consultant.'))
                    <img src="{{ asset('img/logo/casaacasa.png') }}" class="h-6 w-auto brightness-0 invert">
                 @else --}}
                    <span class="text-white text-xs font-bold uppercase tracking-widest">
                        @if(Str::startsWith(Route::currentRouteName(), 'consultant.')) MENU @else {{ __('menu.label_menu') }} @endif
                    </span>
                 {{-- @endif --}}
                 
                 <div class="flex items-center gap-3">
                    <button @click="isOpen = !isOpen" class="text-white p-1 rounded-full hover:bg-white/10 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                 </div>
            </div>
        </div>

        {{-- MOBILE MENU CONTENT --}}
        <div x-show="isOpen" x-collapse class="md:hidden mt-2 mx-auto w-[95%]">
            <div class="glass-nav rounded-2xl p-4 shadow-2xl flex flex-col gap-2">
                @if(Str::startsWith(Route::currentRouteName(), 'consultant.'))
                    {{-- LINKS CONSULTORA (MÓVEL) --}}
                    <a href="#home" @click="isOpen=false" class="block px-4 py-3 rounded-xl bg-white/5 text-white text-sm font-bold text-center">{{ __('consultant_lp.menu_home') }}</a>
                    <a href="#about" @click="isOpen=false" class="block px-4 py-3 rounded-xl hover:bg-white/5 text-white text-sm font-bold text-center transition">{{ __('consultant_lp.menu_about') }}</a>
                    <a href="#testimonials" @click="isOpen=false" class="block px-4 py-3 rounded-xl hover:bg-white/5 text-white text-sm font-bold text-center transition">{{ __('consultant_lp.menu_feedback') }}</a>
                    <a href="#portfolio" @click="isOpen=false" class="block px-4 py-3 rounded-xl hover:bg-white/5 text-white text-sm font-bold text-center transition">{{ __('consultant_lp.menu_portfolio') }}</a>
                    
                    <div class="grid grid-cols-3 gap-2 border-t border-white/10 pt-2 mt-2">
                        <a href="{{ route('consultant.tools.credit', ['domain' => $consultant->domain ?? 'casaacasa.pt']) }}" class="bg-white/5 rounded-lg p-2 text-center text-[10px] font-bold text-slate-300 hover:bg-white/10 hover:text-white">{{ __('menu.credit') }}</a>
                        <a href="{{ route('consultant.tools.gains', ['domain' => $consultant->domain ?? 'casaacasa.pt']) }}" class="bg-white/5 rounded-lg p-2 text-center text-[10px] font-bold text-slate-300 hover:bg-white/10 hover:text-white">{{ __('menu.gains') }}</a>
                        <a href="{{ route('consultant.tools.imt', ['domain' => $consultant->domain ?? 'casaacasa.pt']) }}" class="bg-white/5 rounded-lg p-2 text-center text-[10px] font-bold text-slate-300 hover:bg-white/10 hover:text-white">{{ __('menu.imt') }}</a>
                    </div>

                    <a href="#contact" @click="isOpen=false" class="block px-4 py-3 rounded-xl bg-ht-gold text-white text-sm font-bold text-center mt-2 shadow-lg hover:bg-yellow-600 transition">{{ __('consultant_lp.menu_contact') }}</a>
                    
                    <div class="flex justify-center items-center gap-6 py-4 border-t border-white/10 mt-2">
                        <a href="{{ route('lang.switch', 'pt') }}" class="{{ app()->getLocale() == 'pt' ? 'text-ht-gold font-bold scale-110' : 'text-slate-400 text-xs font-bold uppercase tracking-widest hover:text-white' }}">Português</a>
                        <span class="text-white/10">|</span>
                        <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() == 'en' ? 'text-ht-gold font-bold scale-110' : 'text-slate-400 text-xs font-bold uppercase tracking-widest hover:text-white' }}">English</a>
                    </div>

                @else
                    {{-- LINKS SITE NORMAL --}}
                    <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl bg-white/5 text-white text-sm font-bold text-center">{{ __('menu.home') }}</a>
                    <a href="{{ route('about') }}" class="block px-4 py-3 rounded-xl hover:bg-white/5 text-white text-sm font-bold text-center transition">{{ __('menu.team') }}</a>
                    <a href="{{ route('portfolio') }}" class="block px-4 py-3 rounded-xl hover:bg-white/5 text-white text-sm font-bold text-center transition">{{ __('menu.properties') }}</a>
                    <div class="grid grid-cols-3 gap-2 border-t border-white/10 pt-2 mt-2">
                        <a href="{{ route('tools.credit') }}" class="bg-white/5 rounded-lg p-2 text-center text-[10px] font-bold text-slate-300 hover:bg-white/10 hover:text-white">{{ __('menu.credit') }}</a>
                        <a href="{{ route('tools.gains') }}" class="bg-white/5 rounded-lg p-2 text-center text-[10px] font-bold text-slate-300 hover:bg-white/10 hover:text-white">{{ __('menu.gains') }}</a>
                        <a href="{{ route('tools.imt') }}" class="bg-white/5 rounded-lg p-2 text-center text-[10px] font-bold text-slate-300 hover:bg-white/10 hover:text-white">{{ __('menu.imt') }}</a>
                    </div>
                    <a href="{{ route('contact') }}" class="block px-4 py-3 rounded-xl bg-ht-accent text-white text-sm font-bold text-center mt-2 shadow-lg">{{ __('menu.contact_us') }}</a>
                @endif
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    {{-- RODAPÉ --}}
    @unless(Str::startsWith(Route::currentRouteName(), 'consultant.'))
        <footer class="bg-ht-navy text-white pt-24 pb-12 border-t border-white/5">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-white/10 pb-12">
                    <div class="col-span-1 md:col-span-2">
                        <a href="{{ route('home') }}" class="block mb-6">
                            <img src="{{ asset('img/logo.png') }}" alt="House Team" class="h-14 w-auto brightness-0 invert opacity-90 hover:opacity-100 transition-opacity">
                        </a>
                        <p class="text-slate-400 text-sm leading-relaxed max-w-md">
                            {{ __('footer.broker_description') }}
                        </p>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold uppercase tracking-widest mb-6 text-ht-accent">{{ __('footer.menu_title') }}</h5>
                        <ul class="space-y-3 text-sm text-slate-400">
                            <li><a href="{{ route('home') }}" class="hover:text-white transition">{{ __('menu.home') }}</a></li>
                            <li><a href="{{ route('about') }}" class="hover:text-white transition">{{ __('menu.team') }}</a></li>
                            <li><a href="{{ route('portfolio') }}" class="hover:text-white transition">{{ __('menu.properties') }}</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-white transition">{{ __('menu.contact_us') }}</a></li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-xs font-bold uppercase tracking-widest mb-6 text-ht-accent">{{ __('footer.contacts_title') }}</h5>
                        <ul class="space-y-4 text-sm text-slate-400">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <a href="tel:+351923224551" class="hover:text-white transition">+351 923 224 551</a>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:Clientes@houseteamconsultores.pt" class="hover:text-white transition break-all">Clientes@houseteamconsultores.pt</a>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>R. Cidade de Bissau, 1800-240 Lisboa</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-center md:text-left">
                        <p class="text-slate-500 text-xs uppercase tracking-widest">
                            &copy; {{ date('Y') }} House Team Consultores. {{ __('footer.rights_reserved') }} <span class="mx-1">|</span> NIF: 508615631
                        </p>
                        
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 text-[10px] uppercase tracking-wider text-slate-500 mt-2">
                            <a href="{{ route('legal.privacy') }}" class="hover:text-white transition">{{ __('legal.privacy') }}</a>
                            <a href="{{ route('legal.terms') }}" class="hover:text-white transition">{{ __('legal.terms') }}</a>
                            <a href="{{ route('legal.cookies') }}" class="hover:text-white transition">{{ __('legal.cookies') }}</a>
                            <a href="{{ route('legal.disclaimer') }}" class="hover:text-white transition">{{ __('legal.disclaimer') }}</a>
                        </div>
                    </div>
                    
                    <a href="https://www.maxselladvisor.com" target="_blank" class="opacity-50 hover:opacity-100 transition-opacity">
                        <img src="{{ asset('img/maxsell.png') }}" alt="MaxSell Advisor" class="h-6 w-auto brightness-0 invert">
                    </a>
                </div>
            </div>
        </footer>
    @endunless

    @include('partials.cookie-banner')

    {{-- BOTÃO WHATSAPP --}}
    <div class="fixed bottom-6 left-6 z-[100]">
        <a href="https://wa.me/351923224551" 
           target="_blank" 
           class="w-12 h-12 bg-[#25D366] text-white rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-transform duration-300 focus:outline-none hover:shadow-green-500/30 ring-2 ring-white/50" 
           title="{{ __('social.whatsapp_title') }}">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        </a>
    </div>

    {{-- BOTÕES FLUTUANTES (DIREITA) --}}
    <div class="fixed bottom-6 right-6 z-[100] flex flex-col gap-3" 
         x-data="{ showTop: false }" 
         @scroll.window="showTop = (window.pageYOffset > 300)">
        
        <button x-show="showTop" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-4"
                @click="window.scrollTo({top: 0, behavior: 'smooth'})" 
                class="w-12 h-12 bg-white {{ Str::startsWith(Route::currentRouteName(), 'consultant.') ? 'text-ht-gold hover:bg-ht-gold' : 'text-ht-accent hover:bg-ht-accent' }} rounded-full shadow-lg border border-slate-100 flex items-center justify-center hover:text-white transition-all duration-300 focus:outline-none transform hover:scale-110" 
                title="{{ __('ui.back_to_top') }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
        </button>

        <button @click="history.back()" 
                class="w-12 h-12 bg-white text-ht-navy rounded-full shadow-lg border border-slate-100 flex items-center justify-center hover:bg-ht-navy hover:text-white transition-all duration-300 focus:outline-none transform hover:scale-110" 
                title="{{ __('ui.back_page') }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </button>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 50 });
    </script>
</body>
</html>