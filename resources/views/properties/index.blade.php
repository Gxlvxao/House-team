@extends('layouts.app')

@section('content')

{{-- HERO HEADER --}}
<div class="bg-ht-navy text-white pt-32 pb-20 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    <div class="absolute bottom-0 left-0 w-full h-24 bg-gradient-to-t from-slate-50 to-transparent"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <p class="text-ht-accent font-bold text-xs uppercase tracking-[0.3em] mb-4">{{ __('portfolio.hero_badge') }}</p>
        <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-4">{{ __('portfolio.hero_title') }}</h1>
        <p class="text-slate-400 font-medium max-w-xl mx-auto">{{ __('portfolio.hero_desc') }}</p>
    </div>
</div>

<section class="py-16 bg-slate-50 min-h-screen relative z-20 -mt-10">
    <div class="container mx-auto px-6 md:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            {{-- SIDEBAR FILTROS --}}
            <aside class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[2rem] shadow-xl border border-slate-100 sticky top-32">
                    <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100">
                        <h3 class="font-bold text-xl text-ht-navy">{{ __('portfolio.filter_title') }}</h3>
                        <a href="{{ route('portfolio') }}" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-ht-accent transition-colors">{{ __('portfolio.filter_clear') }}</a>
                    </div>
                    
                    <form action="{{ route('portfolio') }}" method="GET" class="space-y-6">
                        
                        {{-- Localização --}}
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1 mb-2 block">{{ __('portfolio.label_location') }}</label>
                            <div class="relative">
                                <input type="text" name="location" value="{{ request('location') }}" placeholder="{{ __('portfolio.placeholder_location') }}" 
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-accent focus:ring-1 focus:ring-ht-accent transition-all placeholder-slate-400">
                                <svg class="w-4 h-4 absolute right-4 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>

                        {{-- Tipo de Imóvel --}}
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1 mb-2 block">{{ __('portfolio.label_type') }}</label>
                            <div class="relative">
                                <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-accent focus:ring-1 focus:ring-ht-accent appearance-none transition-all cursor-pointer">
                                    <option value="">{{ __('portfolio.option_all') }}</option>
                                    <option value="Apartamento" {{ request('type') == 'Apartamento' ? 'selected' : '' }}>{{ __('portfolio.type_apartment') }}</option>
                                    <option value="Moradia" {{ request('type') == 'Moradia' ? 'selected' : '' }}>{{ __('portfolio.type_villa') }}</option>
                                    <option value="Terreno" {{ request('type') == 'Terreno' ? 'selected' : '' }}>{{ __('portfolio.type_land') }}</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Finalidade --}}
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1 mb-3 block">{{ __('portfolio.label_status') }}</label>
                            <div class="flex bg-slate-50 p-1 rounded-xl border border-slate-200">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="status" value="Venda" {{ request('status') == 'Venda' ? 'checked' : '' }} class="peer sr-only">
                                    <span class="block text-center py-2 rounded-lg text-xs font-bold text-slate-500 peer-checked:bg-white peer-checked:text-ht-accent peer-checked:shadow-sm transition-all">{{ __('portfolio.status_sale') }}</span>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="status" value="Arrendamento" {{ request('status') == 'Arrendamento' ? 'checked' : '' }} class="peer sr-only">
                                    <span class="block text-center py-2 rounded-lg text-xs font-bold text-slate-500 peer-checked:bg-white peer-checked:text-ht-accent peer-checked:shadow-sm transition-all">{{ __('portfolio.status_rent') }}</span>
                                </label>
                            </div>
                        </div>

                        {{-- Preço --}}
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1 mb-2 block">{{ __('portfolio.label_price') }} (€)</label>
                            <div class="flex gap-2 items-center">
                                <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Min" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-sm font-medium focus:border-ht-accent outline-none transition-all">
                                <span class="text-slate-300">-</span>
                                <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Max" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-3 text-sm font-medium focus:border-ht-accent outline-none transition-all">
                            </div>
                        </div>

                        {{-- Quartos --}}
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1 mb-2 block">{{ __('portfolio.label_bedrooms') }}</label>
                            <div class="flex gap-2">
                                @foreach(['1', '2', '3', '4+'] as $bed)
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="bedrooms" value="{{ $bed }}" {{ request('bedrooms') == $bed ? 'checked' : '' }} class="peer sr-only">
                                        <span class="block py-2 text-center border border-slate-200 rounded-lg text-xs font-bold text-slate-500 peer-checked:bg-ht-navy peer-checked:text-white peer-checked:border-ht-navy hover:border-ht-accent transition-all">
                                            {{ $bed }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-ht-accent text-white font-black uppercase tracking-widest text-xs py-4 rounded-xl hover:bg-blue-600 transition-all shadow-lg hover:shadow-blue-500/30 transform active:scale-95">
                            {{ __('portfolio.btn_filter') }}
                        </button>
                    </form>
                </div>
            </aside>

            {{-- GRID DE IMÓVEIS --}}
            <div class="lg:col-span-3">
                <div class="flex justify-between items-center mb-8 px-2">
                    <p class="text-slate-500 text-sm font-medium">{{ __('portfolio.showing') }} <span class="text-ht-navy font-bold">{{ $properties->total() }}</span> {{ __('portfolio.properties') }}</p>
                    <div class="hidden md:block text-xs font-bold uppercase tracking-widest text-slate-400">
                        {{ __('portfolio.order_recent') }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($properties as $property)
                        <a href="{{ route('properties.show', $property) }}" class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-slate-100">
                            
                            <div class="relative h-64 overflow-hidden">
                                <img src="{{ $property->cover_image ? asset('storage/' . $property->cover_image) : asset('img/porto.jpg') }}" 
                                     alt="{{ $property->title }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                                
                                {{-- BADGE TIPO --}}
                                <div class="absolute top-4 left-4 bg-slate-900 text-white px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider shadow-md">
                                    {{ $property->type }}
                                </div>
                                {{-- BADGE STATUS --}}
                                <div class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1 text-[10px] font-bold text-ht-navy rounded-full uppercase tracking-wider shadow-sm">
                                    {{ $property->status }}
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="flex items-start justify-between mb-2">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $property->location }}
                                    </p>
                                </div>

                                <h4 class="text-lg font-bold text-ht-navy mb-4 line-clamp-2 leading-tight group-hover:text-ht-accent transition-colors">
                                    {{ $property->title }}
                                </h4>

                                <div class="flex items-center gap-4 text-xs font-medium text-slate-500 border-t border-slate-100 pt-4 mb-4">
                                    @if($property->bedrooms)
                                        <span class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-lg">
                                            <svg class="w-4 h-4 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            {{ $property->bedrooms }}
                                        </span>
                                    @endif
                                    @if($property->area_gross)
                                        <span class="flex items-center gap-1 bg-slate-50 px-2 py-1 rounded-lg">
                                            <svg class="w-4 h-4 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                            {{ number_format($property->area_gross, 0) }} m²
                                        </span>
                                    @endif
                                </div>

                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] uppercase font-bold text-slate-400">{{ __('portfolio.label_price_short') }}</p>
                                        <p class="text-xl font-black text-ht-accent">
                                            {{ $property->price ? '€ ' . number_format($property->price, 0, ',', '.') : __('portfolio.price_on_request') }}
                                        </p>
                                    </div>
                                    <span class="w-8 h-8 rounded-full bg-slate-100 text-ht-navy flex items-center justify-center group-hover:bg-ht-navy group-hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-3 text-center py-20 bg-white rounded-[2rem] border border-dashed border-slate-300">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            <p class="text-slate-500 font-medium">{{ __('portfolio.no_results') }}</p>
                            <a href="{{ route('portfolio') }}" class="text-ht-accent font-bold text-sm hover:underline mt-2 inline-block">{{ __('portfolio.filter_clear') }}</a>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $properties->links() }}
                </div>
            </div>

        </div>
    </div>
</section>

@endsection