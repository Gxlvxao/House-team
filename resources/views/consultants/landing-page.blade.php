@extends('layouts.app')

@section('title', $consultant->name . ' - ' . __('consultant_lp.hero_subtitle'))

@section('content')

{{-- DESIGN SYSTEM: NAVY & GOLD --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap');

    :root {
        --color-navy: #1e293b;
        --color-navy-light: #334155;
        --color-gold: #c5a059;
        --color-gold-light: #e5c585;
        --font-serif: 'Playfair Display', serif;
        --font-sans: 'Inter', sans-serif;
    }

    body { font-family: var(--font-sans); color: var(--color-navy); }
    h1, h2, h3, h4, .font-serif { font-family: var(--font-serif); }

    .text-gold { color: var(--color-gold) !important; }
    .bg-gold { background-color: var(--color-gold) !important; }
    .bg-navy { background-color: var(--color-navy) !important; }
    .border-gold { border-color: var(--color-gold) !important; }

    .btn-gold {
        background-color: var(--color-gold);
        color: white;
        padding: 14px 40px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 12px;
        transition: all 0.3s ease;
        border: 1px solid var(--color-gold);
    }
    .btn-gold:hover {
        background-color: transparent;
        color: var(--color-gold);
        transform: translateY(-2px);
    }

    .btn-navy-outline {
        background-color: transparent;
        color: var(--color-navy);
        padding: 14px 40px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 12px;
        transition: all 0.3s ease;
        border: 1px solid var(--color-navy);
    }
    .btn-navy-outline:hover {
        background-color: var(--color-navy);
        color: white;
        transform: translateY(-2px);
    }

    .decor-line {
        height: 2px;
        width: 60px;
        background-color: var(--color-gold);
        margin: 20px 0;
    }

    .property-card { transition: transform 0.4s ease, box-shadow 0.4s ease; }
    .property-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(30, 41, 59, 0.1); }
</style>

<div class="overflow-x-hidden">

    {{-- 1. HERO SECTION --}}
    <section id="home" class="relative min-h-screen flex items-center pt-28 pb-20 bg-slate-50">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-navy hidden lg:block" style="clip-path: polygon(20% 0%, 100% 0, 100% 100%, 0% 100%);"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <div class="order-2 lg:order-1" data-aos="fade-right" data-aos-duration="1000">
                    <span class="text-gold font-bold tracking-[0.2em] text-xs uppercase mb-4 block">
                        {{ __('consultant_lp.hero_subtitle') }}
                    </span>
                    
                    <h1 class="text-6xl lg:text-7xl font-bold text-navy leading-tight mb-6">
                        {{ $consultant->name }}
                    </h1>
                    
                    <div class="decor-line"></div>

                    <p class="text-slate-600 text-lg leading-relaxed mb-10 font-light max-w-lg">
                        {{ __('consultant_lp.hero_desc') }}
                    </p>

                    <div class="flex gap-4">
                        <a href="#portfolio" class="btn-gold shadow-lg">
                            {{ __('consultant_lp.btn_collection') }}
                        </a>
                        <a href="#contact" class="btn-navy-outline">
                            {{ __('consultant_lp.btn_contact') }}
                        </a>
                    </div>
                </div>

                <div class="order-1 lg:order-2 relative" data-aos="fade-left" data-aos-duration="1000">
                    <div class="relative z-10">
                        <img src="{{ $consultant->image_url ?? asset('img/team/' . $consultant->photo) }}" 
                             alt="{{ $consultant->name }}" 
                             class="w-full h-auto object-cover shadow-2xl rounded-sm max-h-[80vh] ml-auto"
                             style="box-shadow: -20px 20px 0px rgba(197, 160, 89, 0.2);"
                             onerror="this.src='{{ asset('img/logo.png') }}'">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. ESTATÍSTICAS --}}
    <section class="bg-navy py-16 text-white relative">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/10">
                <div data-aos="fade-up" data-aos-delay="0">
                    <p class="text-4xl font-serif text-gold font-bold mb-2">15+</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('consultant_lp.stats_years') }}</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="100">
                    <p class="text-4xl font-serif text-gold font-bold mb-2">1.000.000$</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('consultant_lp.stats_dreams') }}</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <p class="text-4xl font-serif text-gold font-bold mb-2">1.000+</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('consultant_lp.stats_producer') }}</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="300">
                    <p class="text-4xl font-serif text-gold font-bold mb-2">50+</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">{{ __('consultant_lp.stats_dedication') }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. SOBRE --}}
    <section id="about" class="py-24 bg-white">
        <div class="container mx-auto px-6 max-w-4xl text-center" data-aos="fade-up">
            <span class="text-slate-400 text-xs font-bold uppercase tracking-widest block mb-3">{{ __('consultant_lp.why_title_label') }}</span>
            <h2 class="text-4xl text-navy mb-8">{{ __('consultant_lp.why_title') }}</h2>
            <div class="w-20 h-[1px] bg-gold mx-auto mb-10"></div>
            
            <div class="prose prose-lg mx-auto text-slate-600 font-light leading-loose">
                <p class="mb-8">"{{ __('consultant_lp.why_text') }}"</p>
                @if($consultant->bio)
                    <div class="text-sm italic text-slate-500 border-t border-slate-100 pt-8">
                        {!! nl2br(e($consultant->bio)) !!}
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- 4. PORTFOLIO --}}
    <section id="portfolio" class="py-24 bg-slate-50">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6" data-aos="fade-up">
                <div>
                    <span class="text-gold font-bold tracking-widest text-xs uppercase mb-2 block">{{ __('consultant_lp.properties_subtitle') }}</span>
                    <h2 class="text-4xl text-navy font-serif">{{ __('consultant_lp.properties_title') }}</h2>
                </div>
                <a href="https://houseteamconsultores.pt/imoveis" target="_blank" class="text-navy border-b border-navy pb-1 text-xs font-bold uppercase hover:text-gold hover:border-gold transition">
                    {{ __('consultant_lp.view_inventory') }}
                </a>
            </div>

            @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($properties as $property)
                        <a href="{{ route('properties.show', $property) }}?cid={{ $consultant->id }}" target="_blank" class="property-card group block bg-white shadow-sm overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                            <div class="relative overflow-hidden aspect-[4/3]">
                                <img src="{{ $property->cover_image ? asset('storage/' . $property->cover_image) : asset('img/porto.jpg') }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition duration-1000 ease-out">
                                
                                <div class="absolute top-4 right-4 z-20">
                                    <span class="bg-navy text-white px-4 py-2 text-[10px] font-bold uppercase tracking-widest">
                                        {{ __('property_status.' . \Illuminate\Support\Str::slug($property->status)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-8 border-b-4 border-transparent group-hover:border-gold transition-colors">
                                <p class="text-gold text-xs font-bold uppercase tracking-wider mb-2">
                                    {{ $property->location }}
                                </p>
                                <h3 class="text-xl font-serif text-navy mb-4 group-hover:text-gold transition-colors truncate">
                                    {{ $property->title }}
                                </h3>
                                <div class="flex justify-between items-center mt-6">
                                    <span class="text-lg font-bold text-navy">
                                        {{ $property->price ? number_format($property->price, 0, ',', '.') . ' €' : 'Sob Consulta' }}
                                    </span>
                                    <div class="w-8 h-8 rounded-full border border-slate-200 flex items-center justify-center text-slate-400 group-hover:border-gold group-hover:text-gold transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="py-24 text-center">
                    <p class="text-slate-400 font-serif italic text-xl">{{ __('consultant_lp.portfolio_empty') }}</p>
                </div>
            @endif
        </div>
    </section>

    {{-- 5. TESTEMUNHOS --}}
    <section id="testimonials" class="py-24 bg-white border-t border-slate-100">
        <div class="container mx-auto px-6 text-center">
            <span class="text-slate-400 font-bold tracking-widest text-xs uppercase mb-10 block">{{ __('consultant_lp.feedback_title') }}</span>
            <div class="max-w-5xl mx-auto min-h-[400px]" data-aos="fade-up">
                <script type='text/javascript' src='https://reputationhub.site/reputation/assets/review-widget.js'></script>
                <iframe class='lc_reviews_widget' 
                        src='https://reputationhub.site/reputation/widgets/review_widget/pNqZN0PDS8KBp1b3Uk13?widgetId=69176d9fcf2958bcfa34b132' 
                        frameborder='0' scrolling='no' style='min-width: 100%; width: 100%; min-height: 400px; border: none;'>
                </iframe>
            </div>
        </div>
    </section>

    {{-- 6. CONTACTO --}}
    <section id="contact" class="relative py-24 bg-navy overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gold opacity-10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

        <div class="container mx-auto px-6 max-w-4xl relative z-10" data-aos="fade-up">
            <div class="text-center mb-16">
                <h2 class="text-4xl text-white font-serif mb-6">{{ __('consultant_lp.contact_title') }}</h2>
                <p class="text-slate-400 text-lg font-light">{{ __('consultant_lp.contact_subtitle') }}</p>
            </div>

            <div class="bg-white p-8 md:p-12 shadow-2xl relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-gold"></div>
                
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="consultant_id" value="{{ $consultant->id }}">
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <label class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 block">{{ __('contact.form_name') }}</label>
                            <input type="text" name="name" class="w-full border-b border-slate-200 py-3 text-navy focus:outline-none focus:border-gold transition-colors bg-transparent" required placeholder="{{ __('contact.placeholder_name') }}">
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 block">{{ __('contact.form_phone') }}</label>
                            <input type="tel" name="phone" class="w-full border-b border-slate-200 py-3 text-navy focus:outline-none focus:border-gold transition-colors bg-transparent" placeholder="+351 ...">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 block">{{ __('contact.form_email') }}</label>
                        <input type="email" name="email" class="w-full border-b border-slate-200 py-3 text-navy focus:outline-none focus:border-gold transition-colors bg-transparent" required placeholder="{{ __('contact.placeholder_email') }}">
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 block">{{ __('contact.form_message') }}</label>
                        <textarea name="message" rows="3" class="w-full border-b border-slate-200 py-3 text-navy focus:outline-none focus:border-gold transition-colors resize-none bg-transparent" required placeholder="{{ __('contact.placeholder_message') }}"></textarea>
                    </div>

                    <div class="text-center pt-8">
                        <button type="submit" class="btn-gold w-full md:w-auto shadow-xl">
                            {{ __('contact.btn_send') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-navy text-white py-12 text-center border-t border-white/5" style="background-color: #0f172a;">
        <div class="container mx-auto px-6">
            <img src="{{ asset('img/logo/casaacasa.png') }}" alt="Logo" class="h-16 mx-auto mb-6 brightness-0 invert opacity-50" onerror="this.style.display='none';">
            <p class="font-serif font-bold text-xl mb-2">{{ $consultant->name }}</p>
            <p class="text-xs text-slate-500 uppercase tracking-widest">&copy; {{ date('Y') }} • {{ __('consultant_lp.footer_note') }}</p>
        </div>
    </footer>

</div>

@endsection