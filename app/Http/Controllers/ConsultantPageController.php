<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultant;
use App\Models\Property;

// ATENÇÃO: O nome da classe DEVE ser igual ao nome do arquivo (ConsultantController.php)
class ConsultantController extends Controller
{
    /**
     * Busca a consultora de forma segura (Subdomínio ou Domínio Principal).
     */
    private function getConsultantByDomain($domain)
    {
        // 1. Sanitização: remove 'www.', 'http://', etc.
        $host = strtolower(str_replace(['http://', 'https://', 'www.'], '', $domain));

        // 2. Tenta extrair um possível slug (ex: 'margarida' de 'margarida.casaacasa.pt')
        $possibleSlug = explode('.', $host)[0];

        // 3. Tenta buscar primeiro pelo SLUG (Lógica de Subdomínio)
        $consultant = Consultant::where('slug', $possibleSlug)
            ->where('is_active', true)
            ->first();

        // 4. Se não achou pelo slug, tenta pelo DOMÍNIO EXATO (Lógica da Raiz)
        // Isso resolve o problema de 'casaacasa.pt' tentar buscar o slug 'casaacasa'.
        // Agora ele vai procurar um consultor que tenha 'custom_domain' = 'casaacasa.pt'
        if (! $consultant) {
            // Nota: Certifique-se de que a coluna 'custom_domain' ou 'domain' existe no seu banco.
            // Se sua coluna chamar apenas 'domain', troque abaixo.
            $consultant = Consultant::where(function($query) use ($host) {
                    $query->where('custom_domain', $host)
                          ->orWhere('domain', $host);
                })
                ->where('is_active', true)
                ->firstOrFail(); // Lança 404 se não encontrar nada
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