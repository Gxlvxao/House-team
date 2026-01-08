@extends('layouts.app')

@section('title', __('legal.disclaimer_title_tag') . ' | House Team')

@section('content')
<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-12 max-w-4xl">
        <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100">
            <h1 class="text-3xl font-serif font-bold text-slate-900 mb-8">{{ __('legal.disclaimer_h1') }}</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-4 font-light">
                
                {{-- 1. Identificação --}}
                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.disclaimer_h3_1') }}</h3>
                <ul class="list-none pl-0 space-y-1">
                    <li><strong>{{ __('legal.label_website') }}:</strong> https://houseteamconsultores.pt</li>
                    <li><strong>{{ __('legal.label_owner') }}:</strong> House Team Consultores</li>
                    <li><strong>{{ __('legal.label_address') }}:</strong> R. Cidade de Bissau, 1800-240 Lisboa — Portugal</li>
                    <li><strong>{{ __('legal.label_phone') }}:</strong> +351 923 224 551</li>
                    <li><strong>{{ __('legal.label_email') }}:</strong> clientes@houseteamconsultores.pt</li>
                    <li class="text-sm italic text-gray-400">({{ __('legal.disclaimer_nipc_note') }})</li>
                </ul>

                {{-- 2. Natureza da Informação --}}
                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.disclaimer_h3_2') }}</h3>
                <p>{{ __('legal.disclaimer_p2_intro') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('legal.disclaimer_li2_1') }}</li>
                    <li>{{ __('legal.disclaimer_li2_2') }}</li>
                    <li>{{ __('legal.disclaimer_li2_3') }}</li>
                </ul>

                {{-- 3. Limitação de Responsabilidade --}}
                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.disclaimer_h3_3') }}</h3>
                <p>{{ __('legal.disclaimer_p3_intro') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('legal.disclaimer_li3_1') }}</li>
                    <li>{{ __('legal.disclaimer_li3_2') }}</li>
                    <li>{{ __('legal.disclaimer_li3_3') }}</li>
                </ul>

                {{-- 4. Legislação Aplicável --}}
                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.disclaimer_h3_4') }}</h3>
                <p>{{ __('legal.disclaimer_p4') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection