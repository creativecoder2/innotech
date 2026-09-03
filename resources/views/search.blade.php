@extends('layouts.app')

@section('title', 'Search Results: ' . ($query ?: 'All') . ' - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))

@section('content')
<main>
   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-100 pb-110 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('services_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-xl-7 col-lg-8 col-md-12 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title">Search Results</h2>
               </div>
            </div>
            <div class="col-xl-5 col-lg-4 col-md-12 col-12">
               <div class="tp-breadcrumb__link serv-md d-flex justify-content-lg-end">
                  <span>Innotech : <a href="{{ url('/') }}">Home</a> &nbsp;/&nbsp; Search</span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- 2. SEARCH RESULTS SECTION -->
   <section class="search-results-area pt-70 pb-100 grey-bg-2">
      <div class="container">
         <!-- Search Box Header -->
         <div class="row justify-content-center mb-50">
            <div class="col-lg-10">
               <div class="search-refine-box p-4 bg-white rounded-3 shadow-sm">
                  <form action="{{ route('search') }}" method="GET" class="d-flex flex-column flex-md-row gap-2 align-items-stretch">
                     <div class="input-group input-group-lg flex-grow-1">
                        <span class="input-group-text bg-light border-end-0 text-primary">
                           <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="query" class="form-control border-start-0 ps-0" placeholder="Search services, doctors, articles, equipment..." value="{{ $query }}" required autocomplete="off">
                     </div>
                     <button type="submit" class="tp-btn search-btn px-4 py-3" style="min-width: 170px;">
                        Search Again <i class="fa-light fa-arrow-right ml-5"></i>
                     </button>
                  </form>

                  @if(!empty($query))
                     <div class="mt-3 d-flex flex-wrap align-items-center justify-content-between pt-3 border-top">
                        <div class="text-muted">
                           Found <strong class="text-dark">{{ $totalResults }}</strong> {{ Str::plural('result', $totalResults) }} for "<span class="text-primary fw-semibold">{{ $query }}</span>"
                        </div>
                        @if($totalResults > 0)
                           <div class="small text-muted">
                              Tip: Click the tabs below to filter results by category.
                           </div>
                        @endif
                     </div>
                  @endif
               </div>
            </div>
         </div>

         @if(!empty($query) && $totalResults > 0)
            <!-- Category Filter Tabs -->
            <div class="row mb-40">
               <div class="col-12">
                  <ul class="nav nav-pills justify-content-center search-filter-pills flex-wrap gap-2" id="searchTabs" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'all' ? 'active' : '' }}" href="{{ route('search', ['query' => $query, 'type' => 'all']) }}">
                           <i class="fa-solid fa-grid-2 me-1"></i> All Content <span class="badge bg-light text-dark ms-1">{{ $totalResults }}</span>
                        </a>
                     </li>
                     @if($servicesCount > 0)
                     <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'services' ? 'active' : '' }}" href="{{ route('search', ['query' => $query, 'type' => 'services']) }}">
                           <i class="fa-solid fa-stethoscope me-1"></i> Services <span class="badge bg-light text-dark ms-1">{{ $servicesCount }}</span>
                        </a>
                     </li>
                     @endif
                     @if($blogsCount > 0)
                     <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'blogs' ? 'active' : '' }}" href="{{ route('search', ['query' => $query, 'type' => 'blogs']) }}">
                           <i class="fa-solid fa-newspaper me-1"></i> Research & Articles <span class="badge bg-light text-dark ms-1">{{ $blogsCount }}</span>
                        </a>
                     </li>
                     @endif
                     @if($teamCount > 0)
                     <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'team' ? 'active' : '' }}" href="{{ route('search', ['query' => $query, 'type' => 'team']) }}">
                           <i class="fa-solid fa-user-doctor me-1"></i> Specialists <span class="badge bg-light text-dark ms-1">{{ $teamCount }}</span>
                        </a>
                     </li>
                     @endif
                     @if($galleryCount > 0)
                     <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'gallery' ? 'active' : '' }}" href="{{ route('search', ['query' => $query, 'type' => 'gallery']) }}">
                           <i class="fa-solid fa-microscope me-1"></i> Equipment & Lab <span class="badge bg-light text-dark ms-1">{{ $galleryCount }}</span>
                        </a>
                     </li>
                     @endif
                     @if($pagesCount > 0)
                     <li class="nav-item">
                        <a class="nav-link {{ $activeTab == 'pages' ? 'active' : '' }}" href="{{ route('search', ['query' => $query, 'type' => 'pages']) }}">
                           <i class="fa-solid fa-file-lines me-1"></i> Pages <span class="badge bg-light text-dark ms-1">{{ $pagesCount }}</span>
                        </a>
                     </li>
                     @endif
                  </ul>
               </div>
            </div>

            <!-- RESULTS GRID -->
            <div class="row g-4">
               {{-- 1. SERVICES --}}
               @if(($activeTab == 'all' || $activeTab == 'services') && $services->count() > 0)
                  @foreach($services as $srv)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                        <div class="search-card h-100 bg-white rounded-3 shadow-sm overflow-hidden d-flex flex-column transition">
                           <div class="search-card__thumb position-relative overflow-hidden" style="height: 200px; background: #f3f4f6;">
                              <a href="{{ route('service.detail', $srv->slug) }}">
                                 <img src="{{ asset($srv->image ?: ($srv->banner_image ?: 'assets/img/services/services-thumb-01.jpg')) }}" alt="{{ $srv->title }}" class="w-100 h-100" style="object-fit: cover;">
                              </a>
                              <span class="badge bg-success position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                 <i class="fa-solid fa-stethoscope me-1"></i> Service
                              </span>
                           </div>
                           <div class="search-card__body p-4 d-flex flex-column flex-grow-1">
                              <span class="text-muted small mb-1">{{ $srv->category ?: 'Medical Department' }}</span>
                              <h5 class="search-card__title mb-2">
                                 <a href="{{ route('service.detail', $srv->slug) }}" class="text-dark hover-primary text-decoration-none">
                                    {{ $srv->title }}
                                 </a>
                              </h5>
                              <p class="text-secondary small mb-4 flex-grow-1" style="line-height: 1.6;">
                                 {{ Str::limit(strip_tags($srv->short_description ?: $srv->description), 110) }}
                              </p>
                              <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                 <span class="text-primary small fw-semibold">View Department</span>
                                 <a href="{{ route('service.detail', $srv->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Explore <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

               {{-- 2. BLOGS & RESEARCH --}}
               @if(($activeTab == 'all' || $activeTab == 'blogs') && $blogs->count() > 0)
                  @foreach($blogs as $b)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                        <div class="search-card h-100 bg-white rounded-3 shadow-sm overflow-hidden d-flex flex-column transition">
                           <div class="search-card__thumb position-relative overflow-hidden" style="height: 200px; background: #f3f4f6;">
                              <a href="{{ route('blog.detail', $b->slug) }}">
                                 <img src="{{ asset($b->image ?: 'assets/img/blog/blog-thumb-01.jpg') }}" alt="{{ $b->title }}" class="w-100 h-100" style="object-fit: cover;">
                              </a>
                              <span class="badge bg-primary position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                 <i class="fa-solid fa-newspaper me-1"></i> Article & Research
                              </span>
                           </div>
                           <div class="search-card__body p-4 d-flex flex-column flex-grow-1">
                              <span class="text-muted small mb-1">{{ $b->category ?: 'Medical Publication' }}</span>
                              <h5 class="search-card__title mb-2">
                                 <a href="{{ route('blog.detail', $b->slug) }}" class="text-dark hover-primary text-decoration-none">
                                    {{ $b->title }}
                                 </a>
                              </h5>
                              <p class="text-secondary small mb-4 flex-grow-1" style="line-height: 1.6;">
                                 {{ Str::limit(strip_tags($b->summary ?: $b->content), 110) }}
                              </p>
                              <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                 <span class="text-muted small"><i class="fa-regular fa-calendar me-1"></i> {{ $b->published_at ? $b->published_at->format('M d, Y') : 'Recent' }}</span>
                                 <a href="{{ route('blog.detail', $b->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    Read Article <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

               {{-- 3. TEAM MEMBERS / SPECIALISTS --}}
               @if(($activeTab == 'all' || $activeTab == 'team') && $team->count() > 0)
                  @foreach($team as $tm)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                        <div class="search-card h-100 bg-white rounded-3 shadow-sm overflow-hidden d-flex flex-column transition">
                           <div class="search-card__thumb position-relative overflow-hidden" style="height: 220px; background: #eef2f7;">
                              <a href="{{ route('specialist.detail', $tm->slug ?: $tm->id) }}">
                                 <img src="{{ asset($tm->image ?: 'assets/img/team/team-thumb-01.jpg') }}" alt="{{ $tm->name }}" class="w-100 h-100" style="object-fit: cover; object-position: top;">
                              </a>
                              <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm" style="background: #8b5cf6; color: #fff;">
                                 <i class="fa-solid fa-user-doctor me-1"></i> Specialist
                              </span>
                           </div>
                           <div class="search-card__body p-4 d-flex flex-column flex-grow-1">
                              <span class="text-primary small fw-semibold mb-1">{{ $tm->designation ?: 'Medical Expert' }}</span>
                              <h5 class="search-card__title mb-2">
                                 <a href="{{ route('specialist.detail', $tm->slug ?: $tm->id) }}" class="text-dark hover-primary text-decoration-none">
                                    {{ $tm->name }}
                                 </a>
                              </h5>
                              <p class="text-secondary small mb-4 flex-grow-1" style="line-height: 1.6;">
                                 {{ Str::limit(strip_tags($tm->bio ?: ($tm->expertise ?: $tm->personal_experience)), 110) }}
                              </p>
                              <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                 <span class="text-muted small"><i class="fa-solid fa-award text-warning me-1"></i> {{ $tm->experience ?: 'Certified' }}</span>
                                 <a href="{{ route('specialist.detail', $tm->slug ?: $tm->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    View Doctor <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

               {{-- 4. GALLERY ITEMS --}}
               @if(($activeTab == 'all' || $activeTab == 'gallery') && $gallery->count() > 0)
                  @foreach($gallery as $g)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                        <div class="search-card h-100 bg-white rounded-3 shadow-sm overflow-hidden d-flex flex-column transition">
                           <div class="search-card__thumb position-relative overflow-hidden" style="height: 200px; background: #fef3c7;">
                              <a href="{{ $g->link ?: url('/gallery') }}">
                                 <img src="{{ asset($g->image ?: 'assets/img/gallery/gallery-thumb-01.jpg') }}" alt="{{ $g->title }}" class="w-100 h-100" style="object-fit: cover;">
                              </a>
                              <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                 <i class="fa-solid fa-microscope me-1"></i> Equipment / Lab
                              </span>
                           </div>
                           <div class="search-card__body p-4 d-flex flex-column flex-grow-1">
                              <span class="text-muted small mb-1">{{ $g->category ?: 'Lab Showcase' }}</span>
                              <h5 class="search-card__title mb-2">
                                 <a href="{{ $g->link ?: url('/gallery') }}" class="text-dark hover-primary text-decoration-none">
                                    {{ $g->title }}
                                 </a>
                              </h5>
                              <p class="text-secondary small mb-4 flex-grow-1" style="line-height: 1.6;">
                                 Explore advanced biotechnology instruments and hospital installations by Innotech Medical.
                              </p>
                              <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                 <span class="text-muted small">Showcase</span>
                                 <a href="{{ $g->link ?: url('/gallery') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 text-dark">
                                    View Gallery <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

               {{-- 5. PAGES --}}
               @if(($activeTab == 'all' || $activeTab == 'pages') && $pages->count() > 0)
                  @foreach($pages as $p)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12">
                        <div class="search-card h-100 bg-white rounded-3 shadow-sm overflow-hidden d-flex flex-column transition p-4">
                           <div class="d-flex align-items-center mb-3">
                              <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: #f1f5f9; color: #475569;">
                                 <i class="fa-solid fa-file-lines fa-lg"></i>
                              </div>
                              <div>
                                 <span class="badge bg-secondary mb-1">Company Page</span>
                                 <h5 class="mb-0">
                                    <a href="{{ $p->url ?? route('page.show', $p->slug) }}" class="text-dark hover-primary text-decoration-none">
                                       {{ $p->title }}
                                    </a>
                                 </h5>
                              </div>
                           </div>
                           <p class="text-secondary small mb-4 flex-grow-1" style="line-height: 1.6;">
                              {{ Str::limit(strip_tags($p->content), 120) }}
                           </p>
                           <div class="border-top pt-3 text-end">
                              <a href="{{ $p->url ?? route('page.show', $p->slug) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                 {{ (isset($p->is_custom) && !$p->is_custom) ? 'Visit Page' : 'Read Page' }} <i class="fa-solid fa-arrow-right ms-1"></i>
                              </a>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif
            </div>

         @else
            <!-- EMPTY STATE -->
            <div class="row justify-content-center">
               <div class="col-lg-8">
                  <div class="card border-0 shadow-sm text-center p-5 rounded-4 bg-white">
                     <div class="mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 90px; height: 90px; background: #f0fdf4; color: #10b981;">
                           <i class="fa-light fa-magnifying-glass fa-3x"></i>
                        </div>
                     </div>
                     <h3 class="fw-bold mb-2">No results found {{ !empty($query) ? 'for "' . $query . '"' : '' }}</h3>
                     <p class="text-muted mb-4" style="max-width: 520px; margin: 0 auto;">
                        We couldn't find any services, medical articles, specialists, or pages matching your search. Please check your spelling or try popular search terms below.
                     </p>

                     <div class="popular-searches d-flex flex-wrap justify-content-center gap-2 mb-4">
                        <span class="text-muted align-self-center me-2 small fw-semibold">Popular keywords:</span>
                        <a href="{{ route('search', ['query' => 'cardiology']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Cardiology</a>
                        <a href="{{ route('search', ['query' => 'surgery']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Surgery</a>
                        <a href="{{ route('search', ['query' => 'doctor']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Specialist Doctor</a>
                        <a href="{{ route('search', ['query' => 'research']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Clinical Research</a>
                        <a href="{{ route('search', ['query' => 'equipment']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Medical Equipment</a>
                        <a href="{{ route('search', ['query' => 'contact']) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Contact Us</a>
                     </div>

                     <div>
                        <a href="{{ url('/') }}" class="tp-btn">
                           <i class="fa-light fa-house me-2"></i> Return to Homepage
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         @endif
      </div>
   </section>
</main>

<style>
.search-filter-pills .nav-link {
   background: #ffffff;
   border: 1px solid #e2e8f0;
   color: #475569;
   font-weight: 500;
   font-size: 0.95rem;
   padding: 10px 22px;
   border-radius: 50px;
   transition: all 0.25s ease;
}
.search-filter-pills .nav-link:hover {
   border-color: #0E63FF;
   color: #0E63FF;
}
.search-filter-pills .nav-link.active {
   background: #0E63FF !important;
   border-color: #0E63FF !important;
   color: #ffffff !important;
}
.search-filter-pills .nav-link.active .badge {
   background: #ffffff !important;
   color: #0E63FF !important;
}
.search-card {
   transition: transform 0.25s ease, box-shadow 0.25s ease;
   border: 1px solid #f1f5f9;
}
.search-card:hover {
   transform: translateY(-5px);
   box-shadow: 0 16px 32px rgba(14, 99, 255, 0.08) !important;
}
.hover-primary:hover {
   color: #0E63FF !important;
}
</style>
@endsection
