<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultant;
use App\Models\Property;

class ConsultantPageController extends Controller
{
    // Método auxiliar para identificar a consultora pelo domínio
    private function getConsultantByDomain($domain)
    {
        // Se o domínio for "margarida.houseteam.pt", pega "margarida"
        // Se for "casaacasa.pt", você precisará de uma lógica para mapear domínios personalizados no banco
        // Por enquanto, assumimos subdomínio = slug
        
        $parts = explode('.', $domain);
        $slug = $parts[0]; 
        
        // Tenta buscar pelo slug, se não achar, falha 404
        return Consultant::where('slug', $slug)->where('is_active', true)->firstOrFail();
    }

    public function index(Request $request)
    {
        $host = $request->getHost();
        $consultant = $this->getConsultantByDomain($host);

        // Busca imóveis associados a esta consultora
        $properties = Property::where('consultant_id', $consultant->id)
            ->where('is_visible', true)
            ->latest()
            ->take(6)
            ->get();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    public function showProperty(Request $request, $slug)
    {
        $host = $request->getHost();
        $consultant = $this->getConsultantByDomain($host);

        $property = Property::where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        // Injetamos $consultant para que a view "show.blade.php" saiba que deve ativar o modo Navy & Gold
        return view('properties.show', compact('property', 'consultant'));
    }

    // --- NOVOS MÉTODOS PARA AS FERRAMENTAS ---

    public function showCredit(Request $request)
    {
        $host = $request->getHost();
        $consultant = $this->getConsultantByDomain($host);
        
        // Retorna a view da ferramenta injetando a consultora (ativa o design personalizado)
        return view('tools.credit', compact('consultant'));
    }

    public function showGains(Request $request)
    {
        $host = $request->getHost();
        $consultant = $this->getConsultantByDomain($host);
        
        return view('tools.gains', compact('consultant'));
    }

    public function showImt(Request $request)
    {
        $host = $request->getHost();
        $consultant = $this->getConsultantByDomain($host);
        
        return view('tools.imt', compact('consultant'));
    }

    // Pré-visualização interna (Admin)
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