@extends('layouts.app')

@section('title', __('legal.privacy_title_tag') . ' | House Team')

@section('content')
<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-12 max-w-4xl">
        <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100">
            <h1 class="text-3xl font-serif font-bold text-slate-900 mb-8">{{ __('legal.privacy_h1') }}</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-4 font-light">
                <p>{!! __('legal.privacy_intro', ['url' => '<strong>https://houseteamconsultores.pt</strong>', 'company' => '<strong>House Team Consultores</strong>']) !!}</p>
                <p>{{ __('legal.privacy_intro_2') }}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.privacy_h3_1') }}</h3>
                <p>{{ __('legal.privacy_p1_intro') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('legal.privacy_li1_1') }}</li>
                    <li>{{ __('legal.privacy_li1_2') }}</li>
                    <li>{{ __('legal.privacy_li1_3') }}</li>
                    <li>{{ __('legal.privacy_li1_4') }}</li>
                    <li>{{ __('legal.privacy_li1_5') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.privacy_h3_2') }}</h3>
                <p>{{ __('legal.privacy_p2_intro') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('legal.privacy_li2_1') }}</li>
                    <li>{{ __('legal.privacy_li2_2') }}</li>
                    <li>{{ __('legal.privacy_li2_3') }}</li>
                    <li>{{ __('legal.privacy_li2_4') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.privacy_h3_3') }}</h3>
                <p>{{ __('legal.privacy_p3_intro') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('legal.privacy_li3_1') }}</li>
                    <li>{{ __('legal.privacy_li3_2') }}</li>
                    <li>{{ __('legal.privacy_li3_3') }}</li>
                    <li>{{ __('legal.privacy_li3_4') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.privacy_h3_4') }}</h3>
                <p>{{ __('legal.privacy_p4') }}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.privacy_h3_5') }}</h3>
                <p>{{ __('legal.privacy_p5') }}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.privacy_h3_6') }}</h3>
                <p>{{ __('legal.privacy_p6_intro') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('legal.privacy_li6_1') }}</li>
                    <li>{{ __('legal.privacy_li6_2') }}</li>
                    <li>{{ __('legal.privacy_li6_3') }}</li>
                    <li>{{ __('legal.privacy_li6_4') }}</li>
                    <li>{{ __('legal.privacy_li6_5') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.privacy_h3_7') }}</h3>
                <p>{{ __('legal.privacy_p7') }}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.privacy_h3_8') }}</h3>
                <p>{{ __('legal.privacy_p8') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection