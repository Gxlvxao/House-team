@extends('layouts.app')

@section('content')

<div x-data="{ 
    activeMember: null, 
    openModal: false,
    showMember(member) {
        this.activeMember = member;
        this.openModal = true;
        document.body.style.overflow = 'hidden';
    },
    closeModal() {
        this.openModal = false;
        setTimeout(() => this.activeMember = null, 300);
        document.body.style.overflow = 'auto';
    },
    getSocialLink(platform, value) {
        if (!value) return '';
        if (value.startsWith('http')) return value;
        const cleanValue = value.replace('@', '');
        const bases = {
            'instagram': 'https://instagram.com/',
            'tiktok': 'https://tiktok.com/@',
            'facebook': 'https://facebook.com/',
            'linkedin': 'https://linkedin.com/in/'
        };
        return bases[platform] + cleanValue;
    }
}">

    {{-- HERO SECTION --}}
    <section class="bg-ht-navy pt-40 pb-24 text-center relative overflow-hidden">
        {{-- Background Pattern & Gradient --}}
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-ht-navy via-slate-900 to-ht-navy opacity-80"></div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div data-aos="fade-down" data-aos-duration="1000">
                <span class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/20 text-ht-accent font-bold text-[10px] uppercase tracking-[0.3em] mb-6 backdrop-blur-sm">
                    {{ __('about.hero_badge') }}
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight leading-tight">
                    {{ __('about.hero_title_prefix') }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-400">{{ __('about.hero_title_suffix') }}</span>
                </h1>
                <p class="text-slate-400 max-w-2xl mx-auto text-lg md:text-xl font-light leading-relaxed">
                    {{ __('about.hero_desc') }}
                </p>
            </div>
        </div>
    </section>

    {{-- LEADER SECTION --}}
    @if($leader)
    <section class="py-24 bg-white relative overflow-hidden">
        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-full bg-slate-50/50 -skew-y-3 z-0"></div>

        <div class="container mx-auto px-6 text-center relative z-10">
            <div class="inline-block group cursor-pointer" data-aos="zoom-in" @click="showMember({{ json_encode($leader) }})">
                <div class="relative">
                    {{-- Glow Effect --}}
                    <div class="absolute -inset-6 bg-gradient-to-tr from-ht-navy via-ht-accent to-blue-500 rounded-full opacity-20 blur-2xl group-hover:opacity-40 transition duration-700"></div>
                    
                    {{-- Image Container --}}
                    <div class="relative w-56 h-56 md:w-72 md:h-72 mx-auto rounded-full p-2 bg-white shadow-2xl ring-1 ring-slate-100">
                        <div class="w-full h-full rounded-full overflow-hidden border-4 border-slate-50 relative">
                            <img src="{{ $leader->image_url }}" 
                                 alt="{{ $leader->name }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700 ease-out">
                             {{-- Overlay on Hover --}}
                             <div class="absolute inset-0 bg-ht-navy/20 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        </div>
                         {{-- Badge Icon --}}
                         <div class="absolute bottom-4 right-4 bg-ht-navy text-white p-3 rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition duration-500 border-4 border-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                         </div>
                    </div>
                </div>
                
                <h2 class="text-4xl font-black text-ht-navy mt-10 tracking-tight">{{ $leader->name }}</h2>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <span class="h-px w-8 bg-ht-accent/50"></span>
                    <p class="text-ht-accent font-bold uppercase tracking-widest text-sm">{{ $leader->role }}</p>
                    <span class="h-px w-8 bg-ht-accent/50"></span>
                </div>
                <p class="mt-4 text-sm text-slate-400 font-medium group-hover:text-ht-blue transition-colors">{{ __('about.view_full_profile') }} &rarr;</p>
            </div>
        </div>
    </section>
    @endif

    {{-- TEAM GRID --}}
    <section class="py-24 bg-slate-50 border-t border-slate-200">
        <div class="container mx-auto px-6 md:px-12">
            
            <div class="text-center mb-16">
                <h3 class="text-2xl font-bold text-ht-navy">{{ __('about.team_title') }}</h3>
                <div class="w-20 h-1 bg-ht-accent mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                @foreach($team as $member)
                    <div class="group relative" data-aos="fade-up" @click="showMember({{ json_encode($member) }})">
                        
                        {{-- Card Background --}}
                        <div class="absolute inset-0 bg-white rounded-3xl shadow-sm border border-slate-100 transform transition-all duration-300 group-hover:-translate-y-2 group-hover:shadow-xl group-hover:border-ht-blue/20"></div>

                        <div class="relative p-8 text-center cursor-pointer">
                            {{-- Top Gradient Line --}}
                            <div class="absolute top-0 left-8 right-8 h-1 bg-gradient-to-r from-transparent via-ht-accent to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>

                            {{-- Image --}}
                            <div class="w-32 h-32 mx-auto rounded-full p-1 border border-slate-200 group-hover:border-ht-accent transition-colors duration-300 mb-6 bg-white">
                                <div class="w-full h-full rounded-full overflow-hidden relative">
                                    <img src="{{ $member->image_url }}" 
                                         class="w-full h-full object-cover filter grayscale group-hover:grayscale-0 transform group-hover:scale-110 transition duration-500">
                                </div>
                            </div>

                            {{-- Info --}}
                            <h3 class="text-lg font-bold text-ht-navy mb-1 group-hover:text-ht-blue transition-colors">{{ $member->name }}</h3>
                            <p class="text-[10px] text-slate-400 uppercase tracking-wider font-bold mb-6">{{ $member->role }}</p>

                            {{-- Action --}}
                            <div class="inline-flex items-center gap-1 text-xs font-bold text-slate-400 group-hover:text-ht-navy transition-colors">
                                <span>{{ __('about.view_details') }}</span>
                                <svg class="w-3 h-3 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- MODAL --}}
    <div x-show="openModal" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
         style="display: none;">
        
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-ht-navy/90 backdrop-blur-sm transition-opacity duration-300"
             x-show="openModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeModal()"></div>

        {{-- Modal Content --}}
        <div class="relative bg-white w-full max-w-5xl rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row transform transition-all duration-300 max-h-[90vh] md:max-h-auto"
             x-show="openModal"
             x-transition:enter="ease-out duration-500"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-8">
            
            {{-- Close Button --}}
            <button @click="closeModal()" class="absolute top-4 right-4 z-50 p-2 bg-white/10 backdrop-blur-md rounded-full text-white/80 hover:bg-white hover:text-ht-navy transition-all border border-white/20 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            {{-- Left Side: Image --}}
            <div class="md:w-5/12 bg-slate-100 relative h-72 md:h-auto shrink-0">
                <template x-if="activeMember">
                    <img :src="activeMember.image_url" 
                         class="w-full h-full object-cover absolute inset-0">
                </template>
                {{-- Gradient Overlay for text readability on mobile --}}
                <div class="absolute inset-0 bg-gradient-to-t from-ht-navy/90 via-transparent to-transparent md:bg-gradient-to-r md:from-transparent md:to-white/10"></div>
                
                {{-- Name on Image (Mobile Only) --}}
                <div class="absolute bottom-6 left-6 md:hidden text-white">
                    <h2 class="text-3xl font-black" x-text="activeMember.name"></h2>
                    <p class="text-sm font-bold opacity-80" x-text="activeMember.role"></p>
                </div>
            </div>

            {{-- Right Side: Info --}}
            <div class="md:w-7/12 p-8 md:p-12 flex flex-col justify-center bg-white overflow-y-auto">
                <template x-if="activeMember">
                    <div>
                        {{-- Header (Desktop) --}}
                        <div class="hidden md:block mb-8">
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-ht-blue text-[10px] font-bold uppercase tracking-widest rounded-full mb-3 border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-ht-blue"></span>
                                <span x-text="activeMember.role"></span>
                            </span>
                            <h2 class="text-4xl md:text-5xl font-black text-ht-navy tracking-tight" x-text="activeMember.name"></h2>
                        </div>
                        
                        {{-- Bio --}}
                        <div class="mb-10">
                            <h4 class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-4">{{ __('about.modal_bio_title') }}</h4>
                            <div x-show="activeMember.bio">
                                <p class="text-slate-600 leading-relaxed text-base md:text-lg font-light" x-text="activeMember.bio"></p>
                            </div>
                            <div x-show="!activeMember.bio" class="p-6 bg-slate-50 rounded-xl border border-slate-100 text-center">
                                <p class="text-slate-400 italic text-sm">{{ __('about.modal_bio_empty') }}</p>
                            </div>
                        </div>

                        {{-- Contacts --}}
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-2">{{ __('about.modal_contact_title') }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <a x-show="activeMember.phone"
                                   :href="'https://wa.me/' + (activeMember.phone ? activeMember.phone.replace(/[^0-9]/g, '') : '')" 
                                   target="_blank"
                                   class="group flex items-center justify-center gap-3 bg-[#25D366] hover:bg-[#128C7E] text-white py-4 px-6 rounded-xl font-bold transition-all shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    <span>WhatsApp</span>
                                </a>

                                <a :href="'mailto:' + activeMember.email" 
                                   x-show="activeMember.email"
                                   class="group flex items-center justify-center gap-3 bg-ht-navy hover:bg-ht-blue text-white py-4 px-6 rounded-xl font-bold transition-all shadow-lg hover:shadow-blue-500/30 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>{{ __('about.btn_email') }}</span>
                                </a>
                            </div>
                        </div>

                        {{-- Social Media --}}
                        <div class="mt-8 pt-8 border-t border-slate-100" 
                             x-show="activeMember.facebook || activeMember.instagram || activeMember.linkedin || activeMember.tiktok">
                            <h4 class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-4">{{ __('about.modal_social_title') }}</h4>
                            <div class="flex gap-4">
                                {{-- Instagram --}}
                                <a x-show="activeMember.instagram" :href="getSocialLink('instagram', activeMember.instagram)" target="_blank" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-gradient-to-tr hover:from-yellow-400 hover:via-red-500 hover:to-purple-500 hover:text-white transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </a>
                                {{-- Facebook --}}
                                <a x-show="activeMember.facebook" :href="getSocialLink('facebook', activeMember.facebook)" target="_blank" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-[#1877F2] hover:text-white transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                {{-- LinkedIn --}}
                                <a x-show="activeMember.linkedin" :href="getSocialLink('linkedin', activeMember.linkedin)" target="_blank" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-[#0A66C2] hover:text-white transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                                {{-- TikTok --}}
                                <a x-show="activeMember.tiktok" :href="getSocialLink('tiktok', activeMember.tiktok)" target="_blank" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-black hover:text-white transition-all duration-300 shadow-sm hover:shadow-md transform hover:-translate-y-1">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.49-3.35-3.98-5.6-1.11-5.5 3.04-10.83 8.45-10.83 2.5 0 4.01 1.11 4.01 1.11v4.21c-1.72-.93-3.8-.75-5.33.45-1.53 1.2-2.3 3.19-1.95 5.11.29 1.61 1.37 3.03 2.87 3.73 1.5.7 3.26.56 4.67-.36 1.05-.68 1.7-1.83 1.75-3.08.03-.76.01-1.53.01-2.29V.02z"/></svg>
                                </a>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>
    </div>

</div>

@endsection