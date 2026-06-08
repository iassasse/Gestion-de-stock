<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Material;
use App\Models\Espace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $articles = Article::query()
            ->with(['material.category', 'espace'])
            ->when($search, function ($query, $search) {
                return $query->where('li_ref', 'like', '%' . $search . '%')
                    ->orWhereHas('material', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                          ->orWhere('ref', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('espace', function ($q) use ($search) {
                        $q->where('title', 'like', '%' . $search . '%');
                    });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('articles.index', compact('articles', 'search'));
    }

    public function create()
    {
        $materials = Material::orderBy('name')->get();
        $espaces = Espace::orderBy('title')->get();
        return view('articles.create', compact('materials', 'espaces'));
    }

    public function showBulkCreate()
    {
        $materials = Material::orderBy('name')->get();
        $espaces = Espace::orderBy('title')->get();
        return view('articles.bulk-create', compact('materials', 'espaces'));
    }

    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'espace_id' => 'required|exists:espaces,id',
            'start_ref' => 'required|integer|min:0',
            'end_ref' => [
                'required',
                'integer',
                'gt:start_ref',
                function ($attribute, $value, $fail) use ($request) {
                    $start = (int)$request->input('start_ref');
                    $end = (int)$value;
                    if (($end - $start) > 500) {
                        $fail('The bulk creation range cannot exceed 500 articles at a time.');
                    }
                }
            ],
        ]);

        $materialId = $validated['material_id'];
        $espaceId = $validated['espace_id'];
        $start = (int)$validated['start_ref'];
        $end = (int)$validated['end_ref'];

        $createdCount = 0;
        $skippedCount = 0;

        DB::transaction(function () use ($start, $end, $materialId, $espaceId, &$createdCount, &$skippedCount) {
            for ($i = $start; $i <= $end; $i++) {
                $liRef = (string)$i;

                $exists = Article::where('li_ref', $liRef)->exists();
                if (!$exists) {
                    Article::create([
                        'li_ref' => $liRef,
                        'material_id' => $materialId,
                        'espace_id' => $espaceId,
                    ]);
                    $createdCount++;
                } else {
                    $skippedCount++;
                }
            }
        });

        if ($createdCount === 0) {
            return redirect()->route('articles.index')
                ->with('error', "No articles were created. All {$skippedCount} references in the range already exist.");
        }

        $message = "Successfully created {$createdCount} articles.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} existing articles were skipped to prevent duplication.";
        }

        return redirect()->route('articles.index')->with('success', $message);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'li_ref' => 'required|string|max:255|unique:articles,li_ref',
            'material_id' => 'required|exists:materials,id',
            'espace_id' => 'required|exists:espaces,id',
        ]);

        Article::create($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $materials = Material::orderBy('name')->get();
        $espaces = Espace::orderBy('title')->get();
        return view('articles.edit', compact('article', 'materials', 'espaces'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'li_ref' => 'required|string|max:255|unique:articles,li_ref,' . $article->id,
            'material_id' => 'required|exists:materials,id',
            'espace_id' => 'required|exists:espaces,id',
        ]);

        $article->update($validated);

        return redirect()->route('articles.index')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}
