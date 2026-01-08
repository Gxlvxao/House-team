@extends('layouts.app')

@section('title', __('legal.terms_title_tag') . ' | House Team')

@section('content')
<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-12 max-w-4xl">
        <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100">
            <h1 class="text-3xl font-serif font-bold text-slate-900 mb-8">{{ __('legal.terms_h1') }}</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-4 font-light">
                <p>{!! __('legal.terms_intro', ['url' => '<strong>https://houseteamconsultores.pt</strong>']) !!}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.terms_h3_1') }}</h3>
                <ul class="list-none pl-0 space-y-1">
                    <li><strong>{{ __('legal.label_owner_name') }}</strong></li>
                    <li>{{ __('legal.label_address') }}: R. Cidade de Bissau, 1800-240 Lisboa — Portugal</li>
                    <li>{{ __('legal.label_phone') }}: +351 923 224 551</li>
                    <li>{{ __('legal.label_email') }}: clientes@houseteamconsultores.pt</li>
                    <li class="text-sm italic text-gray-400">({{ __('legal.terms_nipc_note') }})</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.terms_h3_2') }}</h3>
                <p>{{ __('legal.terms_p2') }}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.terms_h3_3') }}</h3>
                <p>{{ __('legal.terms_p3') }}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.terms_h3_4') }}</h3>
                <p>{{ __('legal.terms_p4_intro') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('legal.terms_li4_1') }}</li>
                    <li>{{ __('legal.terms_li4_2') }}</li>
                    <li>{{ __('legal.terms_li4_3') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.terms_h3_5') }}</h3>
                <p>{{ __('legal.terms_p5') }}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.terms_h3_6') }}</h3>
                <p>{{ __('legal.terms_p6_intro') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('legal.terms_li6_1') }}</li>
                    <li>{{ __('legal.terms_li6_2') }}</li>
                    <li>{{ __('legal.terms_li6_3') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.terms_h3_7') }}</h3>
                <p>{{ __('legal.terms_p7') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection