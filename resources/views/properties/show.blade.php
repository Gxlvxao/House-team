@extends('layouts.app')

@section('content')

<div class="pt-32 pb-12 bg-slate-50">
    <div class="container mx-auto px-6 md:px-12">
        
        {{-- CABEÇALHO --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10" data-aos="fade-up">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    {{-- Badge Tipo --}}
                    <span class="bg-ht-accent text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-500/30">
                        {{ $property->type }}
                    </span>
                    {{-- Badge Status --}}
                    <span class="bg-white text-ht-navy border border-slate-200 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">
                        {{ $property->status }}
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
            
            {{-- REFERÊNCIA --}}
            <div class="text-right hidden md:block">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">{{ __('portfolio.reference') }}</p>
                <p class="text-xl font-black text-ht-navy">
                    {{ $property->crm_code ?? '#' . ($property->id + 1000) }}
                </p>
            </div>
        </div>

        {{-- GALERIA (Alpine.js) --}}
        <div x-data="{ 
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
        }" class="relative rounded-[2.5rem] overflow-hidden shadow-2xl bg-slate-900 group mb-16 h-[50vh] md:h-[70vh]" data-aos="zoom-in">
            
            <div class="absolute inset-0 transition-all duration-700 ease-in-out">
                <img :src="activeImage" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity duration-500" alt="{{ $property->title }}">
                <div class="absolute inset-0 bg-gradient-to-t from-ht-navy/80 via-transparent to-transparent"></div>
            </div>

            {{-- Setas Navegação --}}
            <button @click="prev()" class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/30 backdrop-blur-md text-white p-4 rounded-full transition-all opacity-0 group-hover:opacity-100 transform -translate-x-4 group-hover:translate-x-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="next()" class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/30 backdrop-blur-md text-white p-4 rounded-full transition-all opacity-0 group-hover:opacity-100 transform translate-x-4 group-hover:translate-x-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            {{-- Miniaturas --}}
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 overflow-x-auto max-w-[90%] p-2 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/10">
                <template x-for="(img, index) in images" :key="index">
                    <button @click="setImage(index)" 
                            class="relative w-16 h-12 md:w-20 md:h-14 rounded-xl overflow-hidden transition-all duration-300 transform hover:scale-105"
                            :class="currentIndex === index ? 'ring-2 ring-ht-accent opacity-100 scale-105' : 'opacity-60 hover:opacity-100'">
                        <img :src="img" class="w-full h-full object-cover">
                    </button>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 relative">
            
            <div class="lg:col-span-8 space-y-12">
                
                {{-- Grid de Estatísticas --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    {{-- Quartos --}}
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 01-1 1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="block text-2xl font-black text-ht-navy">{{ $property->bedrooms ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('portfolio.label_bedrooms') }}</span>
                    </div>
                    {{-- Casas de Banho --}}
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="block text-2xl font-black text-ht-navy">{{ $property->bathrooms ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('portfolio.label_bathrooms') }}</span>
                    </div>
                    {{-- Área --}}
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        </div>
                        <span class="block text-2xl font-black text-ht-navy">{{ number_format($property->area_gross ?? 0, 0) }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">m² {{ __('portfolio.label_area') }}</span>
                    </div>
                    {{-- Garagem --}}
                    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center group hover:border-ht-accent/30 transition-colors">
                        <div class="text-ht-accent mb-2 transform group-hover:scale-110 transition-transform flex justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </div>
                        <span class="block text-2xl font-black text-ht-navy">{{ $property->garages ?? '-' }}</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('portfolio.label_garage') }}</span>
                    </div>
                </div>

                {{-- Descrição --}}
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-ht-navy mb-6">{{ __('portfolio.about_property') }}</h3>
                    <div class="prose prose-lg prose-slate text-slate-500 font-medium leading-relaxed text-justify max-w-none">
                        {{-- AQUI ESTÁ A CORREÇÃO: Usamos {!! !!} para renderizar HTML puro --}}
                        {!! $property->description !!}
                    </div>
                </div>

                {{-- Comodidades (Checklist) --}}
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                    <h3 class="text-2xl font-black text-ht-navy mb-8">{{ __('portfolio.features_title') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-12">
                        @if($property->has_pool) 
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_pool') }}</span>
                                <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div>
                            </div> 
                        @endif
                        @if($property->has_garden) 
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_garden') }}</span>
                                <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div>
                            </div> 
                        @endif
                        @if($property->has_lift) 
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_elevator') }}</span>
                                <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div>
                            </div> 
                        @endif
                        @if($property->has_terrace) 
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_terrace') }}</span>
                                <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div>
                            </div> 
                        @endif
                        @if($property->has_air_conditioning) 
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_ac') }}</span>
                                <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div>
                            </div> 
                        @endif
                        @if($property->is_furnished) 
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_furnished') }}</span>
                                <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div>
                            </div> 
                        @endif
                        @if($property->is_kitchen_equipped) 
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <span class="text-slate-600 font-bold text-sm">{{ __('portfolio.feat_kitchen') }}</span>
                                <div class="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xs">✓</div>
                            </div> 
                        @endif
                        
                        @if($property->floor) 
                            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl">
                                <span class="text-slate-400 font-bold text-xs uppercase">{{ __('portfolio.feat_floor') }}</span>
                                <span class="text-ht-navy font-bold text-sm">{{ $property->floor }}</span>
                            </div> 
                        @endif
                        @if($property->orientation) 
                            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl">
                                <span class="text-slate-400 font-bold text-xs uppercase">{{ __('portfolio.feat_orientation') }}</span>
                                <span class="text-ht-navy font-bold text-sm">{{ $property->orientation }}</span>
                            </div> 
                        @endif
                        @if($property->energy_rating) 
                            <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl">
                                <span class="text-slate-400 font-bold text-xs uppercase">{{ __('portfolio.feat_energy') }}</span>
                                <span class="bg-ht-accent text-white px-3 py-1 rounded text-xs font-bold">{{ $property->energy_rating }}</span>
                            </div> 
                        @endif
                    </div>
                </div>

                {{-- Vídeo Tour --}}
                @if($property->video_url)
                    <div class="bg-ht-navy p-8 md:p-10 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-ht-accent/20 rounded-full blur-3xl"></div>
                        <h3 class="text-2xl font-black text-white mb-8 relative z-10">{{ __('portfolio.video_title') }}</h3>
                        
                        @php
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $property->video_url, $match);
                            $youtube_id = $match[1] ?? null;
                        @endphp

                        @if($youtube_id)
                            <div class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-lg border border-white/10">
                                <iframe 
                                    src="https://www.youtube.com/embed/{{ $youtube_id }}" 
                                    title="YouTube video player" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen
                                    class="absolute inset-0 w-full h-full">
                                </iframe>
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

            <div class="lg:col-span-4">
                <div class="sticky top-32 space-y-6">
                    
                    <div class="bg-ht-navy text-white p-8 rounded-[2rem] shadow-2xl border border-white/10 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-ht-accent/30 rounded-full blur-3xl"></div>
                        
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 mb-2 relative z-10">{{ __('portfolio.investment_value') }}</p>
                        <p class="text-4xl lg:text-5xl font-black tracking-tight mb-8 relative z-10">
                            {{ $property->price ? '€ ' . number_format($property->price, 0, ',', '.') : __('portfolio.price_on_request') }}
                        </p>

                        @if($property->consultant)
                            {{-- CARD DO CONSULTOR --}}
                            <div class="relative z-10 bg-white/5 p-6 rounded-2xl border border-white/10 mb-6">
                                <div class="flex items-center gap-4 mb-4">
                                    <img src="{{ asset('img/team/' . $property->consultant->photo) }}" class="w-16 h-16 rounded-full object-cover border-2 border-ht-accent">
                                    <div>
                                        <p class="text-xs text-ht-accent font-bold uppercase tracking-wider">{{ __('portfolio.consultant_label') }}</p>
                                        <p class="font-bold text-lg leading-tight">{{ $property->consultant->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $property->consultant->role }}</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    {{-- [MODIFICADO] Passa o Código do Imóvel na URL --}}
                                    <a href="{{ route('contact', ['property_code' => $property->crm_code ?? $property->id]) }}" class="block w-full bg-white text-ht-navy font-black uppercase tracking-widest py-3 text-xs rounded-xl hover:bg-ht-accent hover:text-white transition-all text-center shadow-lg">
                                        {{ __('portfolio.btn_schedule') }}
                                    </a>
                                    @if($property->consultant->phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $property->consultant->phone) }}?text=Olá {{ $property->consultant->name }}, vi o imóvel {{ $property->title }} ({{ $property->crm_code ?? '#' . $property->id }}) no site." target="_blank" class="flex items-center justify-center gap-2 w-full border border-green-500 text-green-400 font-bold uppercase tracking-widest py-3 text-xs rounded-xl hover:bg-green-500 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                            WhatsApp
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            {{-- GENÉRICO (Se não tiver consultor) --}}
                            <div class="space-y-4 relative z-10">
                                {{-- [MODIFICADO] Passa o Código do Imóvel na URL --}}
                                <a href="{{ route('contact', ['property_code' => $property->crm_code ?? $property->id]) }}" class="block w-full bg-white text-ht-navy font-black uppercase tracking-widest py-4 text-xs rounded-xl hover:bg-ht-accent hover:text-white transition-all text-center shadow-lg transform active:scale-95">
                                    {{ __('portfolio.btn_schedule') }}
                                </a>

                                @if($property->whatsapp_number)
                                    <a href="https://wa.me/{{ $property->whatsapp_number }}?text=Olá, tenho interesse no imóvel: {{ $property->title }} (Ref: {{ $property->crm_code ?? $property->id }})" target="_blank" class="flex items-center justify-center gap-2 w-full border border-green-500 text-green-400 font-bold uppercase tracking-widest py-4 text-xs rounded-xl hover:bg-green-500 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        WhatsApp
                                    </a>
                                @endif
                            </div>
                        @endif

                        <div class="mt-8 pt-8 border-t border-white/10 text-center">
                            <p class="text-xs text-slate-400 mb-2">{{ __('portfolio.share_text') }}</p>
                            <div class="flex justify-center gap-4">
                                <a href="#" class="text-slate-300 hover:text-white transition">Facebook</a>
                                <a href="#" class="text-slate-300 hover:text-white transition">LinkedIn</a>
                                <a href="#" class="text-slate-300 hover:text-white transition">Email</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection