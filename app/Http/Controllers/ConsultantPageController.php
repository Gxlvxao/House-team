<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use App\Models\Property;
use Illuminate\Http\Request;

class ConsultantPageController extends Controller
{
    /**
     * Carrega a Homepage baseada no DOMÍNIO (ex: casaacasa.pt)
     */
    public function index($domain)
    {
        // 1. Limpeza do domínio (remove www. se existir para bater com o banco)
        $domain = preg_replace('/^www\./', '', $domain);

        // 2. Busca o consultor pelo domínio exato
        $consultant = Consultant::where('domain', $domain)
                        ->where('has_lp', true)
                        ->where('is_active', true)
                        ->first();

        // Fallback: Se não achar pelo domínio, tenta pelo slug (caso uses aliases no futuro)
        if (!$consultant) {
             $consultant = Consultant::where('lp_slug', $domain)
                        ->where('has_lp', true)
                        ->where('is_active', true)
                        ->first();
        }

        if (!$consultant) {
            abort(404); 
        }

        // 3. Carrega os imóveis (Top 6 ordenados)
        $properties = $this->getAvailableProperties();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    /**
     * Preview Interno (Modal) - Mantém igual
     */
    public function preview(Consultant $consultant)
    {
        if (!$consultant->has_lp || !$consultant->is_active) {
            abort(404);
        }
        $properties = $this->getAvailableProperties();
        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    /**
     * Helper privado
     */
    private function getAvailableProperties()
    {
        return Property::where('is_visible', true)
            ->ordered()
            ->take(6) // Mantém o limite de 6 que pediste
            ->get();
    }
}