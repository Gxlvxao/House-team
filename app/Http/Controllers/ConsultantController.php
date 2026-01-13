<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultant;
use App\Models\Property;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConsultantController extends Controller
{
    // ==============================================================================
    // 1. ÁREA PÚBLICA (LANDING PAGES) - LÓGICA RESTAURADA
    // ==============================================================================

    /**
     * Busca a consultora pelo Domínio ou Slug.
     */
    private function getConsultantByDomain($domain)
    {
        // 1. Limpeza do protocolo
        $host = strtolower(str_replace(['http://', 'https://', 'www.'], '', $domain));
        
        // 2. Extrai o possível slug
        $slugPart = explode('.', $host)[0];

        // Se estiver NULL no banco, ele simplesmente não acha aqui e passa para o próximo passo.
        $consultant = Consultant::where('lp_slug', $slugPart)
            ->where('is_active', true)
            ->first();

        // 3. Se não achou pelo slug, busca pelo Domínio Exato
        if (! $consultant) {
            $consultant = Consultant::where('domain', $host)
                ->where('is_active', true)
                ->firstOrFail(); 
        }

        return $consultant;
    }

    public function index($domain)
    {
        $consultant = $this->getConsultantByDomain($domain);

        $properties = Property::where('consultant_id', $consultant->id)
            ->where('is_visible', true)
            ->ordered() // Usei seu scopeOrdered() pra garantir consistência
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
            ->ordered()
            ->take(6)
            ->get();

        return view('consultants.landing-page', compact('consultant', 'properties'));
    }

    // ==============================================================================
    // 2. ÁREA ADMINISTRATIVA (CRUD) - LÓGICA REIMPLEMENTADA
    // ==============================================================================

    /**
     * Lista de consultores no Admin (Renomeado para não conflitar com a LP)
     */
    public function adminIndex()
    {
        $consultants = Consultant::orderBy('order', 'asc')->paginate(20);
        return view('admin.consultants.index', compact('consultants'));
    }

    public function create()
    {
        return view('admin.consultants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:consultants,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:5120', // Máx 5MB
            'bio' => 'nullable|string',
            'domain' => 'nullable|string|unique:consultants,domain|max:100',
            'lp_slug' => 'nullable|string|unique:consultants,lp_slug|max:50',
            'whatsapp' => 'nullable|string|max:20',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'tiktok' => 'nullable|url',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['has_lp'] = $request->has('has_lp');
        
        // Define a ordem automaticamente (último + 1)
        $data['order'] = Consultant::max('order') + 1;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('consultants', 'public');
        }

        Consultant::create($data);

        return redirect()->route('admin.consultants.index')
            ->with('success', 'Consultor criado com sucesso!');
    }

    public function edit(Consultant $consultant)
    {
        return view('admin.consultants.edit', compact('consultant'));
    }

    public function update(Request $request, Consultant $consultant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:consultants,email,' . $consultant->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|string|max:100',
            'photo' => 'nullable|image|max:5120',
            'bio' => 'nullable|string',
            'domain' => 'nullable|string|max:100|unique:consultants,domain,' . $consultant->id,
            'lp_slug' => 'nullable|string|max:50|unique:consultants,lp_slug,' . $consultant->id,
            'whatsapp' => 'nullable|string|max:20',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'tiktok' => 'nullable|url',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['has_lp'] = $request->has('has_lp');

        if ($request->hasFile('photo')) {
            // Remove a antiga se não for padrão
            if ($consultant->photo && !str_contains($consultant->photo, 'default')) {
                Storage::disk('public')->delete($consultant->photo);
            }
            $data['photo'] = $request->file('photo')->store('consultants', 'public');
        }

        $consultant->update($data);

        return redirect()->route('admin.consultants.index')
            ->with('success', 'Consultor atualizado com sucesso!');
    }

    public function destroy(Consultant $consultant)
    {
        if ($consultant->photo && Storage::disk('public')->exists($consultant->photo)) {
            Storage::disk('public')->delete($consultant->photo);
        }
        
        $consultant->delete();
        return back()->with('success', 'Consultor removido.');
    }
}