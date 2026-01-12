<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Models\Property;
use App\Models\Consultant;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\ConsultantPageController;

// ==============================================================================
// 1. ROTAS DE DOMÍNIO EXTERNO (CONSULTORAS) - PRIORIDADE MÁXIMA
// ==============================================================================
// Captura qualquer domínio que NÃO SEJA o principal ou localhost
Route::domain('{domain}')
    ->where(['domain' => '^(?!houseteamconsultores\.pt|www\.houseteamconsultores\.pt|localhost|127\.0\.0\.1).*$'])
    ->group(function () {
        
        // 1.1 Home da Consultora (Landing Page)
        Route::get('/', [ConsultantPageController::class, 'index'])->name('consultant.home');
        
        // 1.2 Detalhe do Imóvel (Com design da consultora)
        Route::get('/imoveis/{property:slug}', [ConsultantPageController::class, 'showProperty'])->name('consultant.property.show');

        // 1.3 FERRAMENTAS (Views personalizadas)
        // Estas rotas chamam o ConsultantPageController para injetar a variável $consultant e ativar o modo Navy & Gold
        Route::get('/ferramentas/mais-valias', [ConsultantPageController::class, 'showGains'])->name('consultant.tools.gains');
        Route::get('/ferramentas/simulador-credito', [ConsultantPageController::class, 'showCredit'])->name('consultant.tools.credit');
        Route::get('/ferramentas/imt', [ConsultantPageController::class, 'showImt'])->name('consultant.tools.imt');

        // 1.4 AÇÕES (POST) - Cálculos e Envios
        // Necessário estar aqui para que o formulário submeta para o PRÓPRIO domínio da consultora
        // O Laravel injeta o ToolsController, que processa a lógica independentemente do domínio
        Route::post('/ferramentas/mais-valias/calcular', [ToolsController::class, 'calculateGains']);
        Route::post('/ferramentas/simulador-credito/enviar', [ToolsController::class, 'sendCreditSimulation']);
        Route::post('/ferramentas/imt/enviar', [ToolsController::class, 'sendImtSimulation']);
        Route::post('/contato', [ToolsController::class, 'sendContact']);
    });


// ==============================================================================
// 2. APLICAÇÃO PRINCIPAL (HOUSE TEAM)
// ==============================================================================

// --- HOME ---
Route::get('/', function () {
    $properties = Property::where('is_visible', true)->ordered()->take(3)->get(); 
    return view('home', compact('properties'));
})->name('home');

// --- SOBRE ---
Route::get('/sobre', function () {
    $consultants = Consultant::where('is_active', true)->orderBy('order', 'asc')->get();
    $leader = $consultants->first();
    $team = $consultants->filter(fn($c) => $c->id !== ($leader->id ?? null));
    return view('about', compact('leader', 'team'));
})->name('about');

// --- IDIOMA ---
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['pt', 'en'])) Session::put('locale', $locale);
    return back();
})->name('lang.switch');

// --- IMÓVEIS (Site Principal) ---
Route::get('/imoveis', [PropertyController::class, 'publicIndex'])->name('portfolio');
Route::get('/imoveis/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

// --- FERRAMENTAS (Site Principal - Views Padrão) ---
Route::get('/ferramentas/simulador-credito', function () { return view('tools.credit'); })->name('tools.credit');
Route::post('/ferramentas/simulador-credito/enviar', [ToolsController::class, 'sendCreditSimulation'])->name('tools.credit.send');

Route::get('/ferramentas/imt', function () { return view('tools.imt'); })->name('tools.imt');
Route::post('/ferramentas/imt/enviar', [ToolsController::class, 'sendImtSimulation'])->name('tools.imt.send');

Route::get('/ferramentas/mais-valias', function () { return view('tools.gains'); })->name('tools.gains');
Route::post('/ferramentas/mais-valias/calcular', [ToolsController::class, 'calculateGains'])->name('tools.gains.calculate');

// --- BLOG ---
Route::get('/blog', function () { return view('blog.index'); })->name('blog');
Route::view('/blog/novo-perfil-investidor-luxo', 'blog.show')->name('blog.show');
Route::view('/blog/inteligencia-mercado-redefine-investimento', 'blog.show-intelligence')->name('blog.show-intelligence');
Route::view('/blog/lisboa-cascais-algarve-eixos-valor', 'blog.show-locations')->name('blog.show-locations');

// --- CONTACTOS ---
Route::get('/contato', function () { return view('contact'); })->name('contact');
Route::post('/contato', [ToolsController::class, 'sendContact'])->name('contact.submit');

// --- PREVIEW INTERNO (MODAL NO SITE PRINCIPAL) ---
Route::get('/consultor/preview/{consultant}', [ConsultantPageController::class, 'preview'])->name('consultant.preview');

// --- LEGAIS ---
Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/termos', 'legal.terms')->name('terms');
    Route::view('/privacidade', 'legal.privacy')->name('privacy');
    Route::view('/cookies', 'legal.cookies')->name('cookies');
    Route::view('/aviso-legal', 'legal.disclaimer')->name('disclaimer');
});

// --- ADMINISTRAÇÃO ---
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
        
        Route::resource('properties', PropertyController::class)->names('admin.properties');
        Route::resource('consultants', ConsultantController::class)->names('admin.consultants');
        Route::post('/properties/reorder', [PropertyController::class, 'reorder'])->name('admin.properties.reorder');
        Route::post('/properties/{property}/move-to-top', [PropertyController::class, 'moveToTop'])->name('admin.properties.moveToTop');
    });
});