<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultant;
use App\Models\Property;

class ConsultantController extends Controller
{
    /**
     * Busca a consultora pelo Domínio ou Slug.
     */
    private function getConsultantByDomain($domain)
    {
        // 1. Limpeza do protocolo
        $host = strtolower(str_replace(['http://', 'https://', 'www.'], '', $domain));
        
        // 2. Extrai o possível slug (ex: 'margarida' de 'margarida.casaacasa.pt')
        $slugPart = explode('.', $host)[0];

        // --- CORREÇÃO 1: Usando o nome da coluna que você me mandou (ip_slug) ---
        $consultant = Consultant::where('ip_slug', $slugPart)
            ->where('is_active', true)
            ->first();

        // 3. Se não achou pelo slug, busca pelo Domínio Exato
        if (! $consultant) {
            // --- CORREÇÃO 2: Removido 'custom_domain', busca só em 'domain' ---
            $consultant = Consultant::where('domain', $host)
                ->where('is_active', true)
                ->firstOrFail(); 
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

        // Imóvel continua buscando por 'slug' (coluna da tabela properties)
        $property = Property::where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return view('properties.show', compact('property', 'consultant'));
    }

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