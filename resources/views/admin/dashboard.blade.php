@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Visão Geral')

@section('content')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
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
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
        </div>

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
@endsection