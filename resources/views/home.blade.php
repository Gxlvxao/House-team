@extends('layouts.app')

@section('content')

<section class="relative h-screen w-full flex items-center justify-center overflow-hidden bg-ht-navy">
    <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover opacity-60">
        <source src="{{ asset('video/header_bg.mp4') }}" type="video/mp4">
    </video>
    
    <div class="absolute inset-0 bg-gradient-to-b from-ht-navy/80 via-ht-navy/20 to-ht-navy"></div>

    {{-- LOGO RESTAURADA AQUI --}}
    <div class="absolute top-6 left-6 md:top-10 md:left-10 z-30" data-aos="fade-right">
        <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-full flex items-center justify-center p-4 shadow-2xl ring-4 ring-white/30 backdrop-blur-md">
            <img src="{{ asset('img/logo.png') }}" alt="House Team Logo" class="w-full h-full object-contain">
        </div>
    </div>

    <div class="relative z-10 container mx-auto px-6 text-center" data-aos="fade-up">
        
        <div x-data="{ texts: ['INTEGRIDADE', 'GRATIDÃO', 'LIBERDADE', 'CONFIANÇA'], idx: 0 }" 
             x-init="setInterval(() => idx = (idx + 1) % texts.length, 2500)"
             class="h-8 mb-6 flex justify-center items-center overflow-hidden">
            <template x-for="(text, index) in texts">
                <span x-show="idx === index" 
                      x-transition:enter="transition ease-out duration-500"
                      x-transition:enter-start="opacity-0 translate-y-full"
                      x-transition:enter-end="opacity-100 translate-y-0"
                      class="text-ht-accent font-black tracking-[0.4em] text-sm md:text-base absolute">
                    <span x-text="text"></span>
                </span>
            </template>
        </div>

        <h1 class="text-6xl md:text-9xl font-black text-white leading-none tracking-tighter mb-10 drop-shadow-2xl">
            HOUSE<br><span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400">TEAM</span>
        </h1>

        <div class="flex flex-col md:flex-row gap-6 justify-center items-center mt-12">
            <a href="#valuation" class="px-10 py-4 bg-ht-accent text-white font-bold uppercase tracking-widest text-xs rounded-full hover:bg-white hover:text-ht-accent transition-all shadow-[0_0_30px_-5px_rgba(37,99,235,0.6)] transform hover:scale-105">
                Avaliar Meu Imóvel
            </a>
            <a href="{{ route('portfolio') }}" class="px-10 py-4 border border-white/30 text-white font-bold uppercase tracking-widest text-xs rounded-full hover:bg-white/10 backdrop-blur-md transition-all">
                Ver Portfólio
            </a>
        </div>
    </div>

    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce text-white/50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
    </div>
</section>

<section class="py-32 bg-slate-50 relative z-20">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16">
            <div>
                <span class="text-ht-accent font-bold text-xs uppercase tracking-widest mb-2 block">Oportunidades</span>
                <h3 class="text-4xl font-black text-ht-navy tracking-tight">Imóveis Recentes</h3>
            </div>
            <a href="{{ route('portfolio') }}" class="group flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-ht-accent transition-colors mt-6 md:mt-0">
                Ver Portfólio Completo
                <span class="bg-slate-200 rounded-full w-6 h-6 flex items-center justify-center group-hover:bg-ht-accent group-hover:text-white transition-colors">→</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($properties as $property)
                <a href="{{ route('properties.show', $property) }}" class="group relative block h-[500px] rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                    <img src="{{ $property->cover_image ? asset('storage/' . $property->cover_image) : asset('img/porto.jpg') }}" 
                         class="absolute inset-0 w-full h-full object-cover transition duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-ht-navy via-ht-navy/20 to-transparent opacity-90 group-hover:opacity-80 transition duration-500"></div>
                    
                    <div class="absolute top-4 right-4 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20">
                        <span class="text-[10px] font-bold text-white uppercase tracking-wider">{{ $property->type }}</span>
                    </div>

                    <div class="absolute bottom-0 left-0 w-full p-8 text-white translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-2xl font-black">{{ $property->price ? '€ ' . number_format($property->price, 0, ',', '.') : 'Consulte' }}</span>
                            <span class="text-[10px] font-bold bg-ht-accent px-2 py-1 rounded uppercase tracking-wider">{{ $property->status }}</span>
                        </div>
                        <h4 class="text-lg font-bold mb-2 line-clamp-1 leading-tight text-slate-200 group-hover:text-white">{{ $property->title }}</h4>
                        <p class="text-xs text-slate-400 font-medium mb-6 flex items-center gap-2">
                            <svg class="w-3 h-3 text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $property->location }}
                        </p>
                        <div class="flex gap-4 pt-4 border-t border-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                            <span class="text-xs font-bold text-slate-300">🛏 {{ $property->bedrooms }} Quartos</span>
                            <span class="text-xs font-bold text-slate-300">📐 {{ number_format($property->area_gross, 0) }} m²</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 py-20 text-center text-slate-400 font-medium bg-white rounded-3xl border border-dashed border-slate-200">
                    A carregar oportunidades...
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-24 bg-white overflow-hidden">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-20">
            
            <div class="lg:w-1/2 relative order-2 lg:order-1" data-aos="fade-right">
                <div class="absolute inset-0 bg-ht-navy transform -rotate-3 rounded-3xl translate-x-4 translate-y-4 opacity-10"></div>
                <img src="{{ asset('img/reuniao.jpg') }}" 
                     alt="House Team Equipa" 
                     class="relative rounded-3xl shadow-2xl w-full h-[500px] object-cover grayscale hover:grayscale-0 transition duration-700">
            </div>

            <div class="lg:w-1/2 order-1 lg:order-2" data-aos="fade-left">
                <span class="text-ht-accent font-black text-xs uppercase tracking-widest mb-6 block">Sobre Nós</span>
                <h2 class="text-5xl font-black text-ht-navy mb-8 leading-tight tracking-tight">
                    House Team<br>Consultores
                </h2>
                <div class="space-y-6 text-slate-500 text-lg leading-relaxed font-medium">
                    <p>
                        Somos uma equipa experiente de Consultores Imobiliários da RE/MAX, pertencente ao Expogroup, fundada sob o conceito de <span class="text-ht-navy font-bold">Broker Empreendedor</span>.
                    </p>
                    <p>
                        Estamos totalmente disponíveis para acompanhá-lo em todas as etapas do seu projeto, seja na compra, venda ou arrendamento do seu futuro imóvel. Nossa missão é oferecer um serviço personalizado e de alta qualidade.
                    </p>
                </div>
                
                <div class="mt-12">
                    <a href="{{ route('contact') }}" class="px-8 py-4 bg-ht-navy text-white text-xs font-bold uppercase tracking-widest rounded-lg hover:bg-ht-accent transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1">
                        Fale Conosco
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- SEÇÃO DE REVIEWS (NOVA) --}}
<section class="py-24 bg-slate-50 border-t border-slate-100">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-ht-accent font-black text-xs uppercase tracking-widest mb-2 block">Testemunhos</span>
            <h3 class="text-3xl font-black text-ht-navy mb-4">O que dizem os nossos clientes</h3>
            <p class="text-slate-500 max-w-xl mx-auto">A sua satisfação é a nossa maior prioridade.</p>
        </div>
        
        <div class="w-full max-w-6xl mx-auto">
            <script type='text/javascript' src='https://reputationhub.site/reputation/assets/review-widget.js'></script>
            <iframe class='lc_reviews_widget' src='https://reputationhub.site/reputation/widgets/review_widget/pNqZN0PDS8KBp1b3Uk13?widgetId=694556a169efd2edea08e932' frameborder='0' scrolling='no' style='min-width: 100%; width: 100%;'></iframe>
        </div>
    </div>
</section>

<section id="valuation" class="py-32 bg-ht-navy relative overflow-hidden">
    <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(#2563eb 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-ht-accent/10 to-transparent pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-6xl font-black text-white mb-6">Quanto vale a sua casa?</h2>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">
                Preencha os detalhes e receba uma análise de mercado precisa.
            </p>
        </div>

        <div x-data="{ 
            step: 1,
            type: 'apartamento',
            bedrooms: 2,
            bathrooms: 1,
            garages: 0, 
            features: [],
            condition: ''
        }" class="max-w-4xl mx-auto bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/10">
            
            <form action="{{ route('contact') }}" method="POST">
                @csrf
                <input type="hidden" name="subject" value="Avaliação Detalhada de Imóvel">
                
                <div class="bg-slate-50 px-8 py-4 border-b border-slate-100 flex justify-between items-center">
                    <div class="flex gap-2">
                        <div class="h-2 rounded-full transition-all duration-500" :class="step >= 1 ? 'w-8 bg-ht-accent' : 'w-2 bg-slate-200'"></div>
                        <div class="h-2 rounded-full transition-all duration-500" :class="step >= 2 ? 'w-8 bg-ht-accent' : 'w-2 bg-slate-200'"></div>
                        <div class="h-2 rounded-full transition-all duration-500" :class="step >= 3 ? 'w-8 bg-ht-accent' : 'w-2 bg-slate-200'"></div>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Passo <span x-text="step"></span>/3</span>
                </div>

                <div class="p-8 md:p-12">
                    
                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0">
                        <h3 class="text-2xl font-bold text-ht-navy mb-8">Detalhes da Propriedade</h3>
                        
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <label class="cursor-pointer">
                                <input type="radio" name="property_type" value="Casa" class="peer sr-only" @click="type = 'casa'">
                                <div class="p-6 rounded-2xl border-2 border-slate-100 text-center peer-checked:border-ht-accent peer-checked:bg-blue-50 transition-all hover:border-slate-300">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 peer-checked:text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    <span class="font-bold text-slate-600 peer-checked:text-ht-navy">Casa</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="property_type" value="Apartamento" class="peer sr-only" @click="type = 'apartamento'" checked>
                                <div class="p-6 rounded-2xl border-2 border-slate-100 text-center peer-checked:border-ht-accent peer-checked:bg-blue-50 transition-all hover:border-slate-300">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 peer-checked:text-ht-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="font-bold text-slate-600 peer-checked:text-ht-navy">Apartamento</span>
                                </div>
                            </label>
                        </div>

                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Ano Construção</label>
                                <input type="number" name="year" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium focus:ring-2 focus:ring-ht-accent outline-none" placeholder="Ex: 2010">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Área (m²)</label>
                                <input type="number" name="area" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium focus:ring-2 focus:ring-ht-accent outline-none" placeholder="Ex: 120">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Quartos</label>
                                <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl p-2">
                                    <button type="button" @click="bedrooms = Math.max(0, bedrooms - 1)" class="w-8 h-8 rounded-lg bg-white shadow text-ht-navy hover:bg-slate-100">-</button>
                                    <input type="hidden" name="bedrooms" :value="bedrooms">
                                    <span class="font-bold text-lg text-ht-navy" x-text="bedrooms"></span>
                                    <button type="button" @click="bedrooms++" class="w-8 h-8 rounded-lg bg-ht-accent text-white shadow hover:bg-blue-600">+</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Casas de banho</label>
                                <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl p-2">
                                    <button type="button" @click="bathrooms = Math.max(0, bathrooms - 1)" class="w-8 h-8 rounded-lg bg-white shadow text-ht-navy hover:bg-slate-100">-</button>
                                    <input type="hidden" name="bathrooms" :value="bathrooms">
                                    <span class="font-bold text-lg text-ht-navy" x-text="bathrooms"></span>
                                    <button type="button" @click="bathrooms++" class="w-8 h-8 rounded-lg bg-ht-accent text-white shadow hover:bg-blue-600">+</button>
                                </div>
                            </div>
                        </div>

                        {{-- [NOVO] SECÇÃO DE ESTACIONAMENTO --}}
                        <div class="grid grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Tipo de Garagem</label>
                                <div class="relative">
                                    <select name="parking_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 font-medium focus:ring-2 focus:ring-ht-accent outline-none appearance-none text-slate-600">
                                        <option value="">Selecione</option>
                                        <option value="Garagem (Box)">Garagem (Box)</option>
                                        <option value="Lugar de Parqueamento">Lugar de Parqueamento</option>
                                        <option value="Estacionamento Exterior">Estacionamento Exterior</option>
                                        <option value="Garagem Dupla">Garagem Dupla</option>
                                    </select>
                                     <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Nº Lugares</label>
                                <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl p-2">
                                    <button type="button" @click="garages = Math.max(0, garages - 1)" class="w-8 h-8 rounded-lg bg-white shadow text-ht-navy hover:bg-slate-100">-</button>
                                    <input type="hidden" name="garages" :value="garages">
                                    <span class="font-bold text-lg text-ht-navy" x-text="garages"></span>
                                    <button type="button" @click="garages++" class="w-8 h-8 rounded-lg bg-ht-accent text-white shadow hover:bg-blue-600">+</button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                        <h3 class="text-2xl font-bold text-ht-navy mb-6">Características & Condição</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-8">
                            @foreach(['Terraço', 'Mobilado', 'Piscina', 'Estacionamento', 'Jardim', 'Condomínio fechado', 'Boas vistas', 'Ar condicionado', 'Aquecimento', 'Domótica'] as $feature)
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="features[]" value="{{ $feature }}" class="accent-ht-accent w-4 h-4 rounded">
                                    <span class="text-xs font-bold text-slate-600">{{ $feature }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="space-y-3">
                            <p class="text-xs font-bold uppercase tracking-wide text-slate-500 mb-2">Estado de Conservação</p>
                            @foreach(['Novo/Renovado (Design)', 'Bons Acabamentos', 'Bom Estado', 'Pequena Reforma', 'Reforma Completa'] as $cond)
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-ht-accent/30 cursor-pointer group">
                                    <input type="radio" name="condition" value="{{ $cond }}" class="accent-ht-accent w-4 h-4">
                                    <span class="text-sm font-medium text-slate-600 group-hover:text-ht-navy">{{ $cond }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                        <h3 class="text-2xl font-bold text-ht-navy mb-6">Os seus dados</h3>
                        
                        <div class="space-y-4">
                            <input type="text" name="address" placeholder="Morada do Imóvel (Rua, Cidade)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-ht-accent outline-none font-medium">
                            
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="name" placeholder="Seu Nome" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-ht-accent outline-none font-medium">
                                <input type="email" name="email" placeholder="Seu Email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-ht-accent outline-none font-medium">
                            </div>

                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                                <p class="text-xs font-bold text-ht-navy mb-2">É o proprietário?</p>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="is_owner" value="Sim" class="accent-ht-accent"> <span class="text-sm">Sim</span></label>
                                    <label class="flex items-center gap-2 cursor-pointer"><input type="radio" name="is_owner" value="Não" class="accent-ht-accent"> <span class="text-sm">Não</span></label>
                                </div>
                            </div>

                            <input type="number" name="estimated_value" placeholder="Valor Estimado (€) - Opcional" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-ht-accent outline-none font-medium">

                            <label class="flex items-start gap-2 mt-4 cursor-pointer">
                                <input type="checkbox" required class="accent-ht-accent mt-1">
                                <span class="text-[10px] text-slate-400 leading-tight">Aceito os Termos e Condições e a Política de Privacidade.</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" @click="step--" x-show="step > 1" class="text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-ht-navy">← Voltar</button>
                        <div x-show="step === 1"></div> <button type="button" @click="step++" x-show="step < 3" class="bg-ht-navy text-white px-8 py-3 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-ht-accent transition shadow-lg">
                            Próximo Passo
                        </button>
                        
                        <button type="submit" x-show="step === 3" class="bg-ht-accent text-white px-10 py-3 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-blue-600 transition shadow-lg shadow-blue-500/30" style="display: none;">
                            Receber Avaliação
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h3 class="text-3xl font-black text-ht-navy mb-4">Ferramentas Inteligentes</h3>
            <p class="text-slate-500 max-w-xl mx-auto">Tome decisões financeiras informadas com os nossos simuladores exclusivos.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="{{ route('tools.credit') }}" class="bg-slate-50 p-10 rounded-3xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 group text-center">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-ht-blue mb-6 mx-auto shadow-sm group-hover:bg-ht-accent group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-xl font-bold text-ht-navy mb-2">Crédito Habitação</h4>
                <p class="text-sm text-slate-400 font-medium">Calcule a sua prestação mensal.</p>
            </a>
            
            <a href="{{ route('tools.gains') }}" class="bg-slate-50 p-10 rounded-3xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 group text-center">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-ht-blue mb-6 mx-auto shadow-sm group-hover:bg-ht-accent group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h4 class="text-xl font-bold text-ht-navy mb-2">Mais-Valias</h4>
                <p class="text-sm text-slate-400 font-medium">Estime o imposto sobre a venda.</p>
            </a>

            <a href="{{ route('tools.imt') }}" class="bg-slate-50 p-10 rounded-3xl hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-slate-100 group text-center">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-ht-blue mb-6 mx-auto shadow-sm group-hover:bg-ht-accent group-hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <h4 class="text-xl font-bold text-ht-navy mb-2">Simulador IMT</h4>
                <p class="text-sm text-slate-400 font-medium">Custos de aquisição e escritura.</p>
            </a>
        </div>
    </div>
</section>

@endsection