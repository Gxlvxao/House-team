<?php

use Illuminate\Support\Facades\Route;
use App\Models\Property;
use App\Models\Consultant;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\ConsultantController;
use Illuminate\Support\Facades\Session; // Importante para a rota de lang funcionar sem erros

// --- HOME & PÁGINAS GERAIS ---
Route::get('/', function () {
    // ALTERADO: De latest() para ordered()
    // Assim respeita a ordem manual definida no admin
    $properties = Property::where('is_visible', true)
        ->ordered() 
        ->take(3)
        ->get(); 
        
    return view('home', compact('properties'));
})->name('home');

// Rota /sobre
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

Route::get('lang/{locale}', function ($locale) {
    // Validação de segurança: só aceita 'pt' ou 'en'
    if (! in_array($locale, ['pt', 'en'])) {
        abort(400);
    }

    // Grava a preferência na sessão do utilizador
    Session::put('locale', $locale);

    // Volta para a página onde o utilizador estava
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

// Rota para processar o envio da Lead de Crédito
Route::post('/ferramentas/simulador-credito/enviar', [ToolsController::class, 'sendCreditSimulation'])
    ->name('tools.credit.send');


// 2. Simulador de IMT
Route::get('/ferramentas/imt', function () {
    return view('tools.imt');
})->name('tools.imt');

// Rota para processar o envio da Lead de IMT
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


Route::prefix('legal')->name('legal.')->group(function () {
    Route::view('/termos', 'legal.terms')->name('terms');
    Route::view('/privacidade', 'legal.privacy')->name('privacy');
    Route::view('/cookies', 'legal.cookies')->name('cookies');
    Route::view('/aviso-legal', 'legal.disclaimer')->name('disclaimer');
});