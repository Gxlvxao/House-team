<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Imóvel | House Team Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Scripts de Terceiros - Ordem de carregamento crítica --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Montserrat', 'sans-serif'] },
                    colors: {
                        'ht-navy': '#0f172a',
                        'ht-blue': '#2563eb',
                        'ht-light': '#f8fafc',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-ht-navy text-white flex flex-col shadow-2xl z-20">
            <div class="p-8 text-center border-b border-white/10">
                <h1 class="font-black text-2xl tracking-tighter">HOUSE TEAM<span class="text-ht-blue">.</span></h1>
                <p class="text-[10px] uppercase tracking-widest text-slate-400 mt-2 font-bold">Admin Panel</p>
            </div>
            
            <nav class="flex-1 p-4 space-y-2 mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-white/10 hover:text-white rounded-xl text-sm font-bold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Visão Geral
                </a>
                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-4 py-3 bg-ht-blue text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Meus Imóveis
                </a>
            </nav>
        </aside>

        <main class="flex-1 p-8 md:p-12 overflow-y-auto bg-slate-50">
            <div class="max-w-5xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-3xl font-black text-ht-navy tracking-tight">Editar Imóvel</h2>
                        <p class="text-slate-500 text-sm mt-1 font-medium">Atualize os dados do imóvel selecionado.</p>
                    </div>
                    <a href="{{ route('admin.properties.index') }}" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-ht-blue transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Voltar
                    </a>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm" role="alert">
                        <p class="font-bold text-sm">Atenção:</p>
                        <ul class="list-disc list-inside text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6">Informações Básicas</h3>
                        <div class="grid grid-cols-1 gap-6">
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Título</label>
                                    <input type="text" name="title" value="{{ old('title', $property->title) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Ordem</label>
                                    <input type="number" name="order" value="{{ old('order', $property->order) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                                </div>
                            </div>

                            {{-- DROPWDOWN DE CONSULTORES BLINDADO --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-50">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Consultor Responsável</label>
                                    
                                    <div x-data="{ 
                                            open: false, 
                                            search: '', 
                                            selectedId: '{{ $property->consultant_id }}', 
                                            selectedName: '{{ $property->consultant ? $property->consultant->name : 'Selecione um Consultor' }}',
                                            options: {{ $consultants->map(function($c) {
                                                $photoPath = ($c->photo && file_exists(public_path('img/team/'.$c->photo))) 
                                                    ? asset('img/team/'.$c->photo) 
                                                    : 'https://ui-avatars.com/api/?name='.urlencode($c->name).'&color=7F9CF5&background=EBF4FF';
                                                
                                                return [
                                                    'id' => $c->id,
                                                    'name' => $c->name,
                                                    'photo' => $photoPath
                                                ];
                                            })->toJson() }}
                                         }" 
                                         class="relative">
                                        
                                        <input type="hidden" name="consultant_id" :value="selectedId">

                                        <button type="button" 
                                                @click="open = !open" 
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-left flex items-center justify-between focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                                            <span x-text="selectedName" :class="selectedId ? 'text-ht-navy' : 'text-slate-400'"></span>
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>

                                        <div x-show="open" 
                                             @click.away="open = false"
                                             class="absolute top-full left-0 w-full mt-2 bg-white border border-slate-100 rounded-xl shadow-xl max-h-60 overflow-y-auto z-50 p-2"
                                             style="display: none;">
                                            
                                            <div class="px-2 pb-2 mb-2 border-b border-slate-100">
                                                <input x-model="search" 
                                                       type="text" 
                                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-ht-blue" 
                                                       placeholder="Pesquisar nome...">
                                            </div>

                                            <template x-for="option in options.filter(i => i.name.toLowerCase().includes(search.toLowerCase()))" :key="option.id">
                                                <div @click="selectedId = option.id; selectedName = option.name; open = false"
                                                     class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors">
                                                    <img :src="option.photo" class="w-8 h-8 rounded-full object-cover border border-slate-200">
                                                    <span class="text-sm font-medium text-ht-navy" x-text="option.name"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Código CRM (Ref)</label>
                                    <input type="text" name="crm_code" value="{{ old('crm_code', $property->crm_code) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all" placeholder="Ex: IMO-1234">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Tipo</label>
                                    <select name="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                        <option value="Apartamento" {{ old('type', $property->type) == 'Apartamento' ? 'selected' : '' }}>Apartamento</option>
                                        <option value="Moradia" {{ old('type', $property->type) == 'Moradia' ? 'selected' : '' }}>Moradia / Villa</option>
                                        <option value="Terreno" {{ old('type', $property->type) == 'Terreno' ? 'selected' : '' }}>Terreno</option>
                                        <option value="Comercial" {{ old('type', $property->type) == 'Comercial' ? 'selected' : '' }}>Comercial</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Status</label>
                                    <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                        <option value="Venda" {{ old('status', $property->status) == 'Venda' ? 'selected' : '' }}>Venda</option>
                                        <option value="Arrendamento" {{ old('status', $property->status) == 'Arrendamento' ? 'selected' : '' }}>Arrendamento</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Preço (€)</label>
                                    <input type="number" name="price" value="{{ old('price', $property->price) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- LOCALIZAÇÃO E DETALHES --}}
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6">Localização e Detalhes</h3>
                        <div class="grid grid-cols-2 gap-6 mb-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Zona</label>
                                <input type="text" name="location" value="{{ old('location', $property->location) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Morada</label>
                                <input type="text" name="address" value="{{ old('address', $property->address) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Área (m²)</label>
                                <input type="number" name="area_gross" value="{{ old('area_gross', $property->area_gross) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Quartos</label>
                                <input type="number" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">WC</label>
                                <input type="number" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Energia</label>
                                <select name="energy_rating" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                                    <option value="A+" {{ old('energy_rating', $property->energy_rating) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A" {{ old('energy_rating', $property->energy_rating) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('energy_rating', $property->energy_rating) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('energy_rating', $property->energy_rating) == 'C' ? 'selected' : '' }}>C</option>
                                    <option value="D" {{ old('energy_rating', $property->energy_rating) == 'D' ? 'selected' : '' }}>D</option>
                                    <option value="E" {{ old('energy_rating', $property->energy_rating) == 'E' ? 'selected' : '' }}>E</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- COMODIDADES --}}
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6">Comodidades</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php
                                $features = [
                                    'has_pool' => 'Piscina',
                                    'has_garden' => 'Jardim',
                                    'has_lift' => 'Elevador',
                                    'has_terrace' => 'Terraço',
                                    'has_air_conditioning' => 'Ar Condicionado',
                                    'is_furnished' => 'Mobilado',
                                    'is_kitchen_equipped' => 'Cozinha Equipada'
                                ];
                            @endphp
                            @foreach($features as $field => $label)
                                <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg cursor-pointer hover:bg-slate-100 transition-all">
                                    <input type="checkbox" name="{{ $field }}" {{ old($field, $property->$field) ? 'checked' : '' }} class="accent-ht-blue w-5 h-5 rounded">
                                    <span class="text-sm font-medium">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- MÍDIA E GALERIA UNIFICADA --}}
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <h3 class="text-lg font-bold text-ht-navy mb-6 text-center">Mídia e Imagens</h3>
                        
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">WhatsApp</label>
                                <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $property->whatsapp_number) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Tour YouTube</label>
                                <input type="url" name="video_url" value="{{ old('video_url', $property->video_url) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                        </div>

                        <div class="flex items-center gap-6 mb-8 p-6 bg-slate-50 border border-slate-200 rounded-2xl">
                            <div class="w-24 h-24 bg-slate-200 rounded-xl overflow-hidden shadow-inner flex-shrink-0">
                                @if($property->cover_image)
                                    <img src="{{ asset('storage/'.$property->cover_image) }}" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x400?text=Sem+Capa'">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-[10px] font-bold">SEM CAPA</div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-bold text-ht-navy mb-2">Substituir Foto de Capa</label>
                                <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-blue file:text-white hover:file:bg-blue-700 cursor-pointer transition-all">
                            </div>
                        </div>

                        <hr class="mb-8 border-slate-100">

                        {{-- GALERIA UNIFICADA (ARRABSTÁVEL) --}}
                        <div class="p-6 bg-slate-50 border border-dashed border-slate-300 rounded-2xl">
                            <label class="block text-sm font-bold text-ht-navy mb-2 ml-1">Galeria de Fotos (Arraste para reordenar)</label>
                            
                            <input type="file" id="gallery-input" name="gallery[]" multiple accept="image/*" class="mb-6 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-navy file:text-white hover:file:bg-slate-700 cursor-pointer">

                            {{-- Input oculto para enviar a ordem final dos IDs existentes --}}
                            <input type="hidden" name="images_order" id="images_order">

                            <div id="unified-gallery" class="grid grid-cols-3 md:grid-cols-5 gap-4">
                                {{-- FOTOS EXISTENTES --}}
                                @if($property->images)
                                    @foreach($property->images->sortBy('order') as $img)
                                        <div class="relative h-24 w-full rounded-xl overflow-hidden shadow-sm border border-slate-200 group cursor-move bg-white" 
                                             data-id="{{ $img->id }}" 
                                             data-type="existing">
                                            <img src="{{ asset('storage/'.$img->path) }}" class="h-full w-full object-cover pointer-events-none" onerror="this.parentElement.remove();">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                            <button type="button" onclick="this.parentElement.remove(); updateGalleryOrder();" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] font-bold shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                                &times;
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                                {{-- NOVAS FOTOS ENTRARÃO AQUI VIA JS --}}
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
                        <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Descrição</label>
                        <textarea name="description" rows="6" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all resize-y">{{ old('description', $property->description) }}</textarea>
                    </div>

                    <div class="flex justify-end pb-12">
                        <button type="submit" class="bg-ht-blue text-white px-10 py-4 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg shadow-blue-500/20 transform active:scale-95">
                            Guardar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('gallery-input');
            const unifiedContainer = document.getElementById('unified-gallery');
            const orderInput = document.getElementById('images_order');

            // 1. Inicializa SortableJS no Container Unificado
            if(typeof Sortable !== 'undefined' && unifiedContainer) {
                new Sortable(unifiedContainer, {
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function() {
                        updateGalleryOrder();
                    }
                });
            }

            // 2. Lógica para Adicionar Novas Fotos na mesma grade
            input.addEventListener('change', function() {
                Array.from(this.files).forEach(file => {
                    const div = document.createElement('div');
                    div.className = "relative h-24 w-full rounded-xl overflow-hidden shadow-sm border border-slate-200 group cursor-move hover:border-ht-blue transition-all bg-white";
                    div.file = file; 
                    div.setAttribute('data-type', 'new');

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        div.innerHTML = `
                            <img src="${e.target.result}" class="h-full w-full object-cover pointer-events-none">
                            <button type="button" class="remove-btn absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] font-bold shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                &times;
                            </button>
                        `;

                        div.querySelector('.remove-btn').addEventListener('click', function() {
                            div.remove();
                            updateGalleryOrder();
                        });
                    };
                    reader.readAsDataURL(file);
                    unifiedContainer.appendChild(div);
                });
                updateGalleryOrder();
            });

            /**
             * Sincroniza Ordem Visual com Inputs Reais
             */
            function updateGalleryOrder() {
                const dt = new DataTransfer();
                const existingIds = [];
                const items = unifiedContainer.children;

                for (let i = 0; i < items.length; i++) {
                    const item = items[i];
                    
                    // Se for imagem existente, guarda o ID na ordem
                    if (item.getAttribute('data-type') === 'existing') {
                        existingIds.push(item.getAttribute('data-id'));
                    }
                    
                    // Se for nova, adiciona ao DataTransfer do file input
                    if (item.getAttribute('data-type') === 'new' && item.file) {
                        dt.items.add(item.file);
                    }
                }

                input.files = dt.files;
                orderInput.value = existingIds.join(',');
            }

            // Inicializa a ordem no carregamento
            updateGalleryOrder();
        });
    </script>
</body>
</html>