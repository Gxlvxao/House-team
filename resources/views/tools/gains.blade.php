@extends('layouts.app')

@section('content')

{{-- 
    1. OVERRIDE DE DESIGN SYSTEM (SE TIVER CONSULTORA)
    Transforma a ferramenta em "Navy & Gold" automaticamente.
--}}
@if(isset($consultant))
    <style>
        /* Fontes Premium */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600&display=swap');
        
        :root {
            --font-serif: 'Playfair Display', serif;
            --font-sans: 'Inter', sans-serif;
            --color-gold: #c5a059;
            --color-navy: #1e293b;
        }

        /* Override de Fontes */
        body { font-family: var(--font-sans) !important; }
        h1, h2, h3, h4 { font-family: var(--font-serif) !important; }

        /* Override de Cores */
        .bg-ht-accent { background-color: var(--color-gold) !important; }
        .text-ht-accent { color: var(--color-gold) !important; }
        .border-ht-accent { border-color: var(--color-gold) !important; }
        .ring-ht-accent { --tw-ring-color: var(--color-gold) !important; }
        .focus\:ring-ht-accent:focus { --tw-ring-color: var(--color-gold) !important; }
        
        .bg-ht-navy { background-color: var(--color-navy) !important; }
        .text-ht-navy { color: var(--color-navy) !important; }
        
        /* Ajustes de Hover */
        .hover\:bg-ht-accent:hover { background-color: #b08d4b !important; }
        
        /* Ajuste visual dos inputs (mais elegantes) */
        input[type="number"], select {
            border-radius: 4px !important;
            border-color: #e2e8f0;
        }
        input[type="number"]:focus, select:focus {
            border-color: var(--color-gold) !important;
            box-shadow: 0 0 0 1px var(--color-gold) !important;
        }
        
        /* Ajuste do botão principal */
        button.bg-ht-navy {
            background-color: var(--color-navy) !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
            border-radius: 4px !important;
        }
        button.bg-ht-navy:hover {
            background-color: var(--color-gold) !important;
            transform: translateY(-2px);
        }
    </style>
@endif

{{-- CORREÇÃO: x-data MOVIDO PARA CÁ (WRAPPER EXTERNO) PARA ENGLOBAR O MODAL --}}
<div class="bg-slate-50 min-h-screen pt-40 pb-12 relative overflow-hidden" x-data="gainsForm()">
    
    {{-- Fundo Decorativo (Só aparece na versão Consultora) --}}
    @if(isset($consultant))
        <div class="absolute top-0 right-0 w-1/3 h-96 bg-ht-navy opacity-5 -z-10" style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%);"></div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Cabeçalho da Ferramenta --}}
        <div class="text-center mb-10" data-aos="fade-down">
            @if(isset($consultant))
                <span class="text-ht-accent font-bold tracking-widest text-xs uppercase mb-2 block">{{ __('menu.tools') }}</span>
            @endif
            <h1 class="text-4xl font-black text-ht-navy tracking-tight mb-4">{{ __('tools.gains.title') }}</h1>
            <p class="text-slate-500 font-medium uppercase tracking-widest text-xs">{{ __('tools.gains.subtitle') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            {{-- ÁREA DO FORMULÁRIO --}}
            <div class="lg:col-span-8 space-y-6" data-aos="fade-up">
                
                {{-- 1. Valor de Aquisição --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span>
                        {{ __('tools.gains.section_acquisition') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.gains.label_value') }}</label>
                            <input type="number" step="0.01" x-model="form.acquisition_value" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent focus:border-transparent text-ht-navy placeholder-slate-400 font-bold" placeholder="Ex: 150000,00">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.gains.label_year') }}</label>
                                <select x-model="form.acquisition_year" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent focus:border-transparent text-ht-navy">
                                    @foreach(range(2025, 1901) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.gains.label_month') }}</label>
                                <select x-model="form.acquisition_month" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent focus:border-transparent text-ht-navy">
                                    @foreach(['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'] as $month)
                                        <option value="{{ $month }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- CONSTRUÇÃO PRÓPRIA --}}
                        <div class="md:col-span-2 pt-2 border-t border-slate-100 mt-2">
                             <label class="block text-sm font-bold text-ht-navy mb-3">{{ __('tools.gains.question_self_built') }}</label>
                             <div class="flex gap-6">
                                 <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Sim" x-model="form.self_built" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.yes') }}</span></label>
                                 <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Não" x-model="form.self_built" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.no') }}</span></label>
                             </div>
                             <p class="text-[10px] text-slate-400 mt-1">{{ __('tools.gains.note_coefficients') }}</p>
                        </div>
                    </div>
                </div>

                {{-- 2. Valor de Venda --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span>
                        {{ __('tools.gains.section_sale') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.gains.label_value') }}</label>
                            <input type="number" step="0.01" x-model="form.sale_value" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent focus:border-transparent text-ht-navy placeholder-slate-400 font-bold" placeholder="Ex: 300000,00">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.gains.label_year') }}</label>
                                <select x-model="form.sale_year" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent focus:border-transparent text-ht-navy">
                                    @foreach(range(2025, 1901) as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.gains.label_month') }}</label>
                                <select x-model="form.sale_month" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent focus:border-transparent text-ht-navy">
                                    @foreach(['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'] as $month)
                                        <option value="{{ $month }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Despesas --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-lg font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span>
                        {{ __('tools.gains.section_expenses') }}
                    </h3>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-ht-navy mb-3">{{ __('tools.gains.question_expenses') }}</label>
                        <div class="flex gap-6">
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="radio" value="Sim" x-model="form.has_expenses" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300">
                                <span class="ml-2 text-sm font-medium text-slate-600 group-hover:text-ht-navy transition-colors">{{ __('tools.gains.yes') }}</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="radio" value="Não" x-model="form.has_expenses" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300">
                                <span class="ml-2 text-sm font-medium text-slate-600 group-hover:text-ht-navy transition-colors">{{ __('tools.gains.no') }}</span>
                            </label>
                        </div>
                    </div>

                    <div x-show="form.has_expenses === 'Sim'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('tools.gains.label_works') }}</label>
                            <input type="number" step="0.01" x-model="form.expenses_works" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent">
                            <p class="text-[10px] text-slate-400 mt-1">{{ __('tools.gains.note_works') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('tools.gains.label_imt') }}</label>
                            <input type="number" step="0.01" x-model="form.expenses_imt" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('tools.gains.label_commission') }}</label>
                            <input type="number" step="0.01" x-model="form.expenses_commission" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('tools.gains.label_other') }}</label>
                            <input type="number" step="0.01" x-model="form.expenses_other" class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent">
                        </div>
                    </div>
                </div>

                {{-- 4. Situação Fiscal --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-8">
                    <h3 class="text-lg font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-6 h-6 rounded-full flex items-center justify-center text-xs">4</span>
                        {{ __('tools.gains.section_tax') }}
                    </h3>

                    <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100">
                        <label class="block text-sm font-bold text-ht-navy mb-3 leading-relaxed">
                            {{ __('tools.gains.question_state_sale') }}
                        </label>
                        <div class="flex gap-6 mt-3">
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="radio" value="Sim" x-model="form.sold_to_state" @change="resetHPPFields" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300">
                                <span class="ml-2 font-bold text-ht-navy group-hover:text-ht-accent transition-colors">{{ __('tools.gains.yes') }}</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="radio" value="Não" x-model="form.sold_to_state" @change="resetHPPFields" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300">
                                <span class="ml-2 font-bold text-slate-600 group-hover:text-ht-navy transition-colors">{{ __('tools.gains.no') }}</span>
                            </label>
                        </div>
                        <div x-show="form.sold_to_state === 'Sim'" x-transition class="mt-4 text-sm text-blue-700 font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ __('tools.gains.note_state_exempt') }}</span>
                        </div>
                    </div>

                    <div x-show="form.sold_to_state === 'Não'" x-transition class="space-y-6">
                        
                        {{-- HPP Status --}}
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <label class="block text-sm font-bold text-ht-navy mb-3">{{ __('tools.gains.question_hpp') }}</label>
                            <div class="flex flex-col gap-3">
                                <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Sim" x-model="form.hpp_status" @change="resetReinvestmentFields" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.option_hpp_yes') }}</span></label>
                                <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Menos12Meses" x-model="form.hpp_status" @change="resetReinvestmentFields" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.option_hpp_less12') }}</span></label>
                                <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Não" x-model="form.hpp_status" @change="resetReinvestmentFields" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.option_hpp_no') }}</span></label>
                            </div>
                        </div>

                        {{-- ISENÇÕES / REINVESTIMENTO / AMORTIZAÇÃO --}}
                        <div class="space-y-6 p-6 rounded-2xl border border-ht-accent/40 bg-ht-accent/5">
                            <h4 class="text-base font-bold text-ht-navy border-b border-ht-accent/30 pb-3">{{ __('tools.gains.section_reinvest') }}</h4>

                            {{-- 1. Reinvestimento em nova HPP (SÓ PARA HPP) --}}
                            <div class="pl-4 border-l-4 border-ht-accent/20" x-show="form.hpp_status === 'Sim'">
                                <label class="block text-sm font-bold text-ht-navy mb-3">{{ __('tools.gains.question_reinvest_new') }}</label>
                                <div class="flex gap-6 mb-3">
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Sim" x-model="form.reinvest_intention" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.yes') }}</span></label>
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Não" x-model="form.reinvest_intention" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.no') }}</span></label>
                                </div>
                                <div x-show="form.reinvest_intention === 'Sim'" x-transition>
                                    <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('tools.gains.label_reinvest_amount') }}</label>
                                    <input type="number" step="0.01" x-model="form.reinvestment_amount" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent">
                                </div>
                            </div>

                            {{-- 2. Amortização de Crédito --}}
                            <div class="pl-4 border-l-4 border-ht-primary/20">
                                <label class="block text-sm font-bold text-ht-navy mb-1">{{ __('tools.gains.question_amortize') }}</label>
                                <p class="text-[10px] text-slate-500 mb-3 leading-tight" x-show="form.hpp_status !== 'Sim'">
                                    {{ __('tools.gains.note_amortize') }}
                                </p>
                                <div class="flex gap-6 mb-3">
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Sim" x-model="form.amortize_credit" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.yes') }}</span></label>
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Não" x-model="form.amortize_credit" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.no') }}</span></label>
                                </div>
                                <div x-show="form.amortize_credit === 'Sim'" x-transition>
                                    <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('tools.gains.label_amortize_amount') }}</label>
                                    <input type="number" step="0.01" x-model="form.amortization_amount" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent">
                                </div>
                            </div>
                            
                            {{-- 3. Reformados (SÓ PARA HPP) --}}
                            <div class="pt-4 border-t border-ht-accent/30" x-show="form.hpp_status === 'Sim'">
                                <label class="block text-sm font-bold text-ht-navy mb-3">{{ __('tools.gains.question_retired') }}</label>
                                <div class="flex gap-6">
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Sim" x-model="form.retired_status" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.yes') }}</span></label>
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Não" x-model="form.retired_status" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.no') }}</span></label>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1" x-show="form.retired_status === 'Sim'">
                                    {{ __('tools.gains.note_retired') }}
                                </p>
                            </div>
                        </div>

                        {{-- Perguntas de IRS Gerais --}}
                        <div class="space-y-6 pt-6 border-t border-slate-100">
                            <div>
                                <label class="block text-sm font-bold text-ht-navy mb-3">{{ __('tools.gains.question_joint_tax') }}</label>
                                <div class="flex gap-6">
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Sim" x-model="form.joint_tax_return" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.yes') }}</span></label>
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Não" x-model="form.joint_tax_return" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.no') }}</span></label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-ht-navy mb-2">{{ __('tools.gains.label_annual_income') }}</label>
                                <input type="number" step="0.01" x-model="form.annual_income" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent focus:border-transparent text-ht-navy placeholder-slate-400" placeholder="Ex: 25000,00">
                                <p class="text-xs text-slate-400 mt-1">{{ __('tools.gains.note_income') }}</p>
                            </div>

                            <div class="pt-6 border-t border-slate-100">
                                <label class="block text-sm font-bold text-ht-navy mb-3">{{ __('tools.gains.question_support') }}</label>
                                <div class="flex gap-6 mb-3">
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Sim" x-model="form.public_support" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.yes') }}</span></label>
                                    <label class="inline-flex items-center cursor-pointer group"><input type="radio" value="Não" x-model="form.public_support" class="text-ht-accent focus:ring-ht-accent w-5 h-5 border-slate-300"><span class="ml-2 text-sm text-slate-600 group-hover:text-ht-navy">{{ __('tools.gains.no') }}</span></label>
                                </div>
                                <div x-show="form.public_support === 'Sim'" x-transition class="grid grid-cols-2 gap-4 bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.gains.label_support_year') }}</label>
                                        <select x-model="form.public_support_year" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent text-ht-navy">
                                            @foreach(range(2025, 1980) as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.gains.label_support_month') }}</label>
                                        <select x-model="form.public_support_month" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent text-ht-navy">
                                            @foreach(['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'] as $month)
                                                <option value="{{ $month }}">{{ $month }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <section class="border-t border-slate-200 pt-8">
                    <button type="button" @click="openModal" class="w-full bg-ht-navy text-white font-bold py-5 rounded-3xl shadow-xl hover:bg-ht-primary hover:shadow-2xl transition-all uppercase tracking-widest text-sm transform hover:-translate-y-1">
                        {{ __('tools.gains.btn_simulate') }}
                    </button>
                </section>
            </div>

            {{-- COLUNA DIREITA (RESULTADOS) --}}
            <div class="lg:col-span-4" data-aos="fade-left">
                <div class="sticky top-24 space-y-6">
                    <div x-show="!hasCalculated" class="bg-white border border-slate-200 rounded-3xl p-10 text-center text-slate-400 shadow-sm">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-30 text-ht-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <p class="text-sm font-medium">{{ __('tools.gains.placeholder_results') }}</p>
                    </div>

                    <div x-show="hasCalculated" x-transition class="space-y-6" style="display: none;">
                        <div class="bg-ht-navy rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
                            {{-- Efeito visual de fundo --}}
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-ht-accent opacity-20 rounded-full blur-2xl"></div>
                            
                            <h3 class="text-xs font-bold text-slate-300 mb-2 uppercase tracking-widest">{{ __('tools.gains.result_tax_title') }}</h3>
                            <div class="text-5xl font-black mb-8 text-ht-accent tracking-tighter" x-text="results.estimated_tax_fmt + ' €'"></div>
                            
                            <div class="grid grid-cols-1 gap-4 border-t border-white/10 pt-6 text-sm relative z-10">
                                <div>
                                    <div class="text-xs text-slate-400 font-medium mb-1">{{ __('tools.gains.result_gross_gain') }}</div>
                                    <div class="text-xl font-bold text-white" x-text="results.gross_gain_fmt + ' €'"></div>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-400 font-medium mb-1">{{ __('tools.gains.result_taxable_gain') }}</div>
                                    <div class="text-xl font-bold text-white" x-text="results.taxable_gain_fmt + ' €'"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden text-sm">
                            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 font-bold text-ht-navy uppercase text-xs tracking-widest">
                                {{ __('tools.gains.details_title') }}
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                    <span class="text-slate-500">{{ __('tools.gains.details_sale_value') }}</span>
                                    <span class="font-bold text-ht-navy" x-text="results.sale_fmt + ' €'"></span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                    <span class="text-slate-500">{{ __('tools.gains.details_coefficient') }}</span>
                                    <span class="font-medium text-ht-navy" x-text="results.coefficient"></span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                    <span class="text-slate-500">{{ __('tools.gains.details_acquisition_corrected') }}</span>
                                    <span class="font-medium text-red-600" x-text="'- ' + results.acquisition_updated_fmt + ' €'"></span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                                    <span class="text-slate-500">{{ __('tools.gains.details_expenses') }}</span>
                                    <span class="font-medium text-red-600" x-text="'- ' + results.expenses_fmt + ' €'"></span>
                                </div>
                                
                                <div class="flex justify-between items-center pt-2 bg-slate-50 -mx-6 px-6 py-3 border-y border-slate-100">
                                    <span class="font-bold text-ht-navy">{{ __('tools.gains.details_gross_gain') }}</span>
                                    <span class="font-bold text-green-600" x-text="results.gross_gain_fmt + ' €'"></span>
                                </div>

                                <div x-show="results.reinvestment_fmt !== '0,00'" class="pt-2">
                                    <button @click="showDetails = !showDetails" class="w-full flex justify-between items-center text-xs font-bold uppercase tracking-wide text-ht-accent hover:text-ht-navy transition-colors">
                                        <span>{{ __('tools.gains.view_exemption_details') }}</span>
                                        <svg class="w-4 h-4 transform transition-transform" :class="showDetails ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    
                                    <div x-show="showDetails" x-collapse class="mt-3 bg-ht-accent/5 p-4 rounded-xl text-xs text-slate-600 space-y-2">
                                        <div class="flex justify-between">
                                            <span>{{ __('tools.gains.details_reinvested') }}</span>
                                            <span class="font-bold text-ht-navy" x-text="results.reinvestment_fmt + ' €'"></span>
                                        </div>
                                        <p class="italic text-[10px] text-slate-400 border-t border-slate-200 pt-2 mt-2">
                                            {{ __('tools.gains.note_exemption_calc') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 text-xs text-blue-800 leading-relaxed">
                            <strong class="block mb-2 font-bold text-blue-900">{{ __('tools.gains.legal_note_title') }}</strong>
                            {{ __('tools.gains.legal_note_text') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL DE LEAD --}}
    <div x-show="showLeadModal" @keydown.escape.window="showLeadModal = false" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            
            {{-- Overlay Escuro --}}
            <div x-show="showLeadModal" x-transition.opacity class="fixed inset-0 bg-ht-navy/80 backdrop-blur-sm transition-opacity" @click="showLeadModal = false"></div>

            {{-- Conteúdo do Modal --}}
            <div x-show="showLeadModal" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-10">
                
                <div class="px-8 pt-8 pb-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-ht-accent/10 mb-6">
                            <svg class="h-8 w-8 text-ht-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-ht-navy mb-2">{{ __('tools.gains.modal_title') }}</h3>
                        <p class="text-sm text-slate-500 mb-8">{{ __('tools.gains.modal_desc') }}</p>
                        
                        <div class="space-y-4 text-left">
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">{{ __('tools.gains.input_name') }}</label>
                                <input type="text" x-model="form.lead_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-1">{{ __('tools.gains.input_email') }}</label>
                                <input type="email" x-model="form.lead_email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-accent">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-8 py-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="button" @click="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg px-6 py-3 bg-ht-accent text-sm font-bold text-white uppercase tracking-widest hover:bg-opacity-90 focus:outline-none sm:w-auto transition-all">
                        {{ __('tools.gains.btn_get_results') }}
                    </button>
                    <button type="button" @click="showLeadModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-6 py-3 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:w-auto transition-all">
                        {{ __('tools.gains.btn_cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function gainsForm() {
        return {
            hasCalculated: false,
            showLeadModal: false,
            showDetails: false, 
            form: {
                acquisition_value: '',
                acquisition_year: 2010,
                acquisition_month: 'Janeiro',
                sale_value: '',
                sale_year: 2025,
                sale_month: 'Janeiro',
                has_expenses: 'Não',
                expenses_works: '',
                expenses_imt: '',
                expenses_commission: '',
                expenses_other: '',
                sold_to_state: 'Não',
                hpp_status: 'Sim',
                amortize_credit: 'Não',
                amortization_amount: '',
                joint_tax_return: 'Não',
                annual_income: '',
                public_support: 'Não',
                public_support_year: 2020,
                public_support_month: 'Janeiro',
                retired_status: 'Não', 
                self_built: 'Não', 
                reinvest_intention: 'Não',
                reinvestment_amount: '',
                lead_name: '',
                lead_email: ''
            },
            results: {
                sale_fmt: '0,00',
                coefficient: '1,00',
                acquisition_updated_fmt: '0,00',
                expenses_fmt: '0,00',
                reinvestment_fmt: '0,00',
                gross_gain_fmt: '0,00',
                taxable_gain_fmt: '0,00',
                estimated_tax_fmt: '0,00',
                status: ''
            },
            
            resetHPPFields() {
                if(this.form.sold_to_state === 'Sim') {
                    this.form.hpp_status = 'Não'; 
                }
                this.resetReinvestmentFields();
            },

            resetReinvestmentFields() {
                 if(this.form.hpp_status !== 'Sim') {
                    this.form.reinvest_intention = 'Não';
                    this.form.reinvestment_amount = '';
                    this.form.retired_status = 'Não'; 
                }
            },
            
            openModal() {
                if(!this.form.acquisition_value || !this.form.sale_value) {
                    alert("{{ __('tools.gains.alert_fill_values') }}");
                    return;
                }
                this.showLeadModal = true;
            },

            async submit() {
                if(!this.form.lead_name || !this.form.lead_email) {
                    alert("{{ __('tools.gains.alert_fill_contact') }}");
                    return;
                }

                try {
                    if(this.form.sold_to_state === 'Sim') {
                        this.form.annual_income = 0; 
                    }

                    // CORREÇÃO CRÍTICA AQUI: Usar url() relativa para evitar CORS
                    // Isso garante que o fetch vá para o domínio atual (margarida.site.com)
                    // e não para o domínio original, se o usuário estiver lá.
                    const response = await fetch('{{ url("/ferramentas/mais-valias/calcular") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.form)
                    });
                    
                    if (!response.ok) {
                        alert('{{ __('tools.gains.alert_error_check') }}');
                        return;
                    }

                    this.results = await response.json();
                    this.hasCalculated = true;
                    this.showLeadModal = false; 
                    
                    this.$nextTick(() => {
                        const resultDiv = this.$el.querySelector('.bg-ht-navy');
                        if(resultDiv) {
                            resultDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });

                } catch (e) {
                    console.error("Erro no cálculo:", e);
                }
            }
        }
    }
</script>

@endsection