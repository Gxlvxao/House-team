@extends('layouts.app')

@section('content')

{{-- Cabeçalho House Team --}}
<div class="bg-ht-navy text-white py-20 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
    <div class="container mx-auto px-6 relative z-10">
        <h1 class="text-4xl md:text-5xl font-black tracking-tight text-white mb-2">Simulador de IMT e Selo 2025</h1>
        <p class="text-slate-400 text-sm font-medium uppercase tracking-widest">Calcule os impostos de aquisição do seu imóvel.</p>
    </div>
</div>

<section class="py-16 bg-slate-50" x-data="imtCalculator()" x-init="calculate()">
    <div class="container mx-auto px-4 md:px-8 max-w-6xl">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- ÁREA DO FORMULÁRIO --}}
            <div class="lg:col-span-7 space-y-8">
                
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-xl font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                        Dados para a simulação
                    </h3>
                    
                    <div class="space-y-6">
                        
                        {{-- Local do imóvel --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-3">Local do imóvel</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="location" value="continente" x-model="location" @change="calculate()" class="peer sr-only">
                                    <div class="px-4 py-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 transition-all text-sm font-bold text-slate-600 peer-checked:text-ht-navy text-center group-hover:border-ht-navy/30">
                                        Portugal Continental
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" name="location" value="ilhas" x-model="location" @change="calculate()" class="peer sr-only">
                                    <div class="px-4 py-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 transition-all text-sm font-bold text-slate-600 peer-checked:text-ht-navy text-center group-hover:border-ht-navy/30">
                                        Regiões Autónomas
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Finalidade do imóvel --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Finalidade do imóvel</label>
                            <div class="relative">
                                <select x-model="purpose" @change="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary text-ht-navy font-medium appearance-none">
                                    <option value="hpp">Habitação Própria e Permanente</option>
                                    <option value="secundaria">Habitação Secundária ou Arrendamento</option>
                                    <option value="rustico">Prédios Rústicos</option>
                                    <option value="urbano">Prédios Urbanos e Outras Aquisições</option>
                                    <option value="offshore_pessoal">Adquirente em paraíso fiscal (Particular)</option>
                                    <option value="offshore_entidade">Adquirente em paraíso fiscal (Empresa)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Preço do imóvel --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Preço do imóvel</label>
                            <div class="relative">
                                <input type="number" x-model.number="propertyValue" @input="calculate()" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary text-ht-navy font-bold placeholder-slate-400" placeholder="0,00">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">€</span>
                            </div>
                        </div>

                        {{-- Número de compradores --}}
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-3">Número de compradores</label>
                            <div class="flex gap-4">
                                <label class="cursor-pointer flex-1 group">
                                    <input type="radio" name="buyers" :value="1" x-model.number="buyersCount" @change="calculate()" class="peer sr-only">
                                    <div class="px-4 py-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 transition-all text-center text-sm font-bold text-slate-600 peer-checked:text-ht-navy group-hover:border-ht-navy/30">
                                        1
                                    </div>
                                </label>
                                <label class="cursor-pointer flex-1">
                                    <input type="radio" name="buyers" :value="2" x-model.number="buyersCount" @change="calculate()" class="peer sr-only">
                                    <div class="px-4 py-3 rounded-xl border border-slate-200 peer-checked:border-ht-accent peer-checked:bg-ht-accent/5 transition-all text-center text-sm font-bold text-slate-600 peer-checked:text-ht-navy group-hover:border-ht-navy/30">
                                        2
                                    </div>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Informação Compradores (AGORA VISÍVEL SEMPRE) --}}
                <div x-transition class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
                    <h3 class="text-xl font-bold text-ht-navy border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <span class="bg-ht-accent text-white w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                        Informação relativa aos compradores
                    </h3>
                    
                    <div class="space-y-6">
                        
                        {{-- Comprador 1 --}}
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <span class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-4 block">Comprador 1</span>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-ht-navy mb-2">Idade</label>
                                    <input type="number" x-model.number="buyer1Age" @input="checkAge(1); calculate()" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary" placeholder="Ex: 30">
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-1 mb-2">
                                        <label class="block text-xs font-bold text-ht-navy">Elegível IMT Jovem?</label>
                                        <div class="group relative cursor-help">
                                            <span class="bg-slate-200 text-slate-500 rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-bold">?</span>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-ht-navy text-white text-xs p-2 rounded hidden group-hover:block z-20 text-center shadow-lg">
                                                Até 35 anos e 1ª habitação própria permanente. **Apenas aplicado se a finalidade for HPP.**
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <button type="button" @click="setBuyerEligible(1, true)" :class="buyer1Eligible ? 'bg-ht-navy text-white border-ht-navy' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-100'" class="flex-1 py-2 text-xs border rounded-lg transition-all font-bold uppercase">Sim</button>
                                        <button type="button" @click="setBuyerEligible(1, false)" :class="!buyer1Eligible ? 'bg-slate-200 text-slate-700 border-slate-300' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-100'" class="flex-1 py-2 text-xs border rounded-lg transition-all font-bold uppercase">Não</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Comprador 2 (Condicional) --}}
                        <div x-show="buyersCount === 2" x-transition class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <span class="text-xs font-bold uppercase text-slate-400 tracking-wider mb-4 block">Comprador 2</span>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-ht-navy mb-2">Idade</label>
                                    <input type="number" x-model.number="buyer2Age" @input="checkAge(2); calculate()" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ht-primary" placeholder="Ex: 36">
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-1 mb-2">
                                        <label class="block text-xs font-bold text-ht-navy">Elegível IMT Jovem?</label>
                                        <div class="group relative cursor-help">
                                            <span class="bg-slate-200 text-slate-500 rounded-full w-4 h-4 flex items-center justify-center text-[10px] font-bold">?</span>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-ht-navy text-white text-xs p-2 rounded hidden group-hover:block z-20 text-center shadow-lg">
                                                Até 35 anos e 1ª habitação própria permanente. **Apenas aplicado se a finalidade for HPP.**
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2">
                                        <button type="button" @click="setBuyerEligible(2, true)" :class="buyer2Eligible ? 'bg-ht-navy text-white border-ht-navy' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-100'" class="flex-1 py-2 text-xs border rounded-lg transition-all font-bold uppercase">Sim</button>
                                        <button type="button" @click="setBuyerEligible(2, false)" :class="!buyer2Eligible ? 'bg-slate-200 text-slate-700 border-slate-300' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-100'" class="flex-1 py-2 text-xs border rounded-lg transition-all font-bold uppercase">Não</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Botão Simular (Mobile) --}}
                <div class="mt-8 lg:hidden">
                    <button @click="scrollToResults" class="w-full bg-ht-navy text-white font-black uppercase tracking-widest py-4 rounded-xl shadow-lg hover:bg-ht-primary transition-all">
                        Ver Resultados
                    </button>
                </div>

            </div>

            {{-- ÁREA DE RESULTADOS --}}
            <div class="lg:col-span-5" id="results-area">
                <div class="sticky top-32 space-y-6">
                    
                    {{-- Cartão Principal --}}
                    <div class="bg-ht-navy rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-6 opacity-10">
                            <svg class="w-32 h-32 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>

                        <div class="flex justify-between items-start mb-6 border-b border-white/10 pb-4 relative z-10">
                            <h3 class="text-xl font-bold text-white tracking-tight">Resultados</h3>
                            
                            {{-- Botão de Transparência do Cálculo --}}
                            <button @click="showBreakdown = !showBreakdown" class="text-xs font-bold text-slate-400 hover:text-white transition-colors flex items-center gap-1 uppercase">
                                <svg x-show="!showBreakdown" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M10 12h.01"/></svg>
                                <svg x-show="showBreakdown" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                <span x-text="showBreakdown ? 'Fechar Detalhes' : 'Detalhes do Cálculo'"></span>
                            </button>
                        </div>

                        {{-- Seção de Detalhes do Cálculo (Transparência) --}}
                        <div x-show="showBreakdown" x-transition:enter.duration.300ms x-transition:leave.duration.300ms class="bg-white/5 p-4 mb-6 rounded-2xl border border-white/10 text-xs relative z-10">
                            <h4 class="font-bold mb-3 text-ht-accent uppercase tracking-wider">Transparência do Cálculo (IMT)</h4>
                            
                            <div class="space-y-1 text-slate-300">
                                <div class="flex justify-between">
                                    <span>Valor Escritura / VPT</span>
                                    <span class="font-bold">€ <span x-text="formatMoney(imtBreakdown.taxableValue)"></span></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Número de Adquirentes</span>
                                    <span class="font-bold" x-text="buyersCount"></span>
                                </div>
                            </div>

                            <div class="space-y-1 text-slate-300 mt-4 border-t border-white/10 pt-3">
                                <p class="font-bold text-sm text-white mb-2" x-text="'Regra IMT Aplicada: ' + imtBreakdown.rateText"></p>
                                
                                <template x-if="imtBreakdown.isJovemBenefit && imtBreakdown.isMarginal">
                                    <p class="text-slate-400">IMT Jovem: Aplicado 8% sobre o excedente a <span x-text="formatMoney(imtBreakdown.marginalExemption) + '€'"></span>.</p>
                                </template>
                                
                                <div x-show="imtBreakdown.rateText.includes('Progressiva')" class="text-slate-400">
                                    Cálculo: (Valor Aquisição x Taxa Marginal) - Parcela a Abater.
                                </div>
                            </div>

                            <div class="flex justify-between text-white border-t border-white/10 pt-3 mt-3">
                                <span class="font-bold">IMT Total (Soma das Quotas)</span>
                                <span class="font-black text-ht-accent">€ <span x-text="formatMoney(imtBreakdown.finalIMT)"></span></span>
                            </div>
                            
                        </div>
                        
                        {{-- Resultados Principais --}}
                        <div class="space-y-5 relative z-10">
                            <div class="flex justify-between items-center text-sm font-medium">
                                <span class="text-slate-400">IMT</span>
                                <span class="font-bold text-lg">€ <span x-text="formatMoney(finalIMT)"></span></span>
                            </div>
                            
                            <div class="flex justify-between items-center text-sm font-medium border-b border-white/10 pb-5">
                                <span class="text-slate-400">Imposto de Selo (0.8%)</span>
                                <span class="font-bold text-lg">€ <span x-text="formatMoney(finalStamp)"></span></span>
                            </div>

                            <div class="bg-white/10 p-6 rounded-2xl border border-white/5 backdrop-blur-sm">
                                <p class="text-xs uppercase tracking-widest text-ht-accent font-bold mb-2">Total a Pagar</p>
                                <p class="text-4xl font-black text-white tracking-tighter">€ <span x-text="formatMoney(totalPayable)"></span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Call to Action --}}
                    <div class="text-center space-y-4 pt-4">
                        <p class="text-sm font-medium text-slate-600">O seu próximo passo para a nova casa.</p>
                        <a href="{{ route('tools.credit') }}" class="block w-full bg-ht-accent text-white font-black uppercase tracking-widest py-4 rounded-xl hover:bg-red-700 transition-all text-xs shadow-lg transform hover:-translate-y-1">
                            Simular Crédito Habitação
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    function imtCalculator() {
        return {
            location: 'continente',
            purpose: 'hpp',
            propertyValue: 250000,
            buyersCount: 1,
            
            buyer1Age: 30, 
            buyer1Eligible: true,
            
            buyer2Age: '',
            buyer2Eligible: false,

            finalIMT: 0,
            finalStamp: 0,
            totalPayable: 0,
            
            // Variáveis de Transparência
            imtBreakdown: {
                taxableValue: 0,
                rateText: 'N/A',
                abatement: 0,
                finalIMT: 0,
                isJovemBenefit: false,
                isMarginal: false,
                marginalExemption: 0,
                marginalRate: 0,
            },
            showBreakdown: false,

            setBuyerEligible(buyerIndex, value) {
                if (buyerIndex === 1) this.buyer1Eligible = value;
                if (buyerIndex === 2) this.buyer2Eligible = value;
                this.calculate();
            },

            formatMoney(value) {
                return new Intl.NumberFormat('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
            },

            scrollToResults() {
                const el = document.getElementById('results-area');
                if(el) el.scrollIntoView({ behavior: 'smooth' });
                this.calculate();
            },

            checkAge(buyerIndex) {
                // Desativa automaticamente se a idade for > 35
                if (buyerIndex === 1) {
                    if (this.buyer1Age > 35) this.buyer1Eligible = false;
                }
                if (buyerIndex === 2) {
                    if (this.buyer2Age > 35) this.buyer2Eligible = false;
                }
            },

            // Retorna o IMT TOTAL (sem considerar divisão de compradores) baseado nas tabelas
            calculateNormalIMT(valor, tabela) {
                let taxa = 0;
                let parcelaAbater = 0;
                
                // --- TABELAS IMT 2025 ---
                // HPP Continente
                if (tabela === 'hpp_continente') {
                    if (valor <= 104261) { taxa = 0; parcelaAbater = 0; }
                    else if (valor <= 142618) { taxa = 0.02; parcelaAbater = 2085.22; }
                    else if (valor <= 194458) { taxa = 0.05; parcelaAbater = 6363.76; }
                    else if (valor <= 324058) { taxa = 0.07; parcelaAbater = 10252.92; }
                    else if (valor <= 648022) { taxa = 0.08; parcelaAbater = 13493.50; }
                    // Taxas únicas
                    else if (valor <= 1128287) { return valor * 0.06; }
                    else { return valor * 0.075; }
                    
                    return Math.max(0, (valor * taxa) - parcelaAbater);
                }

                // HPP Ilhas
                if (tabela === 'hpp_ilhas') {
                    if (valor <= 130326) { taxa = 0; parcelaAbater = 0; }
                    else if (valor <= 178273) { taxa = 0.02; parcelaAbater = 2606.52; }
                    else if (valor <= 243073) { taxa = 0.05; parcelaAbater = 7954.71; }
                    else if (valor <= 405073) { taxa = 0.07; parcelaAbater = 12816.17; }
                    else if (valor <= 810145) { taxa = 0.08; parcelaAbater = 16866.90; }
                    // Taxas únicas
                    else if (valor <= 1410359) { return valor * 0.06; }
                    else { return valor * 0.075; }

                    return Math.max(0, (valor * taxa) - parcelaAbater);
                }

                // Secundária Continente
                if (tabela === 'secundaria_continente') {
                    if (valor <= 104261) { taxa = 0.01; parcelaAbater = 0; }
                    else if (valor <= 142618) { taxa = 0.02; parcelaAbater = 1042.61; }
                    else if (valor <= 194458) { taxa = 0.05; parcelaAbater = 5321.15; }
                    else if (valor <= 324058) { taxa = 0.07; parcelaAbater = 9210.31; }
                    else if (valor <= 621501) { taxa = 0.08; parcelaAbater = 12450.89; }
                    // Taxas únicas
                    else if (valor <= 1128287) { return valor * 0.06; }
                    else { return valor * 0.075; }
                    
                    return Math.max(0, (valor * taxa) - parcelaAbater);
                }

                // Secundária Ilhas
                if (tabela === 'secundaria_ilhas') {
                    if (valor <= 130326) { taxa = 0.01; parcelaAbater = 0; }
                    else if (valor <= 178273) { taxa = 0.02; parcelaAbater = 1303.26; }
                    else if (valor <= 243073) { taxa = 0.05; parcelaAbater = 6651.45; }
                    else if (valor <= 405073) { taxa = 0.07; parcelaAbater = 11512.91; }
                    else if (valor <= 776876) { taxa = 0.08; parcelaAbater = 15563.64; }
                    // Taxas únicas
                    else if (valor <= 1410359) { return valor * 0.06; }
                    else { return valor * 0.075; }

                    return Math.max(0, (valor * taxa) - parcelaAbater);
                }

                return 0;
            },

            // Calcula o IMT para Jovem (Total)
            calculateYoungIMT(valor, location) {
                // Limites OE2025
                const limitIsencao = location === 'continente' ? 324058 : 405073;
                const limitParcial = location === 'continente' ? 648022 : 810145;
                const taxaExcedente = 0.08;

                if (valor <= limitIsencao) {
                    return 0; // Isenção Total
                } else if (valor <= limitParcial) {
                    // Isenção Parcial: Paga 8% sobre o excedente
                    return (valor - limitIsencao) * taxaExcedente;
                } else {
                    // Sem isenção: Calcula como Normal
                    const tabela = location === 'continente' ? 'hpp_continente' : 'hpp_ilhas';
                    return this.calculateNormalIMT(valor, tabela);
                }
            },

            calculate() {
                let valorTotal = this.propertyValue || 0;
                
                if (valorTotal <= 0) {
                    this.finalIMT = 0;
                    this.finalStamp = 0;
                    this.totalPayable = 0;
                    this.imtBreakdown = { taxableValue: 0, rateText: 'N/A', abatement: 0, finalIMT: 0, isJovemBenefit: false, isMarginal: false, marginalExemption: 0, marginalRate: 0 };
                    return;
                }

                let imtBaseNormal = 0;
                let rateSelo = 0.008; // Taxa padrão do Imposto de Selo (0.8%)
                let isHPP = this.purpose === 'hpp';
                let isContinente = this.location === 'continente';
                let imtBreakdownText = '';

                // 1. Determinar Taxas Normais (Sem Jovem) e Breakdown Text
                if (this.purpose === 'rustico') {
                    imtBaseNormal = valorTotal * 0.05;
                    imtBreakdownText = '5% (Taxa Única) sobre o valor total';
                } else if (this.purpose === 'urbano') {
                    imtBaseNormal = valorTotal * 0.065;
                    imtBreakdownText = '6.5% (Taxa Única) sobre o valor total';
                } else if (this.purpose === 'offshore_pessoal' || this.purpose === 'offshore_entidade') {
                    imtBaseNormal = valorTotal * 0.10;
                    // rateSelo = 0.008; // Mantemos o IS em 0.8% (0.008)
                    imtBreakdownText = '10% (Taxa de Paraíso Fiscal) sobre o valor total';
                } else {
                    let tabela = isHPP ? 
                                (isContinente ? 'hpp_continente' : 'hpp_ilhas') : 
                                (isContinente ? 'secundaria_continente' : 'secundaria_ilhas');
                    
                    imtBaseNormal = this.calculateNormalIMT(valorTotal, tabela);
                    
                    imtBreakdownText = isHPP ? 'Tabela Progressiva HPP Normal' : 'Tabela Progressiva Habitação Secundária';
                }

                // 2. Determinar IMT e Selo se fosse 100% Jovem (Apenas se HPP)
                let imtBaseJovem = imtBaseNormal;
                let seloBaseJovem = valorTotal * rateSelo;
                let isJovemBenefitApplied = false;
                let youngBuyersCount = 0;
                
                // Variáveis para Quota IMT Jovem
                const isBuyer1Eligible = this.buyer1Eligible && this.buyer1Age <= 35;
                const isBuyer2Eligible = this.buyersCount === 2 && this.buyer2Eligible && this.buyer2Age <= 35;
                youngBuyersCount = (isBuyer1Eligible ? 1 : 0) + (isBuyer2Eligible ? 1 : 0);


                if (isHPP && youngBuyersCount > 0) {
                    isJovemBenefitApplied = true;
                    const limitIsencao = isContinente ? 324058 : 405073;
                    const limitParcial = isContinente ? 648022 : 810145;
                    
                    if (valorTotal <= limitParcial) {
                        imtBaseJovem = this.calculateYoungIMT(valorTotal, this.location);
                        
                        // Selo Jovem
                        if (valorTotal <= limitIsencao) {
                            seloBaseJovem = 0;
                        } else {
                            // Selo sobre o excedente
                            seloBaseJovem = (valorTotal - limitIsencao) * 0.008;
                        }
                        
                        // Captura do Breakdown Jovem
                        if (valorTotal <= limitIsencao) {
                            imtBreakdownText = '0% (Isenção Total IMT Jovem)';
                            this.imtBreakdown.isMarginal = false;
                            this.imtBreakdown.marginalExemption = valorTotal;
                        } else if (valorTotal > limitIsencao && valorTotal <= limitParcial) {
                            imtBreakdownText = '8% (Taxa Marginal sobre Excedente IMT Jovem)';
                            this.imtBreakdown.isMarginal = true;
                            this.imtBreakdown.marginalExemption = limitIsencao;
                            this.imtBreakdown.marginalRate = 8;
                        }
                    } else {
                         imtBreakdownText = 'Taxa HPP Normal (Acima do limite Jovem)';
                    }
                    
                    if (youngBuyersCount < this.buyersCount) {
                        imtBreakdownText += ` - ${youngBuyersCount}/${this.buyersCount} compradores elegíveis`;
                    }
                }
                
                this.imtBreakdown.isJovemBenefit = isJovemBenefitApplied;


                // 3. Dividir por Compradores (Quota Parte)
                let buyers = this.buyersCount;
                let finalIMT = 0;
                let finalStamp = 0;

                for (let i = 1; i <= buyers; i++) {
                    let isEligible = false;
                    
                    // A elegibilidade IMT Jovem só é levada em conta se for HPP
                    if (this.purpose === 'hpp') {
                        if (i === 1 && isBuyer1Eligible) isEligible = true;
                        if (i === 2 && isBuyer2Eligible) isEligible = true;
                    }

                    if (isEligible) {
                        // Comprador Jovem paga a sua quota do IMT Jovem
                        finalIMT += (imtBaseJovem / buyers);
                        finalStamp += (seloBaseJovem / buyers);
                    } else {
                        // Comprador Normal paga a sua quota do IMT Normal
                        finalIMT += (imtBaseNormal / buyers);
                        finalStamp += ((valorTotal * rateSelo) / buyers);
                    }
                }

                // 4. Atribuir Resultados
                this.finalIMT = finalIMT;
                this.finalStamp = finalStamp;
                this.totalPayable = finalIMT + finalStamp;

                // 5. Atribuir Breakdown Final
                this.imtBreakdown.rateText = imtBreakdownText;
                this.imtBreakdown.taxableValue = valorTotal;
                this.imtBreakdown.finalIMT = finalIMT;
            }
        }
    }
</script>

@endsection