<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Material;
use App\Models\Espace;
use App\Models\Article;
use App\Models\User;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $categoriesCount = Category::count();
        $materialsCount = Material::count();
        $espacesCount = Espace::count();
        $articlesCount = Article::count();
        $activeUsersCount = User::where('is_active', true)->count();

        $espacesData = Espace::withCount('articles')->get();

        $recentArticles = Article::with(['material', 'espace'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'categoriesCount',
            'materialsCount',
            'espacesCount',
            'articlesCount',
            'activeUsersCount',
            'espacesData',
            'recentArticles'
        ));
    }
}
