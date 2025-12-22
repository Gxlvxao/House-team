@extends('layouts.admin')

@section('title', 'Equipe')
@section('header_title', 'Gerir Equipe')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('admin.consultants.create') }}" class="flex items-center gap-2 bg-ht-blue hover:bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-glow transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo Consultor
    </a>
</div>

<div class="bg-white rounded-2xl shadow-soft border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/80 text-[10px] uppercase text-slate-400 font-bold tracking-wider">
                <tr>
                    <th class="px-8 py-4 rounded-tl-lg">Consultor</th>
                    <th class="px-8 py-4">Cargo</th>
                    <th class="px-8 py-4">Contato</th>
                    <th class="px-8 py-4 text-center">Ordem</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 rounded-tr-lg text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($consultants as $consultant)
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="px-8 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-slate-200 overflow-hidden flex-shrink-0 relative shadow-sm border-2 border-white ring-1 ring-slate-100">
                                @if($consultant->photo)
                                    <img src="{{ asset('storage/'.$consultant->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-ht-navy text-white text-xs font-bold">
                                        {{ substr($consultant->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-ht-navy text-sm">{{ $consultant->name }}</p>
                                <p class="text-[10px] text-slate-400 uppercase tracking-wide">ID: #{{ $consultant->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-4 text-sm font-medium text-slate-600">{{ $consultant->role }}</td>
                    <td class="px-8 py-4">
                        <div class="text-xs space-y-1">
                            @if($consultant->email)
                            <div class="flex items-center gap-2 text-slate-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $consultant->email }}
                            </div>
                            @endif
                            @if($consultant->phone)
                            <div class="flex items-center gap-2 text-slate-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $consultant->phone }}
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-8 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-slate-100 text-xs font-bold text-slate-600">
                            {{ $consultant->order }}
                        </span>
                    </td>
                    <td class="px-8 py-4">
                        @if($consultant->is_active)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-700 border border-green-200">
                                Ativo
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                Inativo
                            </span>
                        @endif
                    </td>
                    <td class="px-8 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.consultants.edit', $consultant) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:bg-ht-blue hover:text-white hover:border-transparent transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form action="{{ route('admin.consultants.destroy', $consultant) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este consultor?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:bg-red-500 hover:text-white hover:border-transparent transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-8 py-12 text-center text-slate-400 text-sm">
                        Nenhum consultor encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection