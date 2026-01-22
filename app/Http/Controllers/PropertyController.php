<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    /**
     * INDEX ADMIN: Com filtros de Status e Visibilidade corrigidos
     */
    public function index(Request $request)
    {
        $query = Property::query();

        // Filtro por Negócio (Venda/Arrendamento)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por Visibilidade (Ativo/Inativo)
        // Ajuste: Forçando 1 ou 0 para garantir compatibilidade com o MySQL tinyint
        if ($request->filled('visibility')) {
            $value = ($request->visibility === 'active') ? 1 : 0;
            $query->where('is_visible', $value);
        }

        // Mantém a ordenação definida no Model e persiste os filtros na paginação
        $properties = $query->ordered()->paginate(10)->withQueryString();

        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        $consultants = Consultant::where('is_active', true)->orderBy('name')->get();
        return view('admin.properties.create', compact('consultants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'crm_code' => 'nullable|string|max:50',
            'consultant_id' => 'nullable|exists:consultants,id',
            'price' => 'nullable|numeric',
            'type' => 'required|string',
            'status' => 'required|string',
            'location' => 'nullable|string',
            'address' => 'nullable|string',
            'floor' => 'nullable|string',
            'orientation' => 'nullable|string',
            'area_gross' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'garages' => 'nullable|integer',
            'energy_rating' => 'nullable|string',
            'video_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:20480',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:20480',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();
        
        if (!isset($data['order']) || $data['order'] === null) {
            $maxOrder = Property::max('order');
            $data['order'] = $maxOrder ? $maxOrder + 1 : 1;
        }
        
        $features = [
            'has_pool', 'has_garden', 'has_lift', 'has_terrace', 'has_air_conditioning', 
            'is_furnished', 'is_kitchen_equipped'
        ];
        
        foreach ($features as $feature) {
            $data[$feature] = $request->has($feature);
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('properties', 'public');
        }

        $property = Property::create($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $index => $image) {
                if ($image->isValid()) {
                    $path = $image->store('properties/gallery', 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $path,
                        'order' => $index
                    ]);
                }
            }
        }

        return redirect()->route('admin.properties.index')->with('success', 'Imóvel cadastrado com sucesso!');
    }

    public function edit(Property $property)
    {
        $consultants = Consultant::where('is_active', true)->orderBy('name')->get();
        return view('admin.properties.edit', compact('property', 'consultants'));
    }

    public function update(Request $request, Property $property)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'crm_code' => 'nullable|string|max:50',
            'consultant_id' => 'nullable|exists:consultants,id',
            'price' => 'nullable|numeric',
            'type' => 'required|string',
            'status' => 'required|string',
            'location' => 'nullable|string',
            'address' => 'nullable|string',
            'floor' => 'nullable|string',
            'orientation' => 'nullable|string',
            'area_gross' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'garages' => 'nullable|integer',
            'energy_rating' => 'nullable|string',
            'video_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:20480',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:20480',
            'images_order' => 'nullable|string', 
        ]);

        if ($property->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']) . '-' . time();
        }

        $features = [
            'has_pool', 'has_garden', 'has_lift', 'has_terrace', 'has_air_conditioning', 
            'is_furnished', 'is_kitchen_equipped'
        ];
        
        foreach ($features as $feature) {
            $data[$feature] = $request->has($feature);
        }

        if ($request->hasFile('cover_image')) {
            if ($property->cover_image) {
                Storage::disk('public')->delete($property->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('properties', 'public');
        }

        $property->update($data);

        $existingOrderIds = $request->filled('images_order') 
            ? explode(',', $request->images_order) 
            : [];

        $imagesToDelete = $property->images()->whereNotIn('id', $existingOrderIds)->get();
        foreach ($imagesToDelete as $img) {
            Storage::disk('public')->delete($img->path);
            $img->delete();
        }

        foreach ($existingOrderIds as $index => $id) {
            PropertyImage::where('id', $id)->update(['order' => $index]);
        }

        if ($request->hasFile('gallery')) {
            $lastOrder = PropertyImage::where('property_id', $property->id)->max('order') ?? -1;

            foreach ($request->file('gallery') as $image) {
                if ($image->isValid()) {
                    $lastOrder++;
                    $path = $image->store('properties/gallery', 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $path,
                        'order' => $lastOrder
                    ]);
                }
            }
        }

        return redirect()->route('admin.properties.index')->with('success', 'Imóvel atualizado com sucesso!');
    }

    public function destroy(Property $property)
    {
        if ($property->cover_image) {
            Storage::disk('public')->delete($property->cover_image);
        }
        
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        
        $property->delete();
        return back()->with('success', 'Imóvel removido permanentemente.');
    }

    public function publicIndex(Request $request)
    {
        $query = Property::with('images')->where('is_visible', true);

        if ($request->filled('location')) {
            $search = $request->location;
            $query->where(function($q) use ($search) {
                $q->where('location', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('bedrooms')) {
            if ($request->bedrooms == '4+') {
                $query->where('bedrooms', '>=', 4);
            } else {
                $query->where('bedrooms', $request->bedrooms);
            }
        }

        $properties = $query->ordered()->paginate(9)->withQueryString();

        return view('properties.index', compact('properties'));
    }

    public function show(Request $request, Property $property)
    {
        if (!$property->is_visible) {
            abort(404);
        }

        $property->load(['images' => function ($query) {
            $query->orderBy('order', 'asc');
        }, 'consultant']);

        $consultant = null;

        if ($request->has('cid')) {
            $consultant = Consultant::find($request->cid);
        } elseif ($request->route('domain')) {
             $domain = preg_replace('/^www\./', '', $request->route('domain'));
             $consultant = Consultant::where('domain', $domain)
                ->orWhere('lp_slug', $domain)
                ->first();
        }

        return view('properties.show', compact('property', 'consultant'));
    }

    /**
     * TOGGLE VISIBILITY: Inverte o status e retorna feedback com HTML
     */
    public function toggleVisibility(Property $property)
    {
        $property->update([
            'is_visible' => !$property->is_visible
        ]);

        $status = $property->is_visible ? 'Ativo' : 'Inativo';
        $color = $property->is_visible ? 'text-emerald-600' : 'text-slate-600';
        
        return back()->with('success', "Imóvel agora está <strong class='{$color}'>{$status}</strong>.");
    }

    /**
     * REORDER: Salva a ordem vinda do Drag & Drop (AJAX)
     */
    public function reorder(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $index => $id) {
            Property::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * MOVE TO TOP: Coloca o imóvel na primeira posição
     */
    public function moveToTop(Property $property)
    {
        $minOrder = Property::min('order') ?? 0;
        $property->update(['order' => $minOrder - 1]);

        return back()->with('success', 'Imóvel movido para o topo com sucesso!');
    }
}