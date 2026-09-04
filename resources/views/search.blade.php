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
   <section class="search-results-area pt-70 pb-100" style="background-color: #f8fafc;">
      <div class="container">
         <!-- Search Refine Header Box -->
         <div class="row justify-content-center mb-45">
            <div class="col-lg-10">
               <div class="search-refine-box p-4 p-md-5 bg-white rounded-4 shadow-sm position-relative overflow-hidden">
                  <div class="search-box-accent-bar"></div>
                  
                  <form action="{{ route('search') }}" method="GET" class="search-form-wrapper d-flex flex-column flex-md-row gap-3 align-items-stretch">
                     <div class="input-group input-group-lg flex-grow-1 search-input-group">
                        <span class="input-group-text bg-white border-end-0 text-primary ps-4">
                           <i class="fa-solid fa-magnifying-glass fs-5"></i>
                        </span>
                        <input type="text" name="query" class="form-control border-start-0 ps-2 fs-6 fw-medium" placeholder="Search services, medical equipment, specialists, articles..." value="{{ $query }}" required autocomplete="off">
                     </div>
                     <button type="submit" class="tp-btn-theme search-submit-btn">
                        <span>Search Again</span>
                        <i class="fa-light fa-arrow-right ms-2"></i>
                     </button>
                  </form>

                  @if(!empty($query))
                     <div class="mt-4 pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div class="search-stats-text">
                           <i class="fa-solid fa-circle-check text-success me-2"></i>
                           Found <strong class="text-navy fs-6">{{ $totalResults }}</strong> {{ Str::plural('result', $totalResults) }} for "<span class="text-primary fw-bold">{{ $query }}</span>"
                        </div>
                        @if($totalResults > 0)
                           <div class="small text-muted d-flex align-items-center">
                              <i class="fa-regular fa-filter me-1 text-primary"></i> Filter results instantly without reloading:
                           </div>
                        @endif
                     </div>
                  @endif
               </div>
            </div>
         </div>

         @if(!empty($query) && $totalResults > 0)
            <!-- Category Filter Tabs (Zero Reload Client-Side Filtering) -->
            <div class="row mb-40">
               <div class="col-12">
                  <div class="search-tabs-container">
                     <ul class="nav nav-pills justify-content-center search-filter-pills flex-wrap gap-2" id="searchTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                           <button type="button" class="nav-link search-tab-btn {{ $activeTab == 'all' ? 'active' : '' }}" data-filter="all" role="tab" aria-selected="{{ $activeTab == 'all' ? 'true' : 'false' }}">
                              <i class="fa-solid fa-grid-2 me-1"></i> All Content <span class="badge ms-1 filter-badge">{{ $totalResults }}</span>
                           </button>
                        </li>

                        @if(isset($productsCount) && $productsCount > 0)
                        <li class="nav-item" role="presentation">
                           <button type="button" class="nav-link search-tab-btn {{ $activeTab == 'products' ? 'active' : '' }}" data-filter="products" role="tab" aria-selected="{{ $activeTab == 'products' ? 'true' : 'false' }}">
                              <i class="fa-solid fa-laptop-medical me-1"></i> Medical Equipment <span class="badge ms-1 filter-badge">{{ $productsCount }}</span>
                           </button>
                        </li>
                        @endif

                        @if($servicesCount > 0)
                        <li class="nav-item" role="presentation">
                           <button type="button" class="nav-link search-tab-btn {{ $activeTab == 'services' ? 'active' : '' }}" data-filter="services" role="tab" aria-selected="{{ $activeTab == 'services' ? 'true' : 'false' }}">
                              <i class="fa-solid fa-stethoscope me-1"></i> Services <span class="badge ms-1 filter-badge">{{ $servicesCount }}</span>
                           </button>
                        </li>
                        @endif

                        @if($blogsCount > 0)
                        <li class="nav-item" role="presentation">
                           <button type="button" class="nav-link search-tab-btn {{ $activeTab == 'blogs' ? 'active' : '' }}" data-filter="blogs" role="tab" aria-selected="{{ $activeTab == 'blogs' ? 'true' : 'false' }}">
                              <i class="fa-solid fa-newspaper me-1"></i> Research & Articles <span class="badge ms-1 filter-badge">{{ $blogsCount }}</span>
                           </button>
                        </li>
                        @endif

                        @if($teamCount > 0)
                        <li class="nav-item" role="presentation">
                           <button type="button" class="nav-link search-tab-btn {{ $activeTab == 'team' ? 'active' : '' }}" data-filter="team" role="tab" aria-selected="{{ $activeTab == 'team' ? 'true' : 'false' }}">
                              <i class="fa-solid fa-user-doctor me-1"></i> Specialists <span class="badge ms-1 filter-badge">{{ $teamCount }}</span>
                           </button>
                        </li>
                        @endif

                        @if($galleryCount > 0)
                        <li class="nav-item" role="presentation">
                           <button type="button" class="nav-link search-tab-btn {{ $activeTab == 'gallery' ? 'active' : '' }}" data-filter="gallery" role="tab" aria-selected="{{ $activeTab == 'gallery' ? 'true' : 'false' }}">
                              <i class="fa-solid fa-microscope me-1"></i> Equipment & Lab <span class="badge ms-1 filter-badge">{{ $galleryCount }}</span>
                           </button>
                        </li>
                        @endif

                        @if($pagesCount > 0)
                        <li class="nav-item" role="presentation">
                           <button type="button" class="nav-link search-tab-btn {{ $activeTab == 'pages' ? 'active' : '' }}" data-filter="pages" role="tab" aria-selected="{{ $activeTab == 'pages' ? 'true' : 'false' }}">
                              <i class="fa-solid fa-file-lines me-1"></i> Pages <span class="badge ms-1 filter-badge">{{ $pagesCount }}</span>
                           </button>
                        </li>
                        @endif
                     </ul>
                  </div>
               </div>
            </div>

            <!-- RESULTS GRID -->
            <div class="row g-4 search-results-grid" id="searchResultsGrid">
               
               {{-- 1. MEDICAL PRODUCTS & EQUIPMENT --}}
               @if(isset($products) && $products->count() > 0)
                  @foreach($products as $prd)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12 search-item-card" data-category="products" style="display: {{ ($activeTab == 'all' || $activeTab == 'products') ? 'block' : 'none' }};">
                        <div class="search-card h-100 bg-white rounded-4 shadow-sm overflow-hidden d-flex flex-column transition position-relative">
                           <div class="search-card__thumb position-relative overflow-hidden" style="height: 220px; background: #ffffff;">
                              <a href="{{ route('product.detail', $prd->slug) }}" class="d-block w-100 h-100 p-3 text-center">
                                 <img src="{{ asset($prd->image ?: 'assets/img/shop/shop-01.jpg') }}" alt="{{ $prd->title }}" class="w-100 h-100 search-card-img" style="object-fit: contain; transition: transform 0.4s ease;">
                              </a>
                              <span class="badge-category-theme badge-product position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                 <i class="fa-solid fa-laptop-medical me-1"></i> Equipment
                              </span>
                              @if($prd->sku)
                              <span class="badge bg-dark bg-opacity-75 text-white position-absolute bottom-0 start-0 m-3 px-2 py-1 rounded" style="font-size: 11px;">
                                 SKU: {{ $prd->sku }}
                              </span>
                              @endif
                           </div>
                           <div class="search-card__body p-4 d-flex flex-column flex-grow-1">
                              <div class="search-card__meta mb-2">
                                 <span class="badge-meta-label badge-meta-cyan">
                                    {{ $prd->company ? $prd->company->name : 'Medical Device' }}
                                 </span>
                              </div>
                              <h5 class="search-card__title mb-2">
                                 <a href="{{ route('product.detail', $prd->slug) }}" class="text-navy hover-primary text-decoration-none">
                                    {{ $prd->title }}
                                 </a>
                              </h5>
                              <p class="search-card__snippet small mb-4 flex-grow-1">
                                 {{ Str::limit(strip_tags($prd->short_description ?: $prd->description), 110) }}
                              </p>
                              <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                 <span class="text-muted small fw-semibold">
                                    <i class="fa-solid fa-shield-check text-primary me-1"></i> Certified
                                 </span>
                                 <a href="{{ route('product.detail', $prd->slug) }}" class="btn-card-action">
                                    View Details <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

               {{-- 2. SERVICES --}}
               @if($services->count() > 0)
                  @foreach($services as $srv)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12 search-item-card" data-category="services" style="display: {{ ($activeTab == 'all' || $activeTab == 'services') ? 'block' : 'none' }};">
                        <div class="search-card h-100 bg-white rounded-4 shadow-sm overflow-hidden d-flex flex-column transition position-relative">
                           <div class="search-card__thumb position-relative overflow-hidden" style="height: 220px; background: #f8fafc;">
                              <a href="{{ route('service.detail', $srv->slug) }}" class="d-block w-100 h-100">
                                 <img src="{{ asset($srv->image ?: ($srv->banner_image ?: 'assets/img/services/services-thumb-01.jpg')) }}" alt="{{ $srv->title }}" class="w-100 h-100 search-card-img" style="object-fit: cover; transition: transform 0.4s ease;">
                              </a>
                              <span class="badge-category-theme badge-service position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                 <i class="fa-solid fa-stethoscope me-1"></i> Service
                              </span>
                           </div>
                           <div class="search-card__body p-4 d-flex flex-column flex-grow-1">
                              <div class="search-card__meta mb-2">
                                 <span class="badge-meta-label badge-meta-green">
                                    {{ $srv->category ?: 'Medical Department' }}
                                 </span>
                              </div>
                              <h5 class="search-card__title mb-2">
                                 <a href="{{ route('service.detail', $srv->slug) }}" class="text-navy hover-primary text-decoration-none">
                                    {{ $srv->title }}
                                 </a>
                              </h5>
                              <p class="search-card__snippet small mb-4 flex-grow-1">
                                 {{ Str::limit(strip_tags($srv->short_description ?: $srv->description), 110) }}
                              </p>
                              <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                 <span class="text-muted small fw-semibold">
                                    <i class="fa-solid fa-circle-check text-success me-1"></i> Department
                                 </span>
                                 <a href="{{ route('service.detail', $srv->slug) }}" class="btn-card-action">
                                    Explore <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

               {{-- 3. BLOGS & RESEARCH --}}
               @if($blogs->count() > 0)
                  @foreach($blogs as $b)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12 search-item-card" data-category="blogs" style="display: {{ ($activeTab == 'all' || $activeTab == 'blogs') ? 'block' : 'none' }};">
                        <div class="search-card h-100 bg-white rounded-4 shadow-sm overflow-hidden d-flex flex-column transition position-relative">
                           <div class="search-card__thumb position-relative overflow-hidden" style="height: 220px; background: #f8fafc;">
                              <a href="{{ route('blog.detail', $b->slug) }}" class="d-block w-100 h-100">
                                 <img src="{{ asset($b->image ?: 'assets/img/blog/blog-thumb-01.jpg') }}" alt="{{ $b->title }}" class="w-100 h-100 search-card-img" style="object-fit: cover; transition: transform 0.4s ease;">
                              </a>
                              <span class="badge-category-theme badge-blog position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                 <i class="fa-solid fa-newspaper me-1"></i> Article & Research
                              </span>
                           </div>
                           <div class="search-card__body p-4 d-flex flex-column flex-grow-1">
                              <div class="search-card__meta mb-2">
                                 <span class="badge-meta-label badge-meta-blue">
                                    {{ $b->category ?: 'Medical Publication' }}
                                 </span>
                              </div>
                              <h5 class="search-card__title mb-2">
                                 <a href="{{ route('blog.detail', $b->slug) }}" class="text-navy hover-primary text-decoration-none">
                                    {{ $b->title }}
                                 </a>
                              </h5>
                              <p class="search-card__snippet small mb-4 flex-grow-1">
                                 {{ Str::limit(strip_tags($b->summary ?: $b->content), 110) }}
                              </p>
                              <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                 <span class="text-muted small fw-semibold">
                                    <i class="fa-regular fa-calendar text-primary me-1"></i> {{ $b->published_at ? $b->published_at->format('M d, Y') : 'Recent' }}
                                 </span>
                                 <a href="{{ route('blog.detail', $b->slug) }}" class="btn-card-action">
                                    Read Article <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

               {{-- 4. TEAM MEMBERS / SPECIALISTS --}}
               @if($team->count() > 0)
                  @foreach($team as $tm)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12 search-item-card" data-category="team" style="display: {{ ($activeTab == 'all' || $activeTab == 'team') ? 'block' : 'none' }};">
                        <div class="search-card h-100 bg-white rounded-4 shadow-sm overflow-hidden d-flex flex-column transition position-relative">
                           <div class="search-card__thumb position-relative overflow-hidden" style="height: 230px; background: #eef2f7;">
                              <a href="{{ route('specialist.detail', $tm->slug ?: $tm->id) }}" class="d-block w-100 h-100">
                                 <img src="{{ asset($tm->image ?: 'assets/img/team/team-thumb-01.jpg') }}" alt="{{ $tm->name }}" class="w-100 h-100 search-card-img" style="object-fit: cover; object-position: top; transition: transform 0.4s ease;">
                              </a>
                              <span class="badge-category-theme badge-specialist position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                 <i class="fa-solid fa-user-doctor me-1"></i> Specialist
                              </span>
                           </div>
                           <div class="search-card__body p-4 d-flex flex-column flex-grow-1">
                              <div class="search-card__meta mb-2">
                                 <span class="badge-meta-label badge-meta-purple">
                                    {{ $tm->designation ?: 'Medical Expert' }}
                                 </span>
                              </div>
                              <h5 class="search-card__title mb-2">
                                 <a href="{{ route('specialist.detail', $tm->slug ?: $tm->id) }}" class="text-navy hover-primary text-decoration-none">
                                    {{ $tm->name }}
                                 </a>
                              </h5>
                              <p class="search-card__snippet small mb-4 flex-grow-1">
                                 {{ Str::limit(strip_tags($tm->bio ?: ($tm->expertise ?: $tm->personal_experience)), 110) }}
                              </p>
                              <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                 <span class="text-muted small fw-semibold">
                                    <i class="fa-solid fa-award text-warning me-1"></i> {{ $tm->experience ?: 'Certified' }}
                                 </span>
                                 <a href="{{ route('specialist.detail', $tm->slug ?: $tm->id) }}" class="btn-card-action">
                                    View Doctor <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

               {{-- 5. GALLERY ITEMS --}}
               @if($gallery->count() > 0)
                  @foreach($gallery as $g)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12 search-item-card" data-category="gallery" style="display: {{ ($activeTab == 'all' || $activeTab == 'gallery') ? 'block' : 'none' }};">
                        <div class="search-card h-100 bg-white rounded-4 shadow-sm overflow-hidden d-flex flex-column transition position-relative">
                           <div class="search-card__thumb position-relative overflow-hidden" style="height: 220px; background: #fef3c7;">
                              <a href="{{ $g->link ?: url('/gallery') }}" class="d-block w-100 h-100">
                                 <img src="{{ asset($g->image ?: 'assets/img/gallery/gallery-thumb-01.jpg') }}" alt="{{ $g->title }}" class="w-100 h-100 search-card-img" style="object-fit: cover; transition: transform 0.4s ease;">
                              </a>
                              <span class="badge-category-theme badge-gallery position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm">
                                 <i class="fa-solid fa-microscope me-1"></i> Equipment / Lab
                              </span>
                           </div>
                           <div class="search-card__body p-4 d-flex flex-column flex-grow-1">
                              <div class="search-card__meta mb-2">
                                 <span class="badge-meta-label badge-meta-amber">
                                    {{ $g->category ?: 'Lab Showcase' }}
                                 </span>
                              </div>
                              <h5 class="search-card__title mb-2">
                                 <a href="{{ $g->link ?: url('/gallery') }}" class="text-navy hover-primary text-decoration-none">
                                    {{ $g->title }}
                                 </a>
                              </h5>
                              <p class="search-card__snippet small mb-4 flex-grow-1">
                                 Explore advanced biotechnology instruments and hospital installations by Innotech Medical.
                              </p>
                              <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                 <span class="text-muted small fw-semibold">
                                    <i class="fa-solid fa-camera text-warning me-1"></i> Showcase
                                 </span>
                                 <a href="{{ $g->link ?: url('/gallery') }}" class="btn-card-action">
                                    View Gallery <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

               {{-- 6. PAGES --}}
               @if($pages->count() > 0)
                  @foreach($pages as $p)
                     <div class="col-xl-4 col-lg-6 col-md-6 col-12 search-item-card" data-category="pages" style="display: {{ ($activeTab == 'all' || $activeTab == 'pages') ? 'block' : 'none' }};">
                        <div class="search-card h-100 bg-white rounded-4 shadow-sm overflow-hidden d-flex flex-column transition position-relative p-4">
                           <div class="d-flex align-items-center mb-3">
                              <div class="page-icon-box rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0">
                                 <i class="fa-solid fa-file-lines fs-5"></i>
                              </div>
                              <div>
                                 <span class="badge-meta-label badge-meta-slate mb-1">
                                    Company Page
                                 </span>
                                 <h5 class="search-card__title mb-0">
                                    <a href="{{ $p->url ?? route('page.show', $p->slug) }}" class="text-navy hover-primary text-decoration-none">
                                       {{ $p->title }}
                                    </a>
                                 </h5>
                              </div>
                           </div>
                           <p class="search-card__snippet small mb-4 flex-grow-1">
                              {{ Str::limit(strip_tags($p->content), 125) }}
                           </p>
                           <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                              <span class="text-muted small fw-semibold">
                                 <i class="fa-solid fa-link text-secondary me-1"></i> Official Page
                              </span>
                              <a href="{{ $p->url ?? route('page.show', $p->slug) }}" class="btn-card-action">
                                 {{ (isset($p->is_custom) && !$p->is_custom) ? 'Visit Page' : 'Read Page' }} <i class="fa-solid fa-arrow-right ms-1"></i>
                              </a>
                           </div>
                        </div>
                     </div>
                  @endforeach
               @endif

            </div>

            <!-- DYNAMIC CATEGORY EMPTY NOTICE (Shown if a filtered category has 0 items) -->
            <div id="tabEmptyAlert" class="row justify-content-center mt-30" style="display: none;">
               <div class="col-lg-6 text-center py-5 bg-white rounded-4 shadow-sm">
                  <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; background: #f1f5f9; color: #64748b;">
                     <i class="fa-light fa-box-open fa-2x"></i>
                  </div>
                  <h5 class="fw-bold text-navy mb-2">No matching records in <span id="activeCategoryName">this category</span></h5>
                  <p class="text-muted small mb-3">Please select another category tab or view "All Content".</p>
                  <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-4" onclick="document.querySelector('[data-filter=all]').click()">
                     Show All Results
                  </button>
               </div>
            </div>

         @else
            <!-- EMPTY STATE -->
            <div class="row justify-content-center">
               <div class="col-lg-8">
                  <div class="card border-0 shadow-sm text-center p-5 rounded-4 bg-white">
                     <div class="mb-4">
                        <div class="empty-state-icon-circle d-inline-flex align-items-center justify-content-center">
                           <i class="fa-light fa-magnifying-glass fa-3x"></i>
                        </div>
                     </div>
                     <h3 class="fw-bold text-navy mb-2">No results found {{ !empty($query) ? 'for "' . $query . '"' : '' }}</h3>
                     <p class="text-muted mb-4" style="max-width: 520px; margin: 0 auto; font-size: 15px; line-height: 1.6;">
                        We couldn't find any medical equipment, services, specialists, articles, or pages matching your search. Try checking your spelling or explore the suggested terms below.
                     </p>

                     <div class="popular-searches d-flex flex-wrap justify-content-center gap-2 mb-4">
                        <span class="text-muted align-self-center me-2 small fw-semibold">Popular keywords:</span>
                        <a href="{{ route('search', ['query' => 'cardiology']) }}" class="btn-keyword-pill">Cardiology</a>
                        <a href="{{ route('search', ['query' => 'surgery']) }}" class="btn-keyword-pill">Surgery</a>
                        <a href="{{ route('search', ['query' => 'doctor']) }}" class="btn-keyword-pill">Specialist Doctor</a>
                        <a href="{{ route('search', ['query' => 'equipment']) }}" class="btn-keyword-pill">Medical Equipment</a>
                        <a href="{{ route('search', ['query' => 'research']) }}" class="btn-keyword-pill">Clinical Research</a>
                        <a href="{{ route('search', ['query' => 'contact']) }}" class="btn-keyword-pill">Contact Us</a>
                     </div>

                     <div class="mt-2">
                        <a href="{{ url('/') }}" class="tp-btn-theme">
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
/* === INNOTECH MEDICAL SEARCH THEME STYLES === */
.text-navy {
   color: #171151 !important;
}

/* Search Refine Card */
.search-refine-box {
   border: 1px solid #e2e8f0;
   box-shadow: 0 10px 30px rgba(23, 17, 81, 0.05) !important;
}
.search-box-accent-bar {
   position: absolute;
   top: 0;
   left: 0;
   right: 0;
   height: 4px;
   background: linear-gradient(90deg, #0E63FF 0%, #00D26A 50%, #0E63FF 100%);
}
.search-input-group {
   border: 1.5px solid #e2e8f0;
   border-radius: 12px;
   overflow: hidden;
   transition: all 0.25s ease;
   background: #ffffff;
}
.search-input-group:focus-within {
   border-color: #0E63FF;
   box-shadow: 0 0 0 4px rgba(14, 99, 255, 0.12);
}
.search-input-group input:focus {
   box-shadow: none;
   background: transparent;
}
.search-submit-btn {
   height: 56px;
   padding: 0 32px !important;
   min-width: 175px;
}
.search-stats-text {
   color: #475569;
   font-size: 15px;
}

/* Filter Tab Pills */
.search-filter-pills .search-tab-btn {
   background: #ffffff;
   border: 1.5px solid #e2e8f0;
   color: #334155;
   font-weight: 600;
   font-size: 0.92rem;
   padding: 10px 22px;
   border-radius: 50px;
   transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
   box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
   cursor: pointer;
   display: inline-flex;
   align-items: center;
}
.search-filter-pills .search-tab-btn:hover {
   border-color: #0E63FF;
   color: #0E63FF;
   transform: translateY(-2px);
   box-shadow: 0 6px 16px rgba(14, 99, 255, 0.12);
}
.search-filter-pills .search-tab-btn.active {
   background: linear-gradient(135deg, #0E63FF 0%, #0056e0 100%) !important;
   border-color: #0E63FF !important;
   color: #ffffff !important;
   box-shadow: 0 6px 20px rgba(14, 99, 255, 0.35) !important;
}
.search-filter-pills .search-tab-btn .filter-badge {
   background: #f1f5f9;
   color: #475569;
   font-weight: 700;
   font-size: 11px;
   padding: 4px 8px;
   border-radius: 20px;
   transition: all 0.2s ease;
}
.search-filter-pills .search-tab-btn.active .filter-badge {
   background: #ffffff !important;
   color: #0E63FF !important;
}

/* Cards & Layout */
.search-card {
   transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
   border: 1px solid #e2e8f0;
}
.search-card:hover {
   transform: translateY(-6px);
   box-shadow: 0 20px 35px rgba(14, 99, 255, 0.09) !important;
   border-color: #bfdbfe;
}
.search-card:hover .search-card-img {
   transform: scale(1.05);
}
.search-card__title {
   font-size: 18px;
   font-weight: 700;
   line-height: 1.35;
}
.search-card__title a {
   transition: color 0.2s ease;
}
.hover-primary:hover {
   color: #0E63FF !important;
}
.search-card__snippet {
   color: #64748b;
   line-height: 1.65;
}

/* Category Theme Badges on Image Thumb */
.badge-category-theme {
   font-size: 12px;
   font-weight: 600;
   letter-spacing: 0.3px;
   backdrop-filter: blur(8px);
}
.badge-service {
   background: #10b981 !important;
   color: #ffffff !important;
}
.badge-product {
   background: #0284c7 !important;
   color: #ffffff !important;
}
.badge-blog {
   background: #0E63FF !important;
   color: #ffffff !important;
}
.badge-specialist {
   background: #8b5cf6 !important;
   color: #ffffff !important;
}
.badge-gallery {
   background: #f59e0b !important;
   color: #ffffff !important;
}

/* Meta Pill Labels */
.badge-meta-label {
   display: inline-block;
   font-size: 11px;
   font-weight: 700;
   text-transform: uppercase;
   letter-spacing: 0.6px;
   padding: 4px 10px;
   border-radius: 6px;
}
.badge-meta-cyan {
   background: #e0f2fe;
   color: #0284c7;
}
.badge-meta-green {
   background: #ebf7ee;
   color: #10b981;
}
.badge-meta-blue {
   background: #eff6ff;
   color: #0E63FF;
}
.badge-meta-purple {
   background: #f5f3ff;
   color: #7c3aed;
}
.badge-meta-amber {
   background: #fef3c7;
   color: #d97706;
}
.badge-meta-slate {
   background: #f1f5f9;
   color: #475569;
}

/* Action Button */
.btn-card-action {
   background: #f8fafc;
   border: 1.5px solid #e2e8f0;
   color: #0E63FF;
   font-weight: 600;
   font-size: 13px;
   padding: 7px 16px;
   border-radius: 30px;
   text-decoration: none;
   display: inline-flex;
   align-items: center;
   transition: all 0.25s ease;
}
.btn-card-action:hover {
   background: #0E63FF;
   border-color: #0E63FF;
   color: #ffffff;
   transform: translateX(2px);
   box-shadow: 0 4px 12px rgba(14, 99, 255, 0.25);
}

/* Page Icon Box */
.page-icon-box {
   width: 50px;
   height: 50px;
   background: #eff6ff;
   color: #0E63FF;
}

/* Empty State Styling */
.empty-state-icon-circle {
   width: 90px;
   height: 90px;
   background: #f0fdf4;
   color: #10b981;
   border-radius: 50%;
}
.btn-keyword-pill {
   background: #f8fafc;
   border: 1px solid #e2e8f0;
   color: #475569;
   font-size: 13px;
   font-weight: 500;
   padding: 6px 14px;
   border-radius: 30px;
   text-decoration: none;
   transition: all 0.2s ease;
}
.btn-keyword-pill:hover {
   background: #eff6ff;
   border-color: #0E63FF;
   color: #0E63FF;
}
</style>

{{-- JAVASCRIPT: INSTANT NO-REFRESH TAB FILTERING --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
   const tabButtons = document.querySelectorAll('#searchTabs .search-tab-btn');
   const cardItems = document.querySelectorAll('.search-item-card');
   const tabEmptyAlert = document.getElementById('tabEmptyAlert');
   const activeCategoryName = document.getElementById('activeCategoryName');

   const categoryNames = {
      'all': 'All Content',
      'products': 'Medical Equipment',
      'services': 'Services',
      'blogs': 'Research & Articles',
      'team': 'Specialists',
      'gallery': 'Equipment & Lab',
      'pages': 'Pages'
   };

   function filterCategory(category, pushHistory = true) {
      // 1. Highlight Active Tab
      tabButtons.forEach(btn => {
         if (btn.getAttribute('data-filter') === category) {
            btn.classList.add('active');
            btn.setAttribute('aria-selected', 'true');
         } else {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
         }
      });

      // 2. Filter Results Grid
      let visibleCount = 0;
      cardItems.forEach(card => {
         const cardCat = card.getAttribute('data-category');
         if (category === 'all' || cardCat === category) {
            card.style.display = 'block';
            card.style.opacity = '0';
            card.style.transform = 'translateY(12px)';
            card.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            setTimeout(() => {
               card.style.opacity = '1';
               card.style.transform = 'translateY(0)';
            }, 10);
            visibleCount++;
         } else {
            card.style.display = 'none';
         }
      });

      // 3. Show / Hide Category Empty Notice
      if (tabEmptyAlert) {
         if (visibleCount === 0) {
            if (activeCategoryName) {
               activeCategoryName.textContent = categoryNames[category] || category;
            }
            tabEmptyAlert.style.display = 'block';
         } else {
            tabEmptyAlert.style.display = 'none';
         }
      }

      // 4. Update browser URL without reloading page
      if (pushHistory) {
         try {
            const url = new URL(window.location.href);
            url.searchParams.set('type', category);
            window.history.pushState({ category: category }, '', url.toString());
         } catch(e) {
            console.warn('History pushState error:', e);
         }
      }
   }

   // Attach click event to all tabs
   tabButtons.forEach(btn => {
      btn.addEventListener('click', function(e) {
         e.preventDefault(); // PREVENTS PAGE RELOAD
         const filter = this.getAttribute('data-filter');
         filterCategory(filter, true);
      });
   });

   // Handle Browser Back / Forward buttons seamlessly
   window.addEventListener('popstate', function(e) {
      const urlParams = new URLSearchParams(window.location.search);
      const cat = urlParams.get('type') || 'all';
      filterCategory(cat, false);
   });
});
</script>
@endsection
