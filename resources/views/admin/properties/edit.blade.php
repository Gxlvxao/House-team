<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Imóvel | House Team Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    Visão Geral
                </a>
                <a href="{{ route('admin.properties.index') }}" class="flex items-center gap-3 px-4 py-3 bg-ht-blue text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 transition-all">
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
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-ht-navy mb-2 ml-1">Título</label>
                                <input type="text" name="title" value="{{ old('title', $property->title) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue transition-all">
                            </div>
                            
                            <div class="grid grid-cols-3 gap-6">
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
                        <div class="grid grid-cols-4 gap-6">
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
                                <select name="energy_rating" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-600 focus:outline-none focus:border-ht-blue focus:ring-1 focus:ring-ht-blue appearance-none transition-all">
                                    <option value="A+" {{ old('energy_rating', $property->energy_rating) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A" {{ old('energy_rating', $property->energy_rating) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('energy_rating', $property->energy_rating) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="C" {{ old('energy_rating', $property->energy_rating) == 'C' ? 'selected' : '' }}>C</option>
                                </select>
                            </div>
                        </div>
                    </div>

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
                                    <img src="{{ asset('storage/'.$property->cover_image) }}" class="w-full h-full object-cover">
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

                        @if($property->images && $property->images->count() > 0)
                            <div class="mb-8">
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-400 mb-4 ml-1">Galeria Atual (No Servidor)</label>
                                <div class="grid grid-cols-4 md:grid-cols-6 gap-3">
                                    @foreach($property->images as $img)
                                        <div class="relative h-20 rounded-xl overflow-hidden border border-slate-200 group">
                                            <img src="{{ asset('storage/'.$img->path) }}" class="w-full h-full object-cover grayscale-[20%] group-hover:grayscale-0 transition-all">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="p-6 bg-slate-50 border border-dashed border-slate-300 rounded-2xl">
                            <label class="block text-sm font-bold text-ht-navy mb-2 ml-1">Adicionar Novas Fotos (Acumulativo)</label>
                            <input type="file" id="gallery-input" name="gallery[]" multiple accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-ht-navy file:text-white hover:file:bg-slate-700 cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-2 ml-1 font-bold italic">As novas fotos selecionadas aparecerão abaixo para confirmação.</p>

                            <div id="gallery-preview" class="grid grid-cols-3 md:grid-cols-5 gap-4 mt-6">
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
            const previewContainer = document.getElementById('gallery-preview');
            const dt = new DataTransfer();

            input.addEventListener('change', function() {
                for(let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    dt.items.add(file);

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = "relative h-24 w-full rounded-xl overflow-hidden shadow-md border-2 border-white group animate-pulse-once";
                        
                        div.innerHTML = `
                            <img src="${e.target.result}" class="h-full w-full object-cover">
                            <button type="button" class="remove-btn absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                &times;
                            </button>
                        `;

                        div.querySelector('.remove-btn').addEventListener('click', function() {
                            div.remove();
                            removeFromDataTransfer(file);
                        });

                        previewContainer.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                }
                this.files = dt.files;
            });

            function removeFromDataTransfer(fileToRemove) {
                const newDt = new DataTransfer();
                for (let i = 0; i < dt.files.length; i++) {
                    if (dt.files[i] !== fileToRemove) {
                        newDt.items.add(dt.files[i]);
                    }
                }
                dt.items.clear();
                for (let i = 0; i < newDt.files.length; i++) {
                    dt.items.add(newDt.files[i]);
                }
                input.files = dt.files;
            }
        });
    </script>
</body>
</html>