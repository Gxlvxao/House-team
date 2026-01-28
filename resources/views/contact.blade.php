@extends('layouts.app')

@section('content')

{{-- HEADER SECTION --}}
<section class="relative py-32 bg-ht-navy text-center overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-ht-accent/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <p class="text-ht-accent font-bold text-xs uppercase tracking-[0.3em] mb-4">{{ __('contact.header_waiting') }}</p>
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">{{ __('contact.header_title') }}</h1>
        <p class="text-slate-400 max-w-2xl mx-auto text-lg font-light">
            {{ __('contact.header_desc') }}
        </p>
    </div>
</section>

<section class="py-20 bg-slate-50 relative">
    <div class="container mx-auto px-6 md:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
            
            {{-- LADO ESQUERDO: INFORMAÇÕES DE CONTATO --}}
            <div class="space-y-10">
                <div>
                    <h3 class="text-3xl font-black text-ht-navy mb-8">{{ __('contact.channels_title') }}</h3>
                    
                    <div class="space-y-6">
                        {{-- ESCRITÓRIO --}}
                        <div class="flex items-start gap-5 p-6 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                            <div class="p-3 bg-blue-50 rounded-xl text-ht-accent">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold uppercase tracking-widest text-ht-navy mb-1">{{ __('contact.channel_office') }}</h4>
                                <p class="text-slate-500 font-medium">{!! nl2br(__('contact.address')) !!}</p>
                            </div>
                        </div>

                        {{-- TELEFONE --}}
                        <div class="flex items-start gap-5 p-6 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                            <div class="p-3 bg-blue-50 rounded-xl text-ht-accent">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold uppercase tracking-widest text-ht-navy mb-1">{{ __('contact.channel_phone') }}</h4>
                                <p class="text-slate-500 font-medium">+351 962 881 120</p>
                                <p class="text-xs text-slate-400 mt-1">{{ __('contact.phone_note') }}</p>
                            </div>
                        </div>

                        {{-- EMAIL --}}
                        <div class="flex items-start gap-5 p-6 bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                            <div class="p-3 bg-blue-50 rounded-xl text-ht-accent">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold uppercase tracking-widest text-ht-navy mb-1">{{ __('contact.channel_email') }}</h4>
                                <a href="mailto:Clientes@houseteamconsultores.pt" class="text-slate-500 font-medium hover:text-ht-accent transition">Clientes@houseteamconsultores.pt</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold uppercase tracking-widest text-ht-navy mb-4">{{ __('contact.follow_us') }}</h4>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/houseteamconsultores/" target="_blank" class="w-12 h-12 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-ht-accent hover:text-white hover:border-ht-accent transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/houseteam_consultores/" target="_blank" class="w-12 h-12 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-ht-accent hover:text-white hover:border-ht-accent transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://www.linkedin.com/company/house-team-consultores/" target="_blank" class="w-12 h-12 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-ht-accent hover:text-white hover:border-ht-accent transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>

                <div class="w-full h-64 bg-slate-200 rounded-2xl overflow-hidden shadow-inner">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3111.456789012345!2d-9.112345!3d38.765432!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd1933c123456789%3A0x123456789abcdef!2sR.%20Cidade%20de%20Bissau%2049A%2C%20Olivais%2C%20Lisboa!5e0!3m2!1spt-PT!2spt!4v1600000000000!5m2!1spt-PT!2spt" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

            {{-- LADO DIREITO: FORMULÁRIO --}}
            <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-2xl border border-slate-100 h-fit sticky top-32">
                <h3 class="text-2xl font-black text-ht-navy mb-2">{{ __('contact.form_title') }}</h3>
                <p class="text-slate-400 text-sm mb-8">{{ __('contact.form_subtitle') }}</p>
                
                {{-- MENSAGENS DE FEEDBACK --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl text-sm font-bold border border-green-200 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl text-sm font-bold border border-red-200 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
                
                {{-- FORMULÁRIO --}}
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1">{{ __('contact.form_name') }}</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-ht-accent focus:ring-1 focus:ring-ht-accent transition-all" placeholder="{{ __('contact.placeholder_name') }}">
                            @error('name') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1">{{ __('contact.form_phone') }}</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-ht-accent focus:ring-1 focus:ring-ht-accent transition-all" placeholder="{{ __('contact.placeholder_phone') }}">
                            @error('phone') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1">{{ __('contact.form_email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-ht-accent focus:ring-1 focus:ring-ht-accent transition-all" placeholder="{{ __('contact.placeholder_email') }}">
                        @error('email') <span class="text-xs text-red-500 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1">{{ __('contact.form_subject') }}</label>
                        <div class="relative">
                            {{-- IMPORTANTE: Values fixos para o Controller entender o funil correto --}}
                            <select name="subject" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-ht-accent focus:ring-1 focus:ring-ht-accent transition-all appearance-none text-slate-600">
                                <option value="comprar" {{ old('subject') == 'comprar' ? 'selected' : '' }}>{{ __('contact.subject_buy') }}</option>
                                <option value="vender" {{ old('subject') == 'vender' ? 'selected' : '' }}>{{ __('contact.subject_sell') }}</option>
                                <option value="avaliacao" {{ old('subject') == 'avaliacao' ? 'selected' : '' }}>{{ __('contact.subject_valuation') }}</option>
                                <option value="recrutamento" {{ old('subject') == 'recrutamento' ? 'selected' : '' }}>{{ __('contact.subject_recruitment') }}</option>
                                <option value="outros" {{ old('subject') == 'outros' ? 'selected' : '' }}>{{ __('contact.subject_other') }}</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold uppercase tracking-wide text-ht-navy ml-1">{{ __('contact.form_message') }}</label>
                        <textarea name="message" rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-ht-accent focus:ring-1 focus:ring-ht-accent transition-all resize-none" placeholder="{{ __('contact.placeholder_message') }}">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-ht-accent text-white font-black uppercase tracking-widest text-xs py-4 mt-2 rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/30 transform active:scale-95">
                        {{ __('contact.btn_send') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

@endsection