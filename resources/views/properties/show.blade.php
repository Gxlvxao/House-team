@extends('layouts.app')

@section('content')

{{-- 
    1. OVERRIDE DE DESIGN SYSTEM (SE TIVER CONSULTORA)
    Transforma a página de "House Team" para "Navy & Gold" automaticamente.
--}}
@if(isset($consultant))
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600&display=swap');
        
        :root {
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Inter', sans-serif;
            --color-gold: #c5a059;
            --color-navy: #1e293b;
        }

        /* Override de Fontes */
        body { font-family: var(--font-sans) !important; }
        h1, h2, h3, h4 { font-family: var(--font-serif) !important; }

        /* Override de Cores (Substitui o vermelho/azul padrão) */
        .bg-ht-accent { background-color: var(--color-gold) !important; }
        .text-ht-accent { color: var(--color-gold) !important; }
        .border-ht-accent { border-color: var(--color-gold) !important; }
        .ring-ht-accent { --tw-ring-color: var(--color-gold) !important; }
        
        .bg-ht-navy { background-color: var(--color-navy) !important; }
        .text-ht-navy { color: var(--color-navy) !important; }

        /* Ajustes Específicos */
        .hover\:bg-ht-accent:hover { background-color: #b08d4b !important; }
        .shadow-blue-500\/30 { --tw-shadow-color: rgba(197, 160, 89, 0.4) !important; }
        
        /* Botões mais elegantes */
        button, a.block { border-radius: 4px !important; text-transform: uppercase; letter-spacing: 1px; font-size: 11px; font-weight: 700; }
    </style>
@endif

{{-- CONTAINER PRINCIPAL --}}
<div class="pt-32 pb-12 bg-slate-50 relative" 
     x-data="{ 
        isModalOpen: false, 
        activeImage: '{{ $property->cover_image ? asset('storage/' . $property->cover_image) : asset('img/porto.jpg') }}',
        images: [
            '{{ $property->cover_image ? asset('storage/' . $property->cover_image) : asset('img/porto.jpg') }}',
            @foreach($property->images as $img)
                '{{ asset('storage/' . $img->path) }}',
            @endforeach
        ],
        currentIndex: 0,
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
            this.activeImage = this.images[this.currentIndex];
        },
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            this.activeImage = this.images[this.currentIndex];
        },
        setImage(index) {
            this.currentIndex = index;
            this.activeImage = this.images[index];
        }
    }"
    @keydown.escape.window="isModalOpen = false"
    @keydown.arrow-right.window="if(isModalOpen) next()"
    @keydown.arrow-left.window="if(isModalOpen) prev()"
>
    
    {{-- Decoração de Fundo (Apenas se tiver consultor) --}}
    @if(isset($consultant))
        <div class="absolute top-0 right-0 w-1/3 h-96 bg-ht-navy opacity-5 -z-10" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%);"></div>
    @endif

    <div class="container mx-auto px-6 md:px-12 relative z-10">
        
        {{-- CABEÇALHO --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10" data-aos="fade-up">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="bg-ht-accent text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-500/30">
                        {{ __('property_type.' . \Illuminate\Support\Str::slug($property->type)) }}
                    </span>
                    <span class="bg-white text-ht-navy border border-slate-200 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">
                        {{ __('property_status.' . \Illuminate\Support\Str::slug($property->status)) }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-5xl font-black text-ht-navy leading-tight max-w-4xl">
                    {{ $property->title }}
                </h1>
                <p class="text-slate-500 font-medium mt-4 flex items-center gap-2 text-lg">
                    <svg class="w-5 h-5 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $property->location }} {{ $property->city ? '• ' . $property->city : '' }}
                </p>
            </div>
            
            <div class="text-right hidden md:block">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">{{ __('portfolio.reference') }}</p>
                <p class="text-xl font-black text-ht-navy">
                    {{ $property->crm_code ?? '#' . ($property->id + 1000) }}
                </p>
            </div>
        </div>

        {{-- GALERIA DE IMAGENS --}}
        <div class="relative rounded-[2rem] overflow-hidden shadow-2xl bg-slate-900 group mb-16 h-[50vh] md:h-[70vh]" data-aos="zoom-in">
            <div class="absolute inset-0 transition-all duration-700 ease-in-out cursor-zoom-in" @click="isModalOpen = true">
                <img :src="activeImage" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity duration-500" alt="{{ $property->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-ht-navy/80 via-transparent to-transparent"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/50 backdrop-blur-md text-white px-6 py-3 rounded-full flex items-center gap-2 pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                    <span class="text-xs font-bold uppercase tracking-widest">{{ __('portfolio.zoom_image') }}</span>
                </div>
            </div>
            {{-- Setas de Navegação --}}
            <button @click.stop="prev()" class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/30 backdrop-blur-md text-white p-4 rounded-full transition-all opacity-0 group-hover:opacity-100 transform -translate-x-4 group-hover:translate-x-0 z-20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click.stop="next()" class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/30 backdrop-blur-md text-white p-4 rounded-full transition-all opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0 z-20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            {{-- Miniaturas --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 overflow-x-auto max-w-[90%] p-2 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/10 z-20 scrollbar-hide">
                <template x-for="(img, index) in images" :key="index">
                    <button @click.stop="setImage(index)" 
                            class="relative w-16 h-12 md:w-20 md:h-14 rounded-xl overflow-hidden transition-all duration-300 transform hover:scale-105 flex-shrink-0"
                            :class="currentIndex === index ? 'ring-2 ring-ht-accent opacity-100 scale-105' : 'opacity-60 hover:opacity-100'">
                        <img :src="img" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 relative">
            
            {{-- COLUNA ESQUERDA (Info Principal) --}}
            <div class="lg:col-span-8 space-y-12">
                {{-- STATS GRID --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 01-1 1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="block text-2xl font-black text-ht-navy">{{ $property->bedrooms ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('portfolio.label_bedrooms') }}</span>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="block text-2xl font-black text-ht-navy">{{ $property->bathrooms ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('portfolio.label_bathrooms') }}</span>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        </div>
                        <span class="block text-2xl font-black text-ht-navy">{{ number_format($property->area_gross ?? 0, 0) }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">m² {{ __('portfolio.label_area') }}</span>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                        <span class="block text-2xl font-black text-ht-navy">{{ $property->garages ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('portfolio.label_garage') }}</span>
                    </div>
                </div>

                {{-- DESCRIÇÃO --}}
                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-ht-navy mb-6">{{ __('portfolio.about_property') }}</h3>
                    <div class="prose prose-lg prose-slate text-slate-500 font-medium leading-relaxed text-justify max-w-none">
                        {!! $property->description !!}
                    </div>
                </div>

                {{-- COMODIDADES --}}
                <div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-ht-navy mb-8">{{ __('portfolio.features_title') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($property->has_pool) <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl"><span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_pool') }}</span><div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div></div> @endif
                        @if($property->has_garden) <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl"><span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_garden') }}</span><div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div></div> @endif
                        @if($property->has_lift) <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl"><span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_elevator') }}</span><div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div></div> @endif
                        @if($property->has_terrace) <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl"><span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_terrace') }}</span><div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div></div> @endif
                        @if($property->has_air_conditioning) <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl"><span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_ac') }}</span><div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div></div> @endif
                        @if($property->is_furnished) <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl"><span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_furnished') }}</span><div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div></div> @endif
                        @if($property->is_kitchen_equipped) <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl"><span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_kitchen') }}</span><div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div></div> @endif
                        @if($property->floor) <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl"><span class="text-slate-400 font-bold text-xs uppercase">{{ __('portfolio.feat_floor') }}</span><span class="text-ht-navy font-bold text-sm">{{ $property->floor }}</span></div> @endif
                        @if($property->orientation) <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl"><span class="text-slate-400 font-bold text-xs uppercase">{{ __('portfolio.feat_orientation') }}</span><span class="text-ht-navy font-bold text-sm">{{ $property->orientation }}</span></div> @endif
                        @if($property->energy_rating) <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl"><span class="text-slate-400 font-bold text-xs uppercase">{{ __('portfolio.feat_energy') }}</span><span class="bg-ht-accent text-white px-3 py-1 rounded text-xs font-bold">{{ $property->energy_rating }}</span></div> @endif
                    </div>
                </div>

                {{-- VÍDEO --}}
                @if($property->video_url)
                    <div class="bg-ht-navy p-8 md:p-10 rounded-[2rem] shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-ht-accent/20 rounded-full blur-3xl"></div>
                        <h3 class="text-2xl font-black text-white mb-8 relative z-10">{{ __('portfolio.video_title') }}</h3>
                        @php
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $property->video_url, $match);
                            $youtube_id = $match[1] ?? null;
                        @endphp
                        @if($youtube_id)
                            <div class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-lg border border-white/10">
                                <iframe src="https://www.youtube.com/embed/{{ $youtube_id }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="absolute inset-0 w-full h-full"></iframe>
                            </div>
                        @else
                            <a href="{{ $property->video_url }}" target="_blank" class="flex items-center justify-center gap-4 bg-white text-ht-navy px-8 py-6 rounded-2xl font-black uppercase tracking-widest hover:bg-ht-accent hover:text-white transition-all shadow-xl">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                {{ __('portfolio.video_btn') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- COLUNA DIREITA (Sidebar Sticky) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-32 space-y-6">
                    
                    {{-- 
                        LÓGICA CONSULTOR ATUAL
                        Se houver um consultor na URL (Landing Page mode), usamos ele.
                        Senão, usamos o consultor dono do imóvel.
                    --}}
                    @php
                        $currentConsultant = isset($consultant) ? $consultant : $property->consultant;
                    @endphp

                    <div class="bg-ht-navy text-white p-8 rounded-[2rem] shadow-2xl border border-white/10 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-ht-accent/30 rounded-full blur-3xl"></div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 mb-2 relative z-10">{{ __('portfolio.investment_value') }}</p>
                        <p class="text-4xl lg:text-5xl font-black tracking-tight mb-8 relative z-10">
                            {{ $property->price ? '€ ' . number_format($property->price, 0, ',', '.') : __('portfolio.price_on_request') }}
                        </p>

                        @if($currentConsultant)
                            <div class="relative z-10 bg-white/5 p-6 rounded-2xl border border-white/10 mb-6 backdrop-blur-sm">
                                <div class="flex items-center gap-4 mb-4">
                                    <img src="{{ $currentConsultant->image_url ?? asset('img/team/' . $currentConsultant->photo) }}" 
                                         class="w-14 h-14 rounded-full object-cover border-2 border-ht-accent shadow-md"
                                         onerror="this.src='{{ asset('img/logo.png') }}'">
                                    <div>
                                        <p class="text-[10px] text-ht-accent font-bold uppercase tracking-wider">{{ __('portfolio.consultant_label') }}</p>
                                        <p class="font-bold text-lg leading-tight">{{ $currentConsultant->name }}</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <a href="{{ route('contact', ['property_code' => $property->crm_code ?? $property->id, 'consultant_id' => $currentConsultant->id]) }}" class="block w-full bg-white text-ht-navy font-black uppercase tracking-widest py-3 text-xs rounded-lg hover:bg-ht-accent hover:text-white transition-all text-center shadow-lg">
                                        {{ __('portfolio.btn_schedule') }}
                                    </a>
                                    @if($currentConsultant->phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $currentConsultant->phone) }}?text=Olá {{ $currentConsultant->name }}, estou no seu site e vi o imóvel {{ $property->title }}." target="_blank" class="flex items-center justify-center gap-2 w-full border border-green-500 text-green-400 font-bold uppercase tracking-widest py-3 text-xs rounded-lg hover:bg-green-500 hover:text-white transition-all">
                                            WhatsApp
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="space-y-4 relative z-10">
                                <a href="{{ route('contact', ['property_code' => $property->crm_code ?? $property->id]) }}" class="block w-full bg-white text-ht-navy font-black uppercase tracking-widest py-4 text-xs rounded-lg hover:bg-ht-accent hover:text-white transition-all text-center shadow-lg">
                                    {{ __('portfolio.btn_schedule') }}
                                </a>
                            </div>
                        @endif

                        <div class="mt-8 pt-8 border-t border-white/10 text-center">
                            <p class="text-xs text-slate-400 mb-2">{{ __('portfolio.share_text') }}</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-slate-300 hover:text-white transition transform hover:scale-110"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg></a>
                                <a href="#" class="text-slate-300 hover:text-white transition transform hover:scale-110"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL LIGHTBOX --}}
    <div x-show="isModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[200] flex items-center justify-center bg-black/95 p-4 md:p-10"
         x-cloak>
        <button @click="isModalOpen = false" class="absolute top-6 right-6 text-white hover:text-ht-accent transition-colors z-[210]">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <button @click.stop="prev()" class="absolute left-4 md:left-10 top-1/2 -translate-y-1/2 text-white hover:text-ht-accent p-4 z-[210] transition-transform hover:-translate-x-1">
            <svg class="w-10 h-10 md:w-16 md:h-16 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click.stop="next()" class="absolute right-4 md:right-10 top-1/2 -translate-y-1/2 text-white hover:text-ht-accent p-4 z-[210] transition-transform hover:translate-x-1">
            <svg class="w-10 h-10 md:w-16 md:h-16 drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
        </button>
        <img :src="activeImage" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" @click.away="isModalOpen = false">
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/50 text-xs font-bold uppercase tracking-widest text-center">
            {{ $property->title }} <br>
            <span class="text-[10px] opacity-70" x-text="(currentIndex + 1) + ' / ' + images.length"></span>
        </div>
    </div>
</div>

@endsection