<?php

namespace App\Http\Controllers;

use App\Models\Espace;
use Illuminate\Http\Request;

class EspaceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $espaces = Espace::query()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('espaces.index', compact('espaces', 'search'));
    }

    public function create()
    {
        return view('espaces.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:espaces,title',
        ]);

        Espace::create($validated);

        return redirect()->route('espaces.index')
            ->with('success', 'Espace created successfully.');
    }

    public function edit(Espace $espace)
    {
        return view('espaces.edit', compact('espace'));
    }

    public function update(Request $request, Espace $espace)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:espaces,title,' . $espace->id,
        ]);

        $espace->update($validated);

        return redirect()->route('espaces.index')
            ->with('success', 'Espace updated successfully.');
    }

    public function destroy(Espace $espace)
    {
        $espace->delete();

        return redirect()->route('espaces.index')
            ->with('success', 'Espace and its related articles deleted successfully.');
    }
}
