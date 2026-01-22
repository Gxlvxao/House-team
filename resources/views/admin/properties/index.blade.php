@extends('layouts.admin')

@section('title', 'Meus Imóveis')
@section('header_title', 'Gerir Imóveis')

@section('content')

    {{-- 1. Carregar biblioteca de Drag & Drop (CDN rápido) --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    {{-- Feedback de Sucesso --}}
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-pulse">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="font-bold text-sm">{!! session('success') !!}</div>
        </div>
    @endif

    {{-- Header + Actions --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
        <div>
            <h2 class="text-3xl font-black text-ht-navy tracking-tight">Gerir Imóveis</h2>
            <p class="text-slate-500 text-sm mt-1 font-medium">Controle total do seu portfólio imobiliário. Arraste para reordenar.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.properties.create') }}" class="flex items-center gap-2 bg-ht-navy text-white px-5 py-2.5 rounded-xl shadow-lg hover:bg-ht-blue transition-all font-bold uppercase text-[11px] tracking-widest transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Adicionar
            </a>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 mb-6">
        <form action="{{ route('admin.properties.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            {{-- Filtro Negócio --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1.5 ml-1">Negócio</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-blue/20">
                    <option value="">Todos os tipos</option>
                    <option value="Venda" {{ request('status') == 'Venda' ? 'selected' : '' }}>Venda</option>
                    <option value="Arrendamento" {{ request('status') == 'Arrendamento' ? 'selected' : '' }}>Arrendamento</option>
                </select>
            </div>

            {{-- Filtro Visibilidade --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1.5 ml-1">Visibilidade</label>
                <select name="visibility" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-blue/20">
                    <option value="">Todos os status</option>
                    <option value="active" {{ request('visibility') == 'active' ? 'selected' : '' }}>Ativos (No Site)</option>
                    <option value="inactive" {{ request('visibility') == 'inactive' ? 'selected' : '' }}>Inativos (Ocultos)</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-xl text-sm font-bold hover:bg-ht-navy transition-all">
                    Filtrar
                </button>
                @if(request()->hasAny(['status', 'visibility']))
                    <a href="{{ route('admin.properties.index') }}" class="bg-slate-100 text-slate-500 px-4 py-2 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">
                        Limpar
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-[10px] uppercase text-slate-400 font-bold tracking-wider">
                    <tr>
                        <th class="pl-4 py-4 w-10"></th> 
                        <th class="px-6 py-4">Imóvel</th>
                        <th class="px-6 py-4">Preço</th>
                        <th class="px-6 py-4">Localização</th>
                        <th class="px-6 py-4">Negócio</th>
                        <th class="px-6 py-4">Visibilidade</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-slate-100" id="properties-list">
                    @foreach($properties as $property)
                    <tr data-id="{{ $property->id }}" class="hover:bg-slate-50/80 transition-colors group bg-white">
                        
                        <td class="pl-4 py-4 cursor-move handle text-slate-300 hover:text-ht-blue transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                            </svg>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden relative shadow-sm border border-slate-200 flex-shrink-0">
                                    @if($property->cover_image)
                                        <img src="{{ asset('storage/'.$property->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-ht-navy text-sm group-hover:text-ht-blue transition-colors line-clamp-1 max-w-[250px]">{{ $property->title }}</p>
                                    <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                        {{ $property->type }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-ht-navy whitespace-nowrap">
                            € {{ number_format($property->price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            <div class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $property->location }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $property->status == 'Venda' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $property->status == 'Venda' ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                {{ $property->status }}
                            </span>
                        </td>

                        {{-- FEEDBACK VISIBILIDADE --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($property->is_visible)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_5px_rgba(34,197,94,0.5)]"></span>
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-400 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Inativo
                                    </span>
                                @endif
                                
                                <form action="{{ route('admin.properties.toggle', $property->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="p-1.5 rounded-lg border border-slate-200 bg-white text-slate-400 hover:text-ht-blue hover:border-ht-blue transition-all shadow-sm" title="{{ $property->is_visible ? 'Desativar Imóvel' : 'Ativar Imóvel' }}">
                                        @if($property->is_visible)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 text-right">
                                <form action="{{ route('admin.properties.moveToTop', $property) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-lg bg-amber-50 border border-amber-200 text-amber-500 hover:bg-amber-100 transition-all shadow-sm" title="Mover para o topo">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                    </button>
                                </form>

                                <a href="{{ route('admin.properties.edit', $property) }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-ht-blue hover:border-ht-blue transition-all shadow-sm" title="Editar">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                
                                <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Tem a certeza que deseja eliminar este imóvel?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="p-2 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-red-500 hover:border-red-500 transition-all shadow-sm" title="Apagar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6 px-2">
        {{ $properties->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('properties-list');
            var sortable = Sortable.create(el, {
                handle: '.handle',
                animation: 150,
                ghostClass: 'bg-blue-50',
                onEnd: function () {
                    var order = sortable.toArray(); 
                    fetch("{{ route('admin.properties.reorder') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ ids: order })
                    })
                    .then(response => response.json())
                    .then(data => { console.log('Ordem salva!'); })
                    .catch(error => { alert('Erro ao salvar ordem.'); });
                }
            });
        });
    </script>

@endsection