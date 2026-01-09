<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Models\Property;
use App\Models\Consultant;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\ConsultantPageController; // Importante estar aqui

// ==============================================================================
// 1. ROTAS DE DOMÍNIO (CONSULTORES - ACESSO EXTERNO)
// 🚨 ESTE BLOCO DEVE VIR ANTES DE TODAS AS OUTRAS ROTAS
// ==============================================================================

// Esta regra diz: "Capture qualquer domínio, EXCETO o localhost/127.0.0.1/house-team..."
Route::domain('{domain}')
    ->where(['domain' => '^(?!127\.0\.0\.1|localhost|house-team\.127\.0\.0\.1\.nip\.io|assets|img|css|js|storage).*$'])
    ->group(function () {
        Route::get('/', [ConsultantPageController::class, 'index'])->name('consultant.home');
        Route::get('/imovel/{property:slug}', [ConsultantPageController::class, 'showProperty'])->name('consultant.property');
        
        // Se precisar das ferramentas na página do consultor, adicione aqui apontando para o ToolsController
        // Ex: Route::get('/simulador-credito', function() { return view('tools.credit'); });
    });


// ==============================================================================
// 2. APLICAÇÃO PRINCIPAL (HOUSE TEAM)
// ==============================================================================

// --- HOME ---
Route::get('/', function () {
    // Busca imóveis visíveis, respeitando a ordem definida no admin
    $properties = Property::where('is_visible', true)
        ->ordered() 
        ->take(3)
        ->get(); 
        
    return view('home', compact('properties'));
})->name('home');

// --- SOBRE ---
Route::get('/sobre', function () {
    $consultants = Consultant::where('is_active', true)
        ->orderBy('order', 'asc')
        ->get();

    $leader = $consultants->first();
    
    $team = $consultants->filter(function ($consultant) use ($leader) {
        return $consultant->id !== ($leader->id ?? null);
    });

    return view('about', compact('leader', 'team'));
})->name('about');

// --- NOVA ROTA: PREVIEW INTERNO (MODAL) ---
// Esta rota permite carregar a LP dentro de um iframe no site principal
Route::get('/consultor/preview/{consultant}', [ConsultantPageController::class, 'preview'])
    ->name('consultant.preview');


// --- IDIOMA ---
Route::get('lang/{locale}', function ($locale) {
    if (! in_array($locale, ['pt', 'en'])) {
        abort(400);
    }
    Session::put('locale', $locale);
    return redirect()->back();
})->name('lang.switch');

// --- IMÓVEIS ---
Route::get('/imoveis', [PropertyController::class, 'publicIndex'])->name('portfolio');
Route::get('/imoveis/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

// --- FERRAMENTAS ---

// 1. Simulador de Crédito
Route::get('/ferramentas/simulador-credito', function () {
    return view('tools.credit');
})->name('tools.credit');

Route::post('/ferramentas/simulador-credito/enviar', [ToolsController::class, 'sendCreditSimulation'])
    ->name('tools.credit.send');

// 2. Simulador de IMT
Route::get('/ferramentas/imt', function () {
    return view('tools.imt');
})->name('tools.imt');

Route::post('/ferramentas/imt/enviar', [ToolsController::class, 'sendImtSimulation'])
    ->name('tools.imt.send');

// 3. Simulador de Mais-Valias
Route::get('/ferramentas/mais-valias', function () {
    return view('tools.gains');
})->name('tools.gains');

Route::post('/ferramentas/mais-valias/calcular', [ToolsController::class, 'calculateGains'])
    ->name('tools.gains.calculate');


// --- BLOG ---
Route::get('/blog', function () {
    return view('blog.index');
})->name('blog');

Route::get('/blog/novo-perfil-investidor-luxo', function () {
    return view('blog.show');
})->name('blog.show');

Route::get('/blog/inteligencia-mercado-redefine-investimento', function () {
    return view('blog.show-intelligence');
})->name('blog.show-intelligence');

Route::get('/blog/lisboa-cascais-algarve-eixos-valor', function () {
    return view('blog.show-locations');
})->name('blog.show-locations');


// --- CONTACTOS ---
Route::get('/contato', function () {
    return view('contact');
})->name('contact');

Route::post('/contato', [ToolsController::class, 'sendContact'])->name('contact.submit');


// --- ADMINISTRAÇÃO ---
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::resource('properties', PropertyController::class)->names('admin.properties');
        Route::resource('consultants', ConsultantController::class)->names('admin.consultants');
        
        // Rota de reordenação (Drag & Drop)
        Route::post('/properties/reorder', [PropertyController::class, 'reorder'])->name('admin.properties.reorder');
    });
});


// --- LEGAIS ---
Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/termos', 'legal.terms')->name('terms');
    Route::view('/privacidade', 'legal.privacy')->name('privacy');
    Route::view('/cookies', 'legal.cookies')->name('cookies');
    Route::view('/aviso-legal', 'legal.disclaimer')->name('disclaimer');
});