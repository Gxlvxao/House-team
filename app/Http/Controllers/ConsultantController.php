<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultant;
use App\Models\Property;

// Mantenha o nome da classe igual ao nome do arquivo
class ConsultantController extends Controller
{
    /**
     * Busca a consultora pelo Domínio ou Subdomínio (lp_slug)
     */
    private function getConsultantByDomain($domain)
    {
        // 1. Limpa o protocolo e www
        $host = strtolower(str_replace(['http://', 'https://', 'www.'], '', $domain));
        
        // 2. Tenta extrair o slug (ex: 'margarida' de 'margarida.casaacasa.pt')
        $slugPart = explode('.', $host)[0];

        // --- CORREÇÃO DO ERRO 500 ---
        // Trocamos 'slug' por 'lp_slug' que é o nome correto na sua tabela
        $consultant = Consultant::where('lp_slug', $slugPart)
            ->where('is_active', true)
            ->first();

        // 3. Se não achou pelo slug, busca pelo Domínio Exato (ex: casaacasa.pt)
        if (! $consultant) {
            $consultant = Consultant::where(function($query) use ($host) {
                    $query->where('domain', $host)       // Nome padrão
                          ->orWhere('custom_domain', $host); // Nome alternativo (segurança)
                })
                ->where('is_active', true)
                ->firstOrFail(); // Se não achar aqui, lança 404
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

        // Nota: Na tabela 'properties' a coluna geralmente é 'slug' mesmo.
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

    // Preview interno (Admin)
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