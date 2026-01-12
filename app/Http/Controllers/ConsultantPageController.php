<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultant;
use App\Models\Property;

class ConsultantPageController extends Controller
{
    /**
     * Busca a consultora baseada no domínio ou slug.
     */
    private function getConsultantByDomain($domain)
    {
        // Remove 'www.' se existir
        $domain = str_replace('www.', '', $domain);
        
        // Pega a primeira parte do domínio (ex: 'casaacasa' de 'casaacasa.pt')
        $slug = explode('.', $domain)[0];

        // Tenta buscar pelo SLUG. 
        // IMPORTANTE: No banco de dados, o slug da Margarida deve ser 'casaacasa' ou o nome do subdomínio.
        // Se você tiver uma coluna 'custom_domain' no banco, descomente a linha abaixo:
        // $consultant = Consultant::where('slug', $slug)->orWhere('custom_domain', $domain)->first();
        
        return Consultant::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    // Nota: O parâmetro $domain vem da rota Route::domain('{domain}')
    public function index($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);

        $properties = Property::where('consultant_id', $consultant->id)
            ->where('is_visible', true)
            ->latest()
            ->take(6)
            ->get();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    public function showProperty($domain, $slug)
    {
        $consultant = $this->getConsultantByDomain($domain);

        $property = Property::where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return view('properties.show', compact('property', 'consultant'));
    }

    // --- MÉTODOS DAS FERRAMENTAS (CORRIGIDOS) ---
    // Adicionamos $domain como primeiro argumento para casar com a rota

    public function showCredit($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        return view('tools.credit', compact('consultant'));
    }

    public function showGains($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        return view('tools.gains', compact('consultant'));
    }

    public function showImt($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        return view('tools.imt', compact('consultant'));
    }

    // Preview interno (sem domínio)
    public function preview(Consultant $consultant)
    {
        $properties = Property::where('consultant_id', $consultant->id)
            ->where('is_visible', true)
            ->latest()
            ->take(6)
            ->get();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }
}