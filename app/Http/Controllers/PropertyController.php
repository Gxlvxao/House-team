<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::latest()->paginate(10);
        return view('admin.properties.index', compact('properties'));
    }

    public function create()
    {
        $consultants = Consultant::where('is_active', true)->orderBy('name')->get();
        return view('admin.properties.create', compact('consultants'));
    }

    public function store(Request $request)
    {
        // ADICIONEI 'crm_code' AQUI NA VALIDAÇÃO
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'crm_code' => 'nullable|string|max:50', // <--- CAMPO NOVO
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
            'condition' => 'nullable|string',
            'video_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:20480',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:20480',
            'has_pool' => 'nullable',
            'has_garden' => 'nullable',
            'has_lift' => 'nullable',
            'has_terrace' => 'nullable',
            'has_air_conditioning' => 'nullable',
            'is_furnished' => 'nullable',
            'is_kitchen_equipped' => 'nullable',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();
        
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
            foreach ($request->file('gallery') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('properties/gallery', 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $path
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
        // ADICIONEI 'crm_code' AQUI TAMBÉM
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'crm_code' => 'nullable|string|max:50', // <--- CAMPO NOVO
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
            'condition' => 'nullable|string',
            'video_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:20480',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|max:20480',
            'has_pool' => 'nullable',
            'has_garden' => 'nullable',
            'has_lift' => 'nullable',
            'has_terrace' => 'nullable',
            'has_air_conditioning' => 'nullable',
            'is_furnished' => 'nullable',
            'is_kitchen_equipped' => 'nullable',
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

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('properties/gallery', 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'path' => $path
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
        return back()->with('success', 'Imóvel removido.');
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

        $properties = $query->latest()->paginate(9)->withQueryString();

        return view('properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        $property->load(['images', 'consultant']);
        return view('properties.show', compact('property'));
    }
}