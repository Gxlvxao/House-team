<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConsultantController extends Controller
{
    public function index()
    {
        $consultants = Consultant::orderBy('order', 'asc')->get();
        return view('admin.consultants.index', compact('consultants'));
    }

    public function create()
    {
        return view('admin.consultants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        Consultant::create($validated);

        return redirect()->route('admin.consultants.index')
            ->with('success', 'Consultor adicionado com sucesso!');
    }

    public function edit(Consultant $consultant)
    {
        return view('admin.consultants.edit', compact('consultant'));
    }

    public function update(Request $request, Consultant $consultant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            if ($consultant->photo && Storage::disk('public')->exists($consultant->photo)) {
                Storage::disk('public')->delete($consultant->photo);
            }
            $validated['photo'] = $request->file('photo')->store('team', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $consultant->update($validated);

        return redirect()->route('admin.consultants.index')
            ->with('success', 'Consultor atualizado com sucesso!');
    }

    public function destroy(Consultant $consultant)
    {
        if ($consultant->photo && Storage::disk('public')->exists($consultant->photo)) {
            Storage::disk('public')->delete($consultant->photo);
        }

        $consultant->delete();

        return redirect()->route('admin.consultants.index')
            ->with('success', 'Consultor removido com sucesso!');
    }
}