@extends('layouts.app')

@section('content')

{{-- CONFIGURAÇÃO DE ESTILO --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap');
    
    .font-serif { font-family: 'Playfair Display', serif; }
    .font-sans { font-family: 'Lato', sans-serif; }
    
    /* Padrão sutil de papel/pedra no fundo */
    .bg-texture {
        background-color: #fafaf9;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d6d3d1' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
</style>

<div class="bg-texture min-h-screen text-stone-800 font-sans selection:bg-amber-200 selection:text-stone-900">

    {{-- 1. HERO SECTION: Clássica e Imponente --}}
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 px-6 border-b border-stone-200">
        <div class="container mx-auto max-w-6xl">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                
                {{-- Coluna Texto (Elegância Sóbria) --}}
                <div class="lg:col-span-5 order-2 lg:order-1 text-center lg:text-left">
                    <p class="text-amber-700 font-bold tracking-[0.2em] text-xs uppercase mb-6">
                        {{ $consultant->role ?? 'Imobiliário de Prestígio' }}
                    </p>
                    
                    <h1 class="text-5xl lg:text-7xl font-serif font-medium text-stone-900 leading-tight mb-8">
                        {{ $consultant->name }}
                    </h1>
                    
                    <div class="w-20 h-1 bg-amber-700 mb-8 mx-auto lg:mx-0"></div>

                    <p class="text-stone-600 text-lg leading-relaxed mb-10 font-light">
                        A tradição de bem servir. Especialista em encontrar propriedades com história e caráter. Um acompanhamento pautado pelo rigor, discrição e excelência.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-6 justify-center lg:justify-start">
                        <a href="#portfolio" class="px-8 py-4 bg-stone-900 text-white font-serif italic text-lg hover:bg-amber-800 transition-colors duration-500 shadow-xl shadow-stone-300">
                            Ver Coleção
                        </a>
                        <a href="#contact" class="px-8 py-4 border border-stone-400 text-stone-600 font-serif italic text-lg hover:bg-white hover:text-stone-900 transition-colors duration-300">
                            Contactar
                        </a>
                    </div>
                </div>

                {{-- Coluna Imagem (Estilo Moldura/Quadro) --}}
                <div class="lg:col-span-7 order-1 lg:order-2 relative">
                    {{-- Moldura Decorativa --}}
                    <div class="absolute inset-0 border-2 border-amber-700/30 transform translate-x-6 translate-y-6 z-0 hidden md:block"></div>
                    
                    <div class="relative z-10 overflow-hidden shadow-2xl">
                        {{-- 
                            ATENÇÃO AQUI: 
                            Caminho atualizado para a foto específica. 
                            Nota: Evite espaços em nomes de arquivos (use team_margarida.png se possível),
                            mas o browser geralmente entende com espaço também.
                        --}}
                        <<div class="relative z-10 overflow-hidden shadow-2xl">
    {{-- CAMINHO CORRIGIDO: pasta 'team' dentro de 'img' --}}
    <img src="{{ asset('img/team/margarida.png') }}" 
         alt="{{ $consultant->name }}" 
         class="w-full h-auto object-cover grayscale-[20%] sepia-[10%] hover:grayscale-0 transition duration-1000 ease-out">
</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. ESTATÍSTICAS (Estilo Régua/Minimalista) --}}
    <section class="py-16 border-b border-stone-200 bg-white">
        <div class="container mx-auto max-w-6xl px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center">
                <div class="space-y-2">
                    <span class="block text-4xl font-serif text-stone-900">15</span>
                    <span class="block text-xs font-bold tracking-widest text-amber-700 uppercase">Anos de Mercado</span>
                </div>
                <div class="space-y-2">
                    <span class="block text-4xl font-serif text-stone-900">1M+</span>
                    <span class="block text-xs font-bold tracking-widest text-amber-700 uppercase">Volume Transacionado</span>
                </div>
                <div class="space-y-2">
                    <span class="block text-4xl font-serif text-stone-900">1000</span>
                    <span class="block text-xs font-bold tracking-widest text-amber-700 uppercase">Famílias Felizes</span>
                </div>
                <div class="space-y-2">
                    <span class="block text-4xl font-serif text-stone-900">50</span>
                    <span class="block text-xs font-bold tracking-widest text-amber-700 uppercase">Imóveis em Carteira</span>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. A CARTA (Bio) --}}
    <section class="py-24 px-6 bg-stone-100">
        <div class="container mx-auto max-w-4xl text-center">
            <i class="fas fa-quote-left text-4xl text-stone-300 mb-8"></i>
            
            <h2 class="text-3xl md:text-4xl font-serif text-stone-900 mb-8 italic">
                "O imobiliário não é apenas sobre tijolo e pedra. É sobre património, legado e o futuro da sua família."
            </h2>

            <div class="prose prose-lg prose-stone mx-auto text-stone-600 mb-12">
                @if($consultant->bio)
                    {!! nl2br(e($consultant->bio)) !!}
                @else
                    <p>Com uma carreira construída sobre pilares de honestidade e trabalho árduo, dedico-me a valorizar o património dos meus clientes. Cada negócio é tratado com a confidencialidade e o respeito que o seu investimento merece.</p>
                @endif
            </div>

            {{-- Assinatura (Visual) --}}
            <div class="font-serif text-2xl text-stone-900 italic">
                {{ $consultant->name }}
            </div>
        </div>
    </section>

    {{-- 4. PORTFÓLIO (Estilo Galeria de Arte) --}}
    <section id="portfolio" class="py-24 px-6">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center mb-20">
                <span class="text-amber-700 font-bold tracking-widest text-xs uppercase">Oportunidades Únicas</span>
                <h2 class="text-4xl font-serif text-stone-900 mt-4">A Seleção</h2>
            </div>

            @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
                    @foreach($properties as $property)
                        <a href="{{ route('consultant.property', ['domain' => request()->getHost(), 'property' => $property->slug]) }}" class="group block">
                            {{-- Card Imagem --}}
                            <div class="relative overflow-hidden mb-6 shadow-lg border-8 border-white bg-white">
                                <div class="aspect-w-4 aspect-h-3">
                                    <img src="{{ $property->cover_image ? asset('storage/' . $property->cover_image) : asset('img/porto.jpg') }}" 
                                         class="w-full h-80 object-cover transform group-hover:scale-105 transition duration-1000 ease-in-out grayscale-[10%] group-hover:grayscale-0">
                                </div>
                                
                                {{-- Etiqueta Sóbria --}}
                                <div class="absolute top-0 right-0 bg-white px-4 py-2">
                                    <span class="text-xs font-bold tracking-widest text-stone-900 uppercase">
                                        {{ __('property_status.' . \Illuminate\Support\Str::slug($property->status)) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Card Texto --}}
                            <div class="text-center px-4">
                                <p class="text-amber-700 text-xs font-bold tracking-widest uppercase mb-2">
                                    {{ $property->location }}
                                </p>
                                <h3 class="text-2xl font-serif text-stone-900 mb-2 group-hover:text-amber-800 transition-colors">
                                    {{ $property->title }}
                                </h3>
                                <p class="text-stone-500 font-light italic mb-4">
                                    {{ $property->type }} • {{ $property->bedrooms }} Quartos
                                </p>
                                <div class="inline-block border-t border-stone-300 pt-3 px-6">
                                    <span class="text-xl font-serif text-stone-900">
                                        {{ $property->price ? number_format($property->price, 0, ',', '.') . ' €' : 'Sob Consulta' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="py-24 text-center border border-stone-200 bg-stone-50">
                    <p class="text-stone-500 font-serif italic text-xl">De momento, o portfólio está a ser atualizado com novas oportunidades exclusivas.</p>
                </div>
            @endif
            
            <div class="text-center mt-20">
                <a href="{{ route('portfolio') }}" class="inline-block border-b border-stone-900 pb-1 text-stone-900 font-bold uppercase tracking-widest text-xs hover:text-amber-700 hover:border-amber-700 transition-all">
                    Ver Inventário Completo
                </a>
            </div>
        </div>
    </section>

    {{-- 5. CONTACTO (Estilo Convite Formal) --}}
    <section id="contact" class="py-24 bg-stone-900 text-stone-200 relative overflow-hidden">
        {{-- Detalhe Dourado --}}
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-transparent via-amber-600 to-transparent"></div>

        <div class="container mx-auto max-w-4xl px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-serif text-white mb-6">Agende uma Reunião</h2>
                <p class="text-stone-400 text-lg font-light">Estou à sua disposição para analisar o seu imóvel ou encontrar o investimento certo.</p>
            </div>

            <div class="bg-white text-stone-900 p-8 lg:p-12 shadow-2xl">
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="hidden" name="consultant_id" value="{{ $consultant->id }}">
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-stone-500">Nome Completo</label>
                            <input type="text" name="name" class="w-full border-b border-stone-300 py-2 focus:outline-none focus:border-amber-700 transition-colors bg-transparent placeholder-stone-300" placeholder="Ex: João Silva" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-stone-500">Contacto Telefónico</label>
                            <input type="tel" name="phone" class="w-full border-b border-stone-300 py-2 focus:outline-none focus:border-amber-700 transition-colors bg-transparent placeholder-stone-300" placeholder="+351 ...">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-stone-500">Email Preferencial</label>
                        <input type="email" name="email" class="w-full border-b border-stone-300 py-2 focus:outline-none focus:border-amber-700 transition-colors bg-transparent placeholder-stone-300" placeholder="email@exemplo.com" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-stone-500">Assunto</label>
                        <textarea name="message" rows="3" class="w-full border-b border-stone-300 py-2 focus:outline-none focus:border-amber-700 transition-colors bg-transparent placeholder-stone-300 resize-none" required placeholder="Gostaria de obter mais informações..."></textarea>
                    </div>

                    <div class="text-center pt-6">
                        <button type="submit" class="px-10 py-4 bg-amber-800 text-white font-serif tracking-wide text-lg hover:bg-stone-900 transition-all duration-500 shadow-lg">
                            Enviar Mensagem
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-16 grid md:grid-cols-3 gap-8 text-center border-t border-stone-800 pt-12">
                <div>
                    <i class="fas fa-map-marker-alt text-amber-700 mb-4 text-xl"></i>
                    <p class="text-stone-300 font-serif">Edifício Portucalle<br>Lisboa, Portugal</p>
                </div>
                <div>
                    <i class="fas fa-phone text-amber-700 mb-4 text-xl"></i>
                    <a href="tel:{{ $consultant->phone }}" class="block text-stone-300 font-serif hover:text-white transition">{{ $consultant->phone }}</a>
                </div>
                <div>
                    <i class="fas fa-envelope text-amber-700 mb-4 text-xl"></i>
                    <a href="mailto:{{ $consultant->email }}" class="block text-stone-300 font-serif hover:text-white transition">{{ $consultant->email }}</a>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-stone-950 text-stone-500 py-8 text-center text-xs border-t border-stone-900">
        <div class="container mx-auto px-6">
            <p>&copy; {{ date('Y') }} {{ $consultant->name }}. Consultoria de Excelência.</p>
            <p class="mt-2 opacity-50">Powered by <span class="font-bold text-stone-400">House Team</span></p>
        </div>
    </footer>

</div>

@endsection