<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultant;
use App\Models\Property;

class ConsultantPageController extends Controller
{
    /**
     * Busca a consultora de forma segura (Subdomínio ou Domínio Principal).
     */
    private function getConsultantByDomain($domain)
    {
        // 1. Sanitização: remove 'www.' para padronizar
        $host = strtolower(str_replace('www.', '', $domain));

        // 2. Identifica o possível slug (primeira parte do domínio)
        // Ex: 'margarida.casaacasa.pt' -> $slug = 'margarida'
        // Ex: 'casaacasa.pt' -> $slug = 'casaacasa' (Isso causava o erro antes)
        $slug = explode('.', $host)[0];

        // 3. Tenta buscar primeiro pelo SLUG (Lógica de Subdomínio)
        $consultant = Consultant::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        // 4. Se não achou pelo slug, tenta pelo DOMÍNIO PERSONALIZADO (Lógica da Raiz)
        // Isso permite que 'casaacasa.pt' carregue a consultora correta se ela tiver
        // o campo 'custom_domain' preenchido ou se você definir uma lógica de fallback.
        if (! $consultant) {
            $consultant = Consultant::where('custom_domain', $host) // Certifique-se que esta coluna existe
                ->orWhere('domain', $host) // Fallback caso o nome da coluna seja 'domain'
                ->where('is_active', true)
                ->firstOrFail(); // Agora sim lançamos 404 se não achar nada
        }

        return $consultant;
    }

    // --- MÉTODOS PÚBLICOS ---

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

    // --- FERRAMENTAS ---

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

    // --- PREVIEW INTERNO (ADMIN) ---
    // Este método não usa $domain, pois recebe o Model direto (Route Model Binding)
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