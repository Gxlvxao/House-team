@extends('layouts.app')

@section('content')

{{-- CONFIGURAÇÃO DE ESTILO E FONTES --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap');
    
    .font-serif { font-family: 'Playfair Display', serif; }
    .font-sans { font-family: 'Lato', sans-serif; }
    
    .bg-texture {
        background-color: #fafaf9;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23d6d3d1' fill-opacity='0.15'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
</style>

<div class="bg-texture min-h-screen text-stone-800 font-sans selection:bg-amber-200 selection:text-stone-900">

    {{-- 1. HERO SECTION (ID ADICIONADO: home) --}}
    <section id="home" class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 px-6 border-b border-stone-200">
        <div class="container mx-auto max-w-6xl">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                
                {{-- Texto --}}
                <div class="lg:col-span-5 order-2 lg:order-1 text-center lg:text-left">
                    {{-- LOGO DA MARGARIDA --}}
                    <div class="mb-8 flex justify-center lg:justify-start">
                        <img src="{{ asset('img/logos/casaacasa.png') }}" alt="Casa a Casa" class="h-16 md:h-20 opacity-90" 
                             onerror="this.style.display='none';"> 
                    </div>

                    <p class="text-amber-700 font-bold tracking-[0.2em] text-xs uppercase mb-4">
                        {{ __('consultant_lp.hero_subtitle') }}
                    </p>
                    
                    <h1 class="text-5xl lg:text-7xl font-serif font-medium text-stone-900 leading-tight mb-6">
                        {{ $consultant->name }}
                    </h1>
                    
                    <div class="w-20 h-1 bg-amber-700 mb-8 mx-auto lg:mx-0"></div>

                    <p class="text-stone-600 text-lg leading-relaxed mb-10 font-light">
                        {{ __('consultant_lp.hero_desc') }}
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start items-center">
                        <a href="#portfolio" class="px-8 py-4 border border-stone-800 text-stone-900 font-serif italic text-lg hover:bg-stone-900 hover:text-white transition-all duration-500 min-w-[200px]">
                            {{ __('consultant_lp.btn_collection') }}
                        </a>
                        
                        <a href="#contact" class="px-8 py-4 bg-amber-700 text-white font-serif italic text-lg hover:bg-amber-800 transition-colors duration-300 shadow-xl shadow-amber-900/20 transform hover:-translate-y-1 min-w-[200px]">
                            {{ __('consultant_lp.btn_contact') }}
                        </a>
                    </div>
                </div>

                {{-- Imagem Principal --}}
                <div class="lg:col-span-7 order-1 lg:order-2 relative">
                    <div class="absolute inset-0 border-2 border-amber-700/30 transform translate-x-6 translate-y-6 z-0 hidden md:block"></div>
                    <div class="relative z-10 overflow-hidden shadow-2xl">
                        <img src="{{ $consultant->image_url }}" 
                             alt="{{ $consultant->name }}" 
                             class="w-full h-auto object-cover grayscale-[10%] sepia-[5%] hover:grayscale-0 transition duration-1000 ease-out">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. PORQUÊ TRABALHAR COMIGO (ID ADICIONADO: about) --}}
    <section id="about" class="py-24 px-6 bg-white">
        <div class="container mx-auto max-w-4xl text-center">
            <h2 class="text-3xl font-serif text-stone-900 mb-6">{{ __('consultant_lp.why_title') }}</h2>
            <div class="w-12 h-0.5 bg-amber-700 mx-auto mb-8"></div>
            
            <div class="prose prose-lg prose-stone mx-auto text-stone-600 font-light leading-relaxed">
                <p>{{ __('consultant_lp.why_text') }}</p>
                @if($consultant->bio)
                    <div class="mt-8 pt-8 border-t border-stone-100 text-sm italic">
                        {!! nl2br(e($consultant->bio)) !!}
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- 3. TESTEMUNHOS (ID ADICIONADO: testimonials) --}}
    <section id="testimonials" class="py-16 bg-stone-50 overflow-hidden border-y border-stone-200">
        <div class="container mx-auto px-6">
            <div class="text-center mb-10">
                <span class="text-amber-700 font-bold tracking-widest text-xs uppercase">{{ __('consultant_lp.feedback_title') }}</span>
            </div>

            <div class="relative max-w-5xl mx-auto min-h-[400px]">
                {{-- WIDGET DA REPUTATION HUB --}}
                <script type='text/javascript' src='https://reputationhub.site/reputation/assets/review-widget.js'></script>
                <iframe class='lc_reviews_widget' 
                        src='https://reputationhub.site/reputation/widgets/review_widget/pNqZN0PDS8KBp1b3Uk13?widgetId=694556a169efd2edea08e932' 
                        frameborder='0' 
                        scrolling='no' 
                        style='min-width: 100%; width: 100%; min-height: 400px; border: none;'>
                </iframe>
            </div>
        </div>
    </section>

    {{-- 4. PORTFÓLIO (ID JÁ EXISTIA: portfolio) --}}
    <section id="portfolio" class="py-24 px-6">
        <div class="container mx-auto max-w-7xl">
            <div class="text-center mb-20">
                <span class="text-amber-700 font-bold tracking-widest text-xs uppercase">{{ __('consultant_lp.properties_subtitle') }}</span>
                <h2 class="text-4xl font-serif text-stone-900 mt-4">{{ __('consultant_lp.properties_title') }}</h2>
            </div>

            @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16">
                    @foreach($properties as $property)
                        {{-- LINK PARA O SITE PRINCIPAL --}}
                        <a href="{{ route('properties.show', $property) }}" target="_blank" class="group block">
                            <div class="relative overflow-hidden mb-6 shadow-lg border-8 border-white bg-white">
                                <div class="aspect-w-4 aspect-h-3">
                                    <img src="{{ $property->cover_image ? asset('storage/' . $property->cover_image) : asset('img/porto.jpg') }}" 
                                         class="w-full h-80 object-cover transform group-hover:scale-105 transition duration-1000 ease-in-out grayscale-[10%] group-hover:grayscale-0">
                                </div>
                                <div class="absolute top-0 right-0 bg-white px-4 py-2">
                                    <span class="text-xs font-bold tracking-widest text-stone-900 uppercase">
                                        {{ __('property_status.' . \Illuminate\Support\Str::slug($property->status)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-center px-4">
                                <p class="text-amber-700 text-xs font-bold tracking-widest uppercase mb-2">
                                    {{ $property->location }}
                                </p>
                                <h3 class="text-2xl font-serif text-stone-900 mb-2 group-hover:text-amber-800 transition-colors">
                                    {{ $property->title }}
                                </h3>
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
                <a href="https://houseteamconsultores.pt/imoveis" target="_blank" class="inline-block border-b border-stone-900 pb-1 text-stone-900 font-bold uppercase tracking-widest text-xs hover:text-amber-700 hover:border-amber-700 transition-all">
                    Ver Inventário Completo House Team
                </a>
            </div>
        </div>
    </section>

    {{-- 5. CONTACTO (ID JÁ EXISTIA: contact) --}}
    <section id="contact" class="py-24 bg-stone-900 text-stone-200 relative overflow-hidden">
        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-transparent via-amber-600 to-transparent"></div>

        <div class="container mx-auto max-w-4xl px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-serif text-white mb-6">Comece a sua História</h2>
                <p class="text-stone-400 text-lg font-light">Agende uma reunião estratégica para discutir o seu próximo passo imobiliário.</p>
            </div>

            <div class="bg-white text-stone-900 p-8 lg:p-12 shadow-2xl border-t-4 border-amber-700">
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-8">
                    @csrf
                    <input type="hidden" name="consultant_id" value="{{ $consultant->id }}">
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-stone-500">Nome</label>
                            <input type="text" name="name" class="w-full border-b border-stone-300 py-2 focus:outline-none focus:border-amber-700 transition-colors" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-widest text-stone-500">Telemóvel</label>
                            <input type="tel" name="phone" class="w-full border-b border-stone-300 py-2 focus:outline-none focus:border-amber-700 transition-colors">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-stone-500">Email</label>
                        <input type="email" name="email" class="w-full border-b border-stone-300 py-2 focus:outline-none focus:border-amber-700 transition-colors" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-stone-500">Mensagem</label>
                        <textarea name="message" rows="3" class="w-full border-b border-stone-300 py-2 focus:outline-none focus:border-amber-700 transition-colors resize-none" required></textarea>
                    </div>

                    <div class="text-center pt-6">
                        <button type="submit" class="px-12 py-4 bg-amber-700 text-white font-serif tracking-wide text-lg hover:bg-stone-900 transition-all duration-500 shadow-lg w-full md:w-auto">
                            Enviar Pedido de Reunião
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-stone-950 text-stone-500 py-10 text-center text-xs border-t border-stone-900">
        <div class="container mx-auto px-6">
            <p class="mb-2 font-serif text-stone-400 text-sm">&copy; {{ date('Y') }} {{ $consultant->name }}</p>
            <p class="opacity-50">Associada House Team • Licença AMI 12345</p>
        </div>
    </footer>

</div>

@endsection