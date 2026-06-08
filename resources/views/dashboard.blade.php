@extends('layouts.app')

@section('content')
<div class="row mb-4 animate__animated animate__fadeIn">
    <div class="col-12">
        <div class="card premium-card border-0 text-white p-4" style="background: var(--primary-gradient);">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h3 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="mb-0 text-white-50">You are logged in as <span class="badge bg-white text-dark fw-bold">{{ Auth::user()->role }}</span> | System Status: Operational</p>
                </div>
                <div>
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Avatar" class="rounded-circle border border-2 border-white shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center border border-2 border-white shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-person-fill fs-2"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Active Users Stats Card -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2">
        <div class="card premium-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Active Users</span>
                    <h3 class="mb-0 fw-bold">{{ $activeUsersCount }}</h3>
                </div>
                <div class="stat-card-icon" style="background-color: #e0f2fe; color: #0369a1;">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="mt-3">
                @if(Auth::user()->isChefMagasinier())
                    <a href="{{ route('users.index') }}" class="text-decoration-none fw-semibold" style="font-size: 0.85rem; color: #0369a1;">
                        Manage users <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                @else
                    <span class="text-muted" style="font-size: 0.85rem;">Active staff</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Categories Stats Card -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2">
        <div class="card premium-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Categories</span>
                    <h3 class="mb-0 fw-bold">{{ $categoriesCount }}</h3>
                </div>
                <div class="stat-card-icon bg-indigo-light">
                    <i class="bi bi-tags-fill"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('categories.index') }}" class="text-indigo text-decoration-none fw-semibold" style="font-size: 0.85rem;">
                    Manage categories <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Materials Stats Card -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2">
        <div class="card premium-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Materials</span>
                    <h3 class="mb-0 fw-bold">{{ $materialsCount }}</h3>
                </div>
                <div class="stat-card-icon bg-purple-light">
                    <i class="bi bi-hammer"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('materials.index') }}" class="text-purple text-decoration-none fw-semibold" style="font-size: 0.85rem; color: #6b21a8;">
                    Manage materials <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Espaces Stats Card -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2">
        <div class="card premium-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Spaces (Espaces)</span>
                    <h3 class="mb-0 fw-bold">{{ $espacesCount }}</h3>
                </div>
                <div class="stat-card-icon bg-pink-light">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('espaces.index') }}" class="text-pink text-decoration-none fw-semibold" style="font-size: 0.85rem; color: #be185d;">
                    Manage spaces <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Articles Stats Card -->
    <div class="col-12 col-sm-6 col-md-4 col-xl-2">
        <div class="card premium-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted fw-semibold d-block mb-1">Articles</span>
                    <h3 class="mb-0 fw-bold">{{ $articlesCount }}</h3>
                </div>
                <div class="stat-card-icon bg-emerald-light">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('articles.index') }}" class="text-emerald text-decoration-none fw-semibold" style="font-size: 0.85rem; color: #047857;">
                    Manage articles <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Articles by Space Breakdown -->
    <div class="col-12 col-lg-6">
        <div class="card premium-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Articles per Space (Espace)</h5>
                <i class="bi bi-pie-chart text-muted fs-5"></i>
            </div>
            <div class="d-flex flex-column gap-3">
                @forelse($espacesData as $espace)
                    @php
                        $percentage = $articlesCount > 0 ? ($espace->articles_count / $articlesCount) * 100 : 0;
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold text-dark">{{ $espace->title }}</span>
                            <span class="text-muted" style="font-size: 0.85rem;">{{ $espace->articles_count }} articles ({{ round($percentage, 1) }}%)</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 5px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $percentage }}%; background: var(--primary-gradient); border-radius: 5px;" 
                                 aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No spaces registered yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Articles Added -->
    <div class="col-12 col-lg-6">
        <div class="card premium-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Recently Added Articles</h5>
                <a href="{{ route('articles.index') }}" class="btn btn-sm text-primary fw-semibold p-0">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>LI Ref</th>
                            <th>Material Name</th>
                            <th>Space</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentArticles as $article)
                            <tr>
                                <td><span class="badge bg-light text-dark border fw-medium">{{ $article->li_ref }}</span></td>
                                <td>
                                    <div class="fw-semibold">{{ $article->material->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $article->material->ref ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-indigo-light"><i class="bi bi-geo-alt me-1"></i>{{ $article->espace->title ?? 'N/A' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No articles added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
