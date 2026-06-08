<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Category;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $selectedCategory = $categoryId ? Category::find($categoryId) : null;

        $materials = Material::query()
            ->with('category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('ref', 'like', '%' . $search . '%')
                      ->orWhereHas('category', function ($catQ) use ($search) {
                          $catQ->where('title', 'like', '%' . $search . '%');
                      });
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('materials.index', compact('materials', 'search', 'selectedCategory'));
    }

    public function indexByCategory(Request $request, Category $category)
    {
        $search = $request->input('search');

        $materials = Material::query()
            ->with('category')
            ->where('category_id', $category->id)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('ref', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $selectedCategory = $category;

        return view('materials.index', compact('materials', 'search', 'selectedCategory'));
    }

    public function create(Request $request)
    {
        $categories = Category::orderBy('title')->get();
        $selectedCategoryId = $request->input('category_id');
        return view('materials.create', compact('categories', 'selectedCategoryId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ref' => 'required|string|max:255|unique:materials,ref',
            'category_id' => 'required|exists:categories,id',
        ]);

        Material::create($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Material created successfully.');
    }

    public function edit(Material $material)
    {
        $categories = Category::orderBy('title')->get();
        return view('materials.edit', compact('material', 'categories'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ref' => 'required|string|max:255|unique:materials,ref,' . $material->id,
            'category_id' => 'required|exists:categories,id',
        ]);

        $material->update($validated);

        return redirect()->route('materials.index')
            ->with('success', 'Material updated successfully.');
    }

    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->route('materials.index')
            ->with('success', 'Material and its related articles deleted successfully.');
    }
}
