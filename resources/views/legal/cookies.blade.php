@extends('layouts.app')

@section('title', __('legal.cookies_title_tag') . ' | House Team')

@section('content')
<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-6 lg:px-12 max-w-4xl">
        <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100">
            <h1 class="text-3xl font-serif font-bold text-slate-900 mb-8">{{ __('legal.cookies_h1') }}</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-4 font-light">
                <p>{!! __('legal.cookies_intro', ['url' => '<strong>https://houseteamconsultores.pt</strong>']) !!}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.cookies_h3_1') }}</h3>
                <p>{{ __('legal.cookies_p1') }}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.cookies_h3_2') }}</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>{{ __('legal.cookies_li1_label') }}:</strong> {{ __('legal.cookies_li1_text') }}</li>
                    <li><strong>{{ __('legal.cookies_li2_label') }}:</strong> {{ __('legal.cookies_li2_text') }}</li>
                    <li><strong>{{ __('legal.cookies_li3_label') }}:</strong> {{ __('legal.cookies_li3_text') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.cookies_h3_3') }}</h3>
                <p>{{ __('legal.cookies_p3_intro') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('legal.cookies_li4') }}</li>
                    <li>{{ __('legal.cookies_li5') }}</li>
                    <li>{{ __('legal.cookies_li6') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.cookies_h3_4') }}</h3>
                <p>{{ __('legal.cookies_p4') }}</p>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">{{ __('legal.cookies_h3_5') }}</h3>
                <p>{{ __('legal.cookies_p5') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection