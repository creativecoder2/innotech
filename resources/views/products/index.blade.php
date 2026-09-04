@extends('layouts.app')

@section('title', ($selectedCompany ? $selectedCompany->name . ' Products' : 'Medical Products & Equipment') . ' | ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('meta_description', 'Explore our comprehensive medical catalog featuring advanced hospital ICU systems, patient monitors, diagnostic devices, endoscopy, and surgical equipment.')
@section('canonical_url', route('products'))

@section('content')
<main>

   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-90 pb-100 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('products_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-7 col-md-7 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title" id="breadcrumb-title">
                     @if($selectedCompany)
                        {{ $selectedCompany->name }} Products
                     @else
                        {{ \App\Models\Setting::get('products_banner_title', 'Medical Products & Equipment') }}
                     @endif
                  </h2>
               </div>
            </div>
            <div class="col-lg-5 col-md-5 col-12">
               <div class="tp-breadcrumb__link d-flex align-items-center justify-content-md-end">
                  <span>Innotech : <a href="{{ route('products') }}">{{ \App\Models\Setting::get('products_banner_subtitle', 'Products') }}</a> @if($selectedCompany) / <span class="text-white" id="breadcrumb-company-name">{{ $selectedCompany->name }}</span> @endif</span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- 2. PRODUCTS CATALOG SECTION -->
   <section class="products-catalog-area pt-70 pb-100 grey-bg-2" style="background-color: #f8fafc;">
      <div class="container">

         <!-- FILTER & SEARCH BAR -->
         <div class="product-filter-wrapper mb-45 bg-white p-4 rounded-4 shadow-sm border border-light">
            <div class="row g-3 align-items-center justify-content-between">
               <!-- Company Pills / Tabs -->
               <div class="col-xl-8 col-lg-7 col-12">
                  <div class="d-flex flex-wrap gap-2 align-items-center" id="company-pills-container">
                     <span class="text-muted fw-bold me-2 small text-uppercase" style="letter-spacing: 0.5px;"><i class="fa-solid fa-filter me-1 text-primary"></i> Company:</span>
                     
                     <a href="{{ route('products', $search ? ['search' => $search] : []) }}" 
                        data-company=""
                        data-url="{{ route('products', $search ? ['search' => $search] : []) }}"
                        class="company-filter-pill btn btn-sm rounded-pill px-3 py-2 fw-semibold transition {{ !$selectedCompany ? 'btn-primary text-white shadow-sm' : 'btn-outline-secondary' }}"
                        style="font-size: 13px;">
                        All Companies ({{ $totalProductsCount }})
                     </a>

                     @foreach($companies as $comp)
                        <a href="{{ route('products', array_merge(['company' => $comp->slug], $search ? ['search' => $search] : [])) }}" 
                           data-company="{{ $comp->slug }}"
                           data-url="{{ route('products', array_merge(['company' => $comp->slug], $search ? ['search' => $search] : [])) }}"
                           class="company-filter-pill btn btn-sm rounded-pill px-3 py-2 fw-semibold transition {{ ($selectedCompany && $selectedCompany->id === $comp->id) ? 'btn-primary text-white shadow-sm' : 'btn-outline-secondary' }}"
                           style="font-size: 13px;">
                           {{ $comp->name }}
                           <span class="badge {{ ($selectedCompany && $selectedCompany->id === $comp->id) ? 'bg-white text-primary' : 'bg-light text-dark' }} rounded-pill ms-1">{{ $comp->active_products_count }}</span>
                        </a>
                     @endforeach
                  </div>
               </div>

               <!-- Search Input -->
               <div class="col-xl-4 col-lg-5 col-12">
                  <form id="products-search-form" action="{{ route('products') }}" method="GET" class="position-relative">
                     <input type="hidden" name="company" id="search-company-hidden" value="{{ $selectedCompany ? $selectedCompany->slug : '' }}">
                     <input type="text" name="search" id="products-search-input" value="{{ $search }}" placeholder="Search products, models, specs..." 
                            class="form-control rounded-pill pe-5 py-2 ps-4 border-1" style="font-size: 14px; border-color: #cbd5e1;" autocomplete="off">
                     <button type="submit" class="btn btn-primary rounded-circle position-absolute end-0 top-50 translate-middle-y me-1 d-flex align-items-center justify-content-center" 
                             style="width: 34px; height: 34px;">
                        <i class="fa-solid fa-magnifying-glass" style="font-size: 13px;"></i>
                     </button>
                  </form>
               </div>
            </div>

            <!-- Meta info bar -->
            <div id="products-filter-meta">
               @include('products._meta')
            </div>
         </div>

         <!-- 3. PRODUCTS GRID CONTAINER (AJAX REPLACED) -->
         <div class="position-relative" style="min-height: 350px;">
            <!-- Subtle Loading Spinner -->
            <div id="products-grid-loader" class="position-absolute top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" 
                 style="background: rgba(248, 250, 252, 0.75); z-index: 10; border-radius: 14px; backdrop-filter: blur(2px);">
               <div class="text-center">
                  <div class="spinner-border text-primary mb-2" role="status" style="width: 2.4rem; height: 2.4rem;">
                     <span class="visually-hidden">Loading...</span>
                  </div>
                  <div class="small fw-semibold text-primary">Updating Catalog...</div>
               </div>
            </div>

            <!-- Dynamic Product Grid Content -->
            <div id="products-grid-container" style="transition: opacity 0.25s ease;">
               @include('products._grid')
            </div>
         </div>

      </div>
   </section>
   <!-- products-catalog-area-end -->

</main>

<style>
   .product-item-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(14, 99, 255, 0.1) !important;
      border-color: #0E63FF !important;
   }
   .product-item-card:hover .product-image-box img {
      transform: scale(1.05);
   }
   .product-item-card:hover .product-card-arrow i {
      color: #0E63FF !important;
      transform: translateX(3px);
   }
   .product-card-title:hover {
      color: #0E63FF !important;
   }
   .company-filter-pill {
      cursor: pointer;
      user-select: none;
   }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
   const gridContainer = document.getElementById('products-grid-container');
   const metaContainer = document.getElementById('products-filter-meta');
   const breadcrumbTitle = document.getElementById('breadcrumb-title');
   const breadcrumbCompanyName = document.getElementById('breadcrumb-company-name');
   const searchForm = document.getElementById('products-search-form');
   const searchInput = document.getElementById('products-search-input');
   const searchCompanyHidden = document.getElementById('search-company-hidden');
   const loader = document.getElementById('products-grid-loader');

   let currentAbortController = null;

   function loadProducts(url, updateHistory = true) {
      if (currentAbortController) {
         currentAbortController.abort();
      }
      currentAbortController = new AbortController();

      if (loader) {
         loader.classList.remove('d-none');
         loader.classList.add('d-flex');
      }
      if (gridContainer) {
         gridContainer.style.opacity = '0.5';
      }

      fetch(url, {
         headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
         },
         signal: currentAbortController.signal
      })
      .then(res => res.json())
      .then(data => {
         if (data.success) {
            if (gridContainer) {
               gridContainer.innerHTML = data.html;
            }
            if (metaContainer) {
               metaContainer.innerHTML = data.meta_html;
            }
            if (breadcrumbTitle && data.breadcrumb_title) {
               breadcrumbTitle.textContent = data.breadcrumb_title;
            }
            if (breadcrumbCompanyName) {
               breadcrumbCompanyName.textContent = data.selected_company_slug ? data.breadcrumb_title.replace(' Products', '') : '';
            }
            if (data.title) {
               document.title = data.title;
            }
            if (searchCompanyHidden) {
               searchCompanyHidden.value = data.selected_company_slug || '';
            }

            // Update company pills active highlight
            const pills = document.querySelectorAll('.company-filter-pill');
            pills.forEach(pill => {
               const compSlug = pill.dataset.company || '';
               const badge = pill.querySelector('.badge');
               if (compSlug === (data.selected_company_slug || '')) {
                  pill.className = 'company-filter-pill btn btn-sm rounded-pill px-3 py-2 fw-semibold transition btn-primary text-white shadow-sm';
                  if (badge) {
                     badge.className = 'badge bg-white text-primary rounded-pill ms-1';
                  }
               } else {
                  pill.className = 'company-filter-pill btn btn-sm rounded-pill px-3 py-2 fw-semibold transition btn-outline-secondary';
                  if (badge) {
                     badge.className = 'badge bg-light text-dark rounded-pill ms-1';
                  }
               }
            });

            if (updateHistory) {
               window.history.pushState({ url: url }, '', url);
            }
         }
      })
      .catch(err => {
         if (err.name !== 'AbortError') {
            console.error('Failed to load products:', err);
         }
      })
      .finally(() => {
         if (loader) {
            loader.classList.remove('d-flex');
            loader.classList.add('d-none');
         }
         if (gridContainer) {
            gridContainer.style.opacity = '1';
         }
      });
   }

   // Intercept clicks on company pills and clear filter buttons
   document.addEventListener('click', function (e) {
      const pill = e.target.closest('.company-filter-pill') || e.target.closest('.filter-pill-btn');
      if (pill) {
         e.preventDefault();
         const targetUrl = pill.dataset.url || pill.getAttribute('href');
         if (targetUrl) {
            loadProducts(targetUrl, true);
         }
         return;
      }

      // AJAX Pagination clicks
      const pageLink = e.target.closest('.ajax-pagination a');
      if (pageLink) {
         e.preventDefault();
         const pageUrl = pageLink.getAttribute('href');
         if (pageUrl) {
            loadProducts(pageUrl, true);
            const wrapper = document.querySelector('.product-filter-wrapper');
            if (wrapper) {
               wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
         }
      }
   });

   // Search form AJAX submit
   if (searchForm) {
      searchForm.addEventListener('submit', function (e) {
         e.preventDefault();
         const formData = new FormData(searchForm);
         const searchParams = new URLSearchParams(formData).toString();
         const searchUrl = `${searchForm.action}?${searchParams}`;
         loadProducts(searchUrl, true);
      });
   }

   // Instant search with debounce
   let debounceTimer = null;
   if (searchInput) {
      searchInput.addEventListener('input', function () {
         clearTimeout(debounceTimer);
         debounceTimer = setTimeout(() => {
            const formData = new FormData(searchForm);
            const searchParams = new URLSearchParams(formData).toString();
            const searchUrl = `${searchForm.action}?${searchParams}`;
            loadProducts(searchUrl, true);
         }, 350);
      });
   }

   // Support browser Back and Forward navigation without reload
   window.addEventListener('popstate', function () {
      loadProducts(window.location.href, false);
   });
});
</script>
@endpush

@endsection
