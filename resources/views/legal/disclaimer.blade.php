@extends('layouts.app')

@section('title', 'Aviso Legal | House Team')

@section('content')
<div class="py-20 bg-gray-50">
    <div class="max-container px-6 lg:px-12">
        <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100">
            <h1 class="text-3xl font-serif font-bold text-slate-900 mb-8">Aviso Legal</h1>
            
            <div class="prose max-w-none text-gray-600 space-y-4 font-light">
                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">1. Identificação</h3>
                <ul class="list-none pl-0 space-y-1">
                    <li><strong>Website:</strong> https://houseteamconsultores.pt</li>
                    <li><strong>Titular:</strong> House Team Consultores</li>
                    <li><strong>Morada:</strong> R. Cidade de Bissau, 1800-240 Lisboa — Portugal</li>
                    <li><strong>Telefone:</strong> +351 923 224 551</li>
                    <li><strong>E-mail:</strong> clientes@houseteamconsultores.pt</li>
                    <li class="text-sm italic text-gray-400">(O NIPC não se encontra indicado à data da redação do presente Aviso Legal.)</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">2. Natureza da Informação</h3>
                <p>As informações disponibilizadas no website:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>São de caráter geral e informativo</li>
                    <li>Não constituem oferta contratual ou vinculativa</li>
                    <li>Não substituem aconselhamento profissional especializado</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">3. Limitação de Responsabilidade</h3>
                <p>A House Team Consultores não se responsabiliza por:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Erros, omissões ou desatualizações da informação</li>
                    <li>Danos diretos ou indiretos resultantes da utilização do website</li>
                    <li>Conteúdos de terceiros acessíveis através de links externos</li>
                </ul>

                <h3 class="text-xl font-bold text-slate-800 mt-8 mb-2">4. Legislação Aplicável</h3>
                <p>O presente Aviso Legal rege-se pela legislação portuguesa, sendo competentes os tribunais portugueses para a resolução de quaisquer litígios.</p>
            </div>
        </div>
    </div>
</div>
@endsection