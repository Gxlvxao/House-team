@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="bg-ht-navy text-white py-20 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    <div class="container mx-auto px-6 relative z-10">
        <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white mb-2">{{ __('tools.credit.title') }}</h1>
        <p class="text-slate-400 text-sm font-medium uppercase tracking-widest">{{ __('tools.credit.subtitle') }}</p>
    </div>
</div>

<section class="py-16 bg-slate-50" x-data="creditCalculator()" x-init="calculate()">
    <div class="container mx-auto px-4 md:px-8 max-w-6xl">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- ÁREA DO FORMULÁRIO --}}
            <div class="lg:col-span-7 space-y-8">
                
                {{-- 1. Valores e Prazos --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-xl font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                        {{ __('tools.credit.section_values') }}
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Valor do Imóvel --}}
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.credit.label_property_value') }}</label>
                                <input type="number" x-model.number="propertyValue" @input="updateLoanAmount()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary text-ht-navy font-bold">
                            </div>

                            {{-- Entrada Inicial --}}
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.credit.label_down_payment') }}</label>
                                <input type="number" x-model.number="downPayment" @input="updateLoanAmount()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary text-ht-navy font-bold">
                            </div>
                        </div>

                        {{-- Montante de Empréstimo (Auto-calculado) --}}
                        <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 flex justify-between items-center">
                            <div>
                                <span class="block text-xs font-bold text-ht-navy uppercase tracking-wide">{{ __('tools.credit.label_loan_amount') }}</span>
                                <span class="text-xs text-blue-600 font-bold" x-text="'LTV: ' + ltv.toFixed(1) + '%'"></span>
                            </div>
                            <div class="text-3xl font-black text-ht-primary">
                                € <span x-text="formatMoney(loanAmount)"></span>
                            </div>
                        </div>
                        <div x-show="ltv > 90" class="text-ht-accent text-xs font-bold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ __('tools.credit.warning_ltv') }}
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Prazo --}}
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.credit.label_term') }}</label>
                                <select x-model.number="years" @change="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary text-ht-navy">
                                    @foreach(range(40, 5) as $y)
                                        <option value="{{ $y }}">{{ $y }} {{ __('tools.credit.label_years') }} ({{ $y * 12 }} {{ __('tools.credit.label_months') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Idade do Titular Mais Velho --}}
                            <div>
                                <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.credit.label_age') }}</label>
                                <input type="number" x-model.number="age" @input="checkMaxTerm()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary text-ht-navy" placeholder="Ex: 35">
                            </div>
                        </div>
                        <div x-show="ageWarning" class="p-3 bg-orange-50 border border-orange-100 rounded-lg text-orange-700 text-xs font-bold flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="ageWarning"></span>
                        </div>

                    </div>
                </div>

                {{-- 2. Taxas de Juro --}}
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-xl font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                        {{ __('tools.credit.section_rates') }}
                    </h3>

                    <div class="space-y-6">
                        
                        {{-- Tipo de Taxa --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-3">{{ __('tools.credit.label_rate_type') }}</label>
                            <div class="flex gap-4">
                                <label class="cursor-pointer flex-1 group">
                                    <input type="radio" name="rateType" value="variable" x-model="rateType" @change="calculate()" class="peer sr-only">
                                    <div class="p-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 text-center text-sm font-bold text-slate-600 peer-checked:text-ht-navy transition-all">
                                        {{ __('tools.credit.type_variable') }}
                                    </div>
                                </label>
                                <label class="cursor-pointer flex-1 group">
                                    <input type="radio" name="rateType" value="fixed" x-model="rateType" @change="calculate()" class="peer sr-only">
                                    <div class="p-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 text-center text-sm font-bold text-slate-600 peer-checked:text-ht-navy transition-all">
                                        {{ __('tools.credit.type_fixed') }}
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Seleção Euribor (Se Variável) --}}
                        <div x-show="rateType === 'variable'" x-transition>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.credit.label_index') }}</label>
                            <select x-model.number="euriborRate" @change="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary text-ht-navy">
                                <option value="2.31">Euribor 12 {{ __('tools.credit.label_months_short') }} (2.31%)</option>
                                <option value="2.17">Euribor 6 {{ __('tools.credit.label_months_short') }} (2.17%)</option>
                                <option value="2.07">Euribor 3 {{ __('tools.credit.label_months_short') }} (2.07%)</option>
                            </select>
                            <p class="text-[10px] text-slate-400 mt-2 font-medium">{{ __('tools.credit.note_ref_values') }}</p>
                        </div>

                        {{-- Taxa Fixa Manual (Se Fixa) --}}
                        <div x-show="rateType === 'fixed'" x-transition>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.credit.label_fixed_rate') }}</label>
                            <input type="number" step="0.01" x-model.number="fixedRate" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary text-ht-navy" placeholder="Ex: 4.0">
                        </div>

                        {{-- Spread --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">{{ __('tools.credit.label_spread') }}</label>
                            <div class="relative">
                                <input type="number" step="0.01" x-model.number="spread" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary text-ht-navy" placeholder="Ex: 0.85">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">%</span>
                            </div>
                        </div>

                        {{-- TAN Total --}}
                        <div class="flex justify-between items-center border-t border-slate-100 pt-4">
                            <span class="text-sm font-bold text-slate-600">{{ __('tools.credit.label_tan') }}</span>
                            <span class="text-xl font-black text-ht-navy" x-text="tan.toFixed(3) + '%'"></span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ÁREA DE RESULTADOS --}}
            <div class="lg:col-span-5">
                <div class="sticky top-32 space-y-6">
                    
                    {{-- Cartão Principal --}}
                    <div class="bg-ht-navy rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 opacity-10">
                            <svg class="w-32 h-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>

                        <h3 class="text-xs font-bold text-slate-300 mb-2 uppercase tracking-widest">{{ __('tools.credit.result_installment_title') }}</h3>
                        <div class="text-5xl font-black mb-8 text-ht-accent tracking-tighter">
                            € <span x-text="formatMoney(monthlyPayment)"></span>
                        </div>

                        <div class="space-y-4 text-sm font-medium text-slate-300 border-t border-white/10 pt-6">
                            <div class="flex justify-between items-center">
                                <span>{{ __('tools.credit.label_installment_breakdown') }}</span>
                                <span class="text-white">€ <span x-text="formatMoney(monthlyPayment)"></span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>{{ __('tools.credit.label_stamp_duty_monthly') }}</span>
                                <span class="text-white">€ <span x-text="formatMoney(monthlyStampDuty)"></span></span>
                            </div>
                        </div>
                    </div>

                    {{-- Cartão Secundário --}}
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-200">
                        <h4 class="text-xs font-bold text-ht-navy uppercase tracking-widest mb-6 border-b border-slate-100 pb-2">{{ __('tools.credit.section_initial_costs') }}</h4>
                        <div class="space-y-4 text-sm mb-8">
                            <div class="flex justify-between text-slate-600">
                                <span>{{ __('tools.credit.label_down_payment') }}</span>
                                <span class="font-bold text-ht-navy">€ <span x-text="formatMoney(downPayment)"></span></span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>{{ __('tools.credit.label_opening_stamp') }}</span>
                                <span class="font-bold text-ht-accent">€ <span x-text="formatMoney(openingStampDuty)"></span></span>
                            </div>
                            <div class="flex justify-between border-t border-slate-100 pt-3 mt-2">
                                <span class="font-black text-ht-navy">{{ __('tools.credit.label_total_cash') }}</span>
                                <span class="font-black text-ht-navy">€ <span x-text="formatMoney(upfrontTotal)"></span></span>
                            </div>
                        </div>

                        <h4 class="text-xs font-bold text-ht-navy uppercase tracking-widest mb-6 border-b border-slate-100 pb-2">{{ __('tools.credit.section_analysis') }}</h4>
                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between text-slate-600">
                                <span>{{ __('tools.credit.label_capital_repaid') }}</span>
                                <span class="font-bold text-ht-navy">€ <span x-text="formatMoney(loanAmount)"></span></span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>{{ __('tools.credit.label_total_interest') }}</span>
                                <span class="font-bold text-ht-accent">€ <span x-text="formatMoney(totalInterest)"></span></span>
                            </div>
                            <div class="flex justify-between border-t border-slate-100 pt-3 mt-2 bg-slate-50 p-3 rounded-lg">
                                <span class="font-bold text-ht-navy">{{ __('tools.credit.label_mtic') }}</span>
                                <span class="font-black text-ht-navy">€ <span x-text="formatMoney(mtic)"></span></span>
                            </div>
                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="text-center">
                        <button @click="showLeadModal = true" class="block w-full bg-ht-accent text-white font-black uppercase tracking-widest py-5 rounded-3xl shadow-lg hover:bg-red-700 hover:shadow-xl transition-all transform hover:-translate-y-1 text-xs">
                            {{ __('tools.credit.btn_report') }}
                        </button>
                        <p class="text-[10px] text-slate-400 mt-3 font-medium">{{ __('tools.credit.disclaimer') }}</p>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DE LEAD --}}
    <div x-show="showLeadModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showLeadModal" class="fixed inset-0 bg-ht-navy/80 backdrop-blur-sm transition-opacity" @click="showLeadModal = false"></div>
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="px-8 pt-8 pb-6">
                    <h3 class="text-2xl font-black text-ht-navy mb-2 text-center">{{ __('tools.credit.modal_title') }}</h3>
                    <p class="text-sm text-slate-500 mb-6 text-center">{{ __('tools.credit.modal_subtitle') }}</p>
                    <div class="space-y-4">
                        <input type="text" x-model="lead_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" placeholder="{{ __('tools.credit.input_name') }}">
                        <input type="email" x-model="lead_email" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm" placeholder="{{ __('tools.credit.input_email') }}">
                    </div>
                </div>
                <div class="bg-slate-50 px-8 py-6 flex flex-col gap-3">
                    <button type="button" @click="submitLead" class="w-full bg-ht-accent text-white font-bold py-3 rounded-xl hover:bg-red-700 transition-all" :disabled="loading">
                        <span x-show="!loading">{{ __('tools.credit.btn_submit') }}</span>
                        <span x-show="loading">{{ __('tools.credit.btn_sending') }}</span>
                    </button>
                    <button @click="showLeadModal = false" class="text-xs text-slate-400 font-bold uppercase hover:text-ht-navy">{{ __('tools.credit.btn_cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
    function creditCalculator() {
        return {
            propertyValue: 250000,
            downPayment: 50000,
            loanAmount: 200000,
            years: 30,
            age: 30,
            rateType: 'variable', 
            euriborRate: 2.17, 
            fixedRate: 4.0,
            spread: 0.85,
            tan: 0,
            
            monthlyPayment: 0,
            monthlyStampDuty: 0,
            monthlyTotal: 0,
            
            openingStampDuty: 0,
            bankFees: 0,
            upfrontTotal: 0,
            
            totalInterest: 0,
            mtic: 0,
            
            ltv: 0,
            ageWarning: '',

            // Variáveis do Modal
            showLeadModal: false,
            loading: false,
            lead_name: '',
            lead_email: '',

            formatMoney(value) {
                // Mantemos o formato PT-PT para consistência do Euro
                return new Intl.NumberFormat('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
            },

            updateLoanAmount() {
                if (this.downPayment > this.propertyValue) this.downPayment = this.propertyValue;
                this.loanAmount = this.propertyValue - this.downPayment;
                this.calculate();
            },

            checkMaxTerm() {
                if (!this.age) {
                    this.ageWarning = '';
                    return;
                }
                const maxAge = 75;
                const projectedAge = this.age + this.years;
                
                let maxTermAllowed = 40;
                if (this.age > 30 && this.age <= 35) maxTermAllowed = 37;
                if (this.age > 35) maxTermAllowed = 35;

                if (this.years > maxTermAllowed) {
                    // Tradução injetada via Blade
                    this.ageWarning = `{{ __('tools.credit.warning_age_limit_1') }} ${this.age} {{ __('tools.credit.warning_age_limit_2') }} ${maxTermAllowed} {{ __('tools.credit.label_years') }}.`;
                } else if (projectedAge > maxAge) {
                    this.ageWarning = `{{ __('tools.credit.warning_age_max_1') }} ${maxAge - this.age} {{ __('tools.credit.label_years') }}.`;
                } else {
                    this.ageWarning = '';
                }
            },

            calculate() {
                // 1. Validar LTV
                if(this.propertyValue > 0) {
                    this.ltv = (this.loanAmount / this.propertyValue) * 100;
                } else {
                    this.ltv = 0;
                }

                this.checkMaxTerm();

                // 2. Definir Taxa Anual Nominal (TAN)
                if (this.rateType === 'variable') {
                    this.tan = this.euriborRate + this.spread;
                } else {
                    this.tan = this.fixedRate; 
                }

                // 3. Cálculo da Prestação (PMT)
                let i = (this.tan / 100) / 12;
                let n = this.years * 12;

                if (i === 0) {
                    this.monthlyPayment = this.loanAmount / n;
                } else {
                    this.monthlyPayment = (this.loanAmount * i) / (1 - Math.pow(1 + i, -n));
                }

                // 4. Imposto de Selo Mensal sobre Juros (4%)
                let firstMonthInterest = this.loanAmount * i;
                this.monthlyStampDuty = firstMonthInterest * 0.04;

                // 6. Total Mensal
                this.monthlyTotal = this.monthlyPayment + this.monthlyStampDuty;

                // 7. Custos Iniciais
                this.openingStampDuty = this.loanAmount * 0.006;
                this.upfrontTotal = this.downPayment + this.openingStampDuty;

                // 8. Totais Finais
                let totalPayments = this.monthlyPayment * n;
                this.totalInterest = totalPayments - this.loanAmount;
                
                let totalStampOnInterest = this.totalInterest * 0.04;
                this.mtic = totalPayments + totalStampOnInterest + this.openingStampDuty;
            },

            async submitLead() {
                if(!this.lead_name || !this.lead_email) { 
                    alert('{{ __('tools.credit.alert_fill') }}'); 
                    return; 
                }
                this.loading = true;
                
                try {
                    const response = await fetch('{{ route('tools.credit.send') }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            propertyValue: this.propertyValue,
                            loanAmount: this.loanAmount,
                            years: this.years,
                            tan: this.tan,
                            monthlyPayment: this.monthlyPayment,
                            mtic: this.mtic,
                            lead_name: this.lead_name,
                            lead_email: this.lead_email
                        })
                    });

                    if (!response.ok) throw new Error('Falha no envio');

                    alert('{{ __('tools.credit.alert_success') }}');
                    this.showLeadModal = false;
                    this.lead_name = '';
                    this.lead_email = '';
                } catch(e) {
                    console.error(e);
                    alert('{{ __('tools.credit.alert_error') }}');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>

@endsection