<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ConsultantPageController extends Controller
{
    /**
     * Helper privado para resolver o consultor e evitar repetição de código (DRY).
     */
    private function getConsultantByDomain($domain)
    {
        // Remove www. se existir para normalizar
        $cleanDomain = preg_replace('/^www\./', '', $domain);

        $consultant = Consultant::where('domain', $cleanDomain)
            ->orWhere('lp_slug', $cleanDomain)
            ->firstOrFail(); // Usa fail para dar 404 se o domínio não existir no banco

        // Injeta globalmente nas views para que o Layout (header/footer) tenha acesso
        View::share('consultant', $consultant);

        return $consultant;
    }

    public function index($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        
        // Carrega imóveis do consultor ou gerais, conforme sua regra
        // Exemplo: Imóveis captados pelo consultor
        $properties = Property::where('consultant_id', $consultant->id)
            ->where('is_visible', true)
            ->ordered()
            ->paginate(9);

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    public function showProperty($domain, Property $property)
    {
        // NOTA: A ordem dos parâmetros deve bater com a URL: {domain}/imoveis/{property}
        $consultant = $this->getConsultantByDomain($domain);

        return view('properties.show', compact('property', 'consultant'));
    }

    // --- FERRAMENTAS ---

    public function showGains($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        // Retorna a view da ferramenta injetando o consultor (via View::share acima ou compact)
        return view('tools.gains', compact('consultant'));
    }

    public function showCredit($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        return view('tools.credit', compact('consultant'));
    }

    public function showImt($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);
        return view('tools.imt', compact('consultant'));
    }
    
    // Método para o preview interno (Modal)
    public function preview(Consultant $consultant)
    {
        // Aqui não tem $domain na rota, é rota normal do admin/site principal
        View::share('consultant', $consultant);
        
        $properties = Property::where('consultant_id', $consultant->id)
            ->where('is_visible', true)
            ->ordered()
            ->take(6)
            ->get();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }
}