@extends('layouts.admin')

@section('title', 'Editar Consultor')
@section('header_title', 'Editar: ' . $consultant->name)

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.consultants.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-ht-navy transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Voltar para Lista
        </a>
    </div>

    <form action="{{ route('admin.consultants.update', $consultant) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-soft border border-slate-100 p-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Nome Completo <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $consultant->name) }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:border-ht-blue focus:bg-white focus:ring-0 transition-all font-semibold text-slate-700" required>
                    @error('name') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Cargo</label>
                    <input type="text" name="role" value="{{ old('role', $consultant->role) }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:border-ht-blue focus:bg-white focus:ring-0 transition-all font-semibold text-slate-700">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $consultant->email) }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:border-ht-blue focus:bg-white focus:ring-0 transition-all font-semibold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Telemóvel</label>
                        <input type="text" name="phone" value="{{ old('phone', $consultant->phone) }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:border-ht-blue focus:bg-white focus:ring-0 transition-all font-semibold text-slate-700">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Biografia Curta</label>
                    <textarea name="bio" rows="4" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-transparent focus:border-ht-blue focus:bg-white focus:ring-0 transition-all font-semibold text-slate-700">{{ old('bio', $consultant->bio) }}</textarea>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <h4 class="text-sm font-bold text-ht-navy mb-4">Redes Sociais (Opcional)</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Instagram</label>
                            <input type="text" name="instagram" value="{{ old('instagram', $consultant->instagram) }}" class="w-full px-3 py-2 rounded-lg bg-slate-50 border-transparent focus:border-ht-blue focus:bg-white text-sm" placeholder="@usuario ou URL">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Facebook</label>
                            <input type="text" name="facebook" value="{{ old('facebook', $consultant->facebook) }}" class="w-full px-3 py-2 rounded-lg bg-slate-50 border-transparent focus:border-ht-blue focus:bg-white text-sm" placeholder="URL Perfil">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">LinkedIn</label>
                            <input type="text" name="linkedin" value="{{ old('linkedin', $consultant->linkedin) }}" class="w-full px-3 py-2 rounded-lg bg-slate-50 border-transparent focus:border-ht-blue focus:bg-white text-sm" placeholder="URL Perfil">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">TikTok</label>
                            <input type="text" name="tiktok" value="{{ old('tiktok', $consultant->tiktok) }}" class="w-full px-3 py-2 rounded-lg bg-slate-50 border-transparent focus:border-ht-blue focus:bg-white text-sm" placeholder="@usuario">
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Fotografia Atual</label>
                    @if($consultant->photo)
                        <div class="mb-4 w-24 h-24 rounded-full overflow-hidden border-2 border-slate-200">
                            <img src="{{ asset('storage/'.$consultant->photo) }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="relative w-full h-48 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 group hover:border-ht-blue hover:text-ht-blue transition-all cursor-pointer">
                        <input type="file" name="photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span class="text-xs font-bold uppercase tracking-wider">Alterar Imagem</span>
                    </div>
                </div>

                <div class="bg-slate-50 p-6 rounded-xl space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-bold text-slate-700">Ordem de Exibição</label>
                        <input type="number" name="order" value="{{ old('order', $consultant->order) }}" class="w-20 px-3 py-2 rounded-lg border-slate-200 text-center font-bold text-slate-700 focus:ring-0 focus:border-ht-blue">
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                        <label class="text-sm font-bold text-slate-700">Estado Ativo</label>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $consultant->is_active) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-ht-blue"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
            <button type="submit" class="bg-ht-navy hover:bg-ht-blue text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:shadow-glow transition-all transform hover:-translate-y-1">
                Atualizar Consultor
            </button>
        </div>
    </form>
</div>
@endsection