@extends('layouts.app')

@section('title', $product->title . ' | ' . ($product->company ? $product->company->name : 'Medical Equipment') . ' - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('meta_description', Str::limit(strip_tags($product->short_description ?: $product->description), 160))
@section('canonical_url', route('product.detail', $product->slug))
@section('og_title', $product->title)
@section('og_description', Str::limit(strip_tags($product->short_description ?: $product->description), 160))
@section('og_image', asset($product->image ?: 'assets/img/shop/shop-01.jpg'))

@section('content')
<main>

   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-80 pb-90 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('product_detail_banner_image', \App\Models\Setting::get('products_banner_image', 'assets/img/banner/breadcrumb-01.jpg'))) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-8 col-md-7 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title" style="font-size: 32px; line-height: 1.3;">{{ $product->title }}</h2>
               </div>
            </div>
            <div class="col-lg-4 col-md-5 col-12">
               <div class="tp-breadcrumb__link d-flex align-items-center justify-content-md-end">
                  <span><a href="{{ url('/') }}">Home</a> / <a href="{{ route('products') }}">Products</a> @if($product->company) / <a href="{{ route('products', ['company' => $product->company->slug]) }}">{{ $product->company->name }}</a> @endif</span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- 2. PRODUCT DETAIL MAIN AREA (MATCHING USER SCREENSHOT EXACTLY) -->
   <section class="product-detail-area pt-70 pb-90" style="background-color: #ffffff;">
      <div class="container">
         
         @if(session('demo_success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 p-3 mb-4 shadow-sm" role="alert">
               <i class="fa-solid fa-circle-check me-2"></i> {{ session('demo_success') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
         @endif

         <div class="row g-5 align-items-stretch product-detail-row">
            
            <!-- LEFT COLUMN: LARGE PRODUCT IMAGE CARD (STICKY ON SCROLL & CLICK TO ZOOM) -->
            <div class="col-xl-5 col-lg-5 col-12 product-sticky-col">
               <div class="product-sticky-card">
                  <div class="product-detail-image-card bg-white rounded-4 d-flex align-items-center justify-content-center p-4 position-relative" 
                       id="productImageTrigger"
                       role="button"
                       tabindex="0"
                       aria-label="Click to enlarge product image"
                       title="Click to view full image"
                       style="border: 1.5px solid #e2e8f0; border-radius: 16px; min-height: 480px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); cursor: zoom-in;">
                     <img src="{{ asset($product->image ?: 'assets/img/shop/shop-01.jpg') }}" 
                          alt="{{ $product->title }}" 
                          class="img-fluid product-main-img" 
                          style="max-height: 440px; width: 100%; object-fit: contain;">
                     
                     <!-- Interactive Zoom Badge -->
                     <span class="product-zoom-hint position-absolute bottom-0 end-0 mb-3 me-3 d-inline-flex align-items-center gap-2 bg-white text-dark shadow-sm px-3 py-1 rounded-pill border" style="font-size: 12px; font-weight: 600; opacity: 0.9; transition: all 0.25s ease;">
                        <i class="fa-solid fa-magnifying-glass-plus text-primary"></i> Click to enlarge
                     </span>
                  </div>
               </div>
            </div>

            <!-- RIGHT COLUMN: DETAILS, KEY FEATURES, REQUEST A DEMO -->
            <div class="col-xl-7 col-lg-7 col-12">
               <div class="product-detail-content ps-xl-3">
                  
                  <!-- Product Title -->
                  <h1 class="product-main-title mb-3" style="font-size: 26px; font-weight: 700; color: #1e293b; line-height: 1.35;">
                     {{ $product->title }}
                  </h1>

                  <!-- Divider Line -->
                  <hr style="border-top: 1.5px solid #e2e8f0; margin: 18px 0 22px 0; opacity: 1;">

                  <!-- Manufacturer / Brand -->
                  <div class="product-meta mb-3" style="font-size: 15.5px;">
                     <strong style="color: #0f172a;">Manufacturer:</strong> 
                     <span style="color: #334155; font-weight: 500;">
                        {{ $product->company ? $product->company->name : 'Innotech Principal Partner' }}
                        @if($product->company && $product->company->country)
                           ({{ $product->company->country }})
                        @endif
                     </span>
                     @if($product->sku)
                        <span class="badge bg-light text-secondary border ms-2 px-2 py-1" style="font-size: 12px; font-weight: 600;">Model: {{ $product->sku }}</span>
                     @endif
                  </div>

                  <!-- Short / Overview Description -->
                  <div class="product-summary mb-4" style="color: #475569; font-size: 15px; line-height: 1.7;">
                     <p class="mb-0">
                        {{ $product->short_description ?: Str::limit($product->description, 200) }}
                     </p>
                  </div>

                  <!-- Key Features -->
                  @php
                     $features = $product->features_list;
                  @endphp

                  @if(count($features) > 0)
                     <div class="product-features-box mb-4">
                        <h4 class="fw-bold mb-3" style="font-size: 16px; color: #0f172a; text-transform: capitalize;">Key Features</h4>
                        <ul class="product-features-list list-unstyled ps-0 mb-0">
                           @foreach($features as $feature)
                              <li class="d-flex align-items-baseline mb-2" style="font-size: 14.5px; color: #334155; line-height: 1.6;">
                                 <span class="bullet-dot me-3 flex-shrink-0" style="width: 6px; height: 6px; background-color: #0f172a; border-radius: 50%; display: inline-block;"></span>
                                 <span>{{ $feature }}</span>
                              </li>
                           @endforeach
                        </ul>
                     </div>
                  @endif

                  <!-- Detailed Description (if any) -->
                  @if(!empty($product->description) && $product->description !== $product->short_description)
                     <div class="product-long-desc mb-4 p-3 rounded-3 bg-light border border-light" style="font-size: 14px; color: #64748b; line-height: 1.65;">
                        {!! nl2br(e($product->description)) !!}
                     </div>
                  @endif

                  <!-- ACTION BUTTON: REQUEST A DEMO (MATCHING USER SCREENSHOT EXACTLY) -->
                  <div class="product-action-box pt-2 mb-4">
                     <button type="button" 
                             class="btn-request-demo d-inline-flex align-items-center gap-2" 
                             data-bs-toggle="modal" 
                             data-bs-target="#requestDemoModal"
                             style="background-color: #048C5B; color: #ffffff; font-weight: 600; font-size: 15px; padding: 12px 34px; border-radius: 6px; border: none; transition: all 0.25s ease; box-shadow: 0 4px 12px rgba(4, 140, 91, 0.25);">
                        Request a Demo
                     </button>
                     
                     <a href="{{ url('/contact') }}?product={{ urlencode($product->title) }}" 
                        class="btn btn-link text-secondary text-decoration-none ms-3 fw-semibold small">
                        <i class="fa-solid fa-phone me-1 text-primary"></i> Contact Sales Directly
                     </a>
                  </div>

                  <!-- Quick Spec Badges -->
                  <div class="d-flex flex-wrap gap-2 pt-2 border-top border-light">
                     <span class="small text-muted me-2"><i class="fa-solid fa-shield-check text-success me-1"></i> OEM Warranty Certified</span>
                     <span class="small text-muted me-2"><i class="fa-solid fa-truck-fast text-primary me-1"></i> Turnkey Installation</span>
                     <span class="small text-muted"><i class="fa-solid fa-headset text-info me-1"></i> 24/7 Biomedical Support</span>
                  </div>

               </div>
            </div>

         </div>

         <!-- 3. RELATED PRODUCTS SECTION -->
         @if(isset($relatedProducts) && $relatedProducts->count() > 0)
         <div class="related-products-area mt-80 pt-40 border-top" style="border-color: #f1f5f9;">
            <div class="d-flex align-items-center justify-content-between mb-35">
               <h3 class="fw-bold mb-0" style="font-size: 20px; color: #1e293b;">Related Products</h3>
               <a href="{{ route('products', $product->company ? ['company' => $product->company->slug] : []) }}" 
                  class="fw-semibold small text-primary text-decoration-none">
                  View More in Catalog <i class="fa-solid fa-arrow-right ms-1"></i>
               </a>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
               @foreach($relatedProducts as $rel)
                  <div class="col">
                     <div class="product-item-card h-100 bg-white rounded-3 d-flex flex-column" 
                          style="border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 20px 18px 16px 18px; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                        
                        <!-- Related Product Image -->
                        <a href="{{ route('product.detail', $rel->slug) }}" 
                           class="product-image-box d-flex align-items-center justify-content-center mb-3" 
                           style="height: 190px; background-color: #ffffff; border-radius: 10px; overflow: hidden;">
                           <img src="{{ asset($rel->image ?: 'assets/img/shop/shop-01.jpg') }}" 
                                alt="{{ $rel->title }}" 
                                class="img-fluid" 
                                style="max-height: 170px; max-width: 90%; object-fit: contain; transition: transform 0.3s ease;">
                        </a>

                        <!-- Related Product Footer: Title & Arrow -->
                        <div class="product-card-footer mt-auto pt-2 border-top border-light d-flex align-items-center justify-content-between">
                           <a href="{{ route('product.detail', $rel->slug) }}" 
                              class="product-card-title text-decoration-none fw-bold flex-grow-1 pe-2" 
                              style="font-size: 12.5px; color: #1e293b; line-height: 1.3; letter-spacing: 0.3px; text-transform: uppercase;">
                              {{ Str::limit($rel->title, 42) }}
                           </a>
                           
                           <a href="{{ route('product.detail', $rel->slug) }}" 
                              class="product-card-arrow d-flex align-items-center justify-content-center text-dark flex-shrink-0" 
                              style="width: 26px; height: 26px;" 
                              title="View Details">
                              <i class="fa-solid fa-chevron-right" style="font-size: 13px; font-weight: 900; color: #334155;"></i>
                           </a>
                        </div>

                     </div>
                  </div>
               @endforeach
            </div>
         </div>
         @endif

      </div>
   </section>
   <!-- product-detail-main-area-end -->

   <!-- 4. REQUEST A DEMO INTERACTIVE MODAL -->
   <div class="modal fade" id="requestDemoModal" tabindex="-1" aria-labelledby="requestDemoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-light px-4 pt-4 pb-3">
               <div>
                  <span class="badge bg-success mb-2 px-2.5 py-1">Clinical Demonstration</span>
                  <h5 class="modal-title fw-bold text-dark" id="requestDemoModalLabel">Request Product Demo</h5>
                  <p class="text-secondary small mb-0">{{ $product->title }}</p>
               </div>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="demoRequestForm" action="{{ route('product.demo_request', $product->id) }}" method="POST">
               @csrf
               <div class="modal-body p-4">
                  <div id="demoSuccessAlert" class="alert alert-success d-none rounded-3 py-2 px-3 mb-3 small"></div>
                  <div id="demoErrorAlert" class="alert alert-danger d-none rounded-3 py-2 px-3 mb-3 small"></div>

                  <div class="mb-3">
                     <label class="form-label small fw-bold text-secondary">Your Name *</label>
                     <input type="text" name="name" required class="form-control rounded-3" placeholder="Dr. / Engr. Full Name">
                  </div>

                  <div class="row g-2 mb-3">
                     <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Official Email *</label>
                        <input type="email" name="email" required class="form-control rounded-3" placeholder="doctor@hospital.org">
                     </div>
                     <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary">Phone / WhatsApp *</label>
                        <input type="text" name="phone" required class="form-control rounded-3" placeholder="+92 331 1234567">
                     </div>
                  </div>

                  <div class="mb-3">
                     <label class="form-label small fw-bold text-secondary">Hospital / Healthcare Facility</label>
                     <input type="text" name="hospital" class="form-control rounded-3" placeholder="Hospital Name & City">
                  </div>

                  <div class="mb-2">
                     <label class="form-label small fw-bold text-secondary">Message / Preferred Demo Date</label>
                     <textarea name="message" rows="3" class="form-control rounded-3" placeholder="Please specify your department or any specific questions regarding this unit..."></textarea>
                  </div>
               </div>
               <div class="modal-footer border-0 px-4 pb-4 pt-0">
                  <button type="button" class="btn btn-light rounded-3 px-3 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" id="demoSubmitBtn" class="btn btn-success rounded-3 px-4 fw-bold" style="background-color: #048C5B; border-color: #048C5B;">
                     <span class="spinner-border spinner-border-sm d-none me-1" id="demoSpinner"></span>
                     Submit Demo Request
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>

   <!-- 5. FULL PRODUCT IMAGE LIGHTBOX MODAL (WITH CLOSE 'X' BUTTON) -->
   <div id="productLightboxModal" class="product-lightbox-modal" aria-hidden="true" role="dialog" aria-modal="true">
      <div class="lightbox-backdrop" id="lightboxBackdrop" title="Click anywhere outside to close"></div>
      <div class="lightbox-dialog">
         <!-- Close 'X' Button -->
         <button type="button" class="lightbox-close-btn" id="lightboxCloseBtn" aria-label="Close full view" title="Close (Esc)">
            <i class="fa-solid fa-xmark"></i>
         </button>

         <!-- Enlarged Image Container -->
         <div class="lightbox-img-wrap">
            <img src="{{ asset($product->image ?: 'assets/img/shop/shop-01.jpg') }}" 
                 alt="{{ $product->title }}" 
                 class="lightbox-img" 
                 id="lightboxImg">
         </div>

         <!-- Product Caption & Keyboard Hint -->
         <div class="lightbox-footer text-center mt-3">
            <h4 class="text-white mb-1 fw-bold fs-6">{{ $product->title }}</h4>
            @if($product->sku)
               <span class="badge bg-light text-dark fw-semibold px-2 py-1">Model: {{ $product->sku }}</span>
            @endif
            <p class="text-white-50 small mb-0 mt-2">
               <i class="fa-solid fa-circle-info me-1"></i> Press <kbd class="bg-dark text-white px-2 py-0.5 border border-secondary rounded">ESC</kbd> or click outside to close
            </p>
         </div>
      </div>
   </div>

</main>

<style>
   /* Ensure body and html do not trap viewport sticky calculation */
   html, body {
      overflow-x: clip !important;
   }

   /* Sticky Left Column on Scroll */
   .product-detail-row {
      align-items: stretch !important;
   }
   .product-sticky-col {
      align-self: stretch !important;
      position: relative !important;
   }
   .product-sticky-card {
      position: -webkit-sticky !important;
      position: sticky !important;
      top: 115px !important;
      z-index: 20;
      align-self: flex-start;
   }
   @media (max-width: 991px) {
      .product-sticky-card {
         position: static !important;
      }
   }

   /* Product Detail Main Card Hover & Zoom Hint */
   .product-detail-image-card {
      transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
   }
   .product-detail-image-card:hover {
      border-color: #0E63FF !important;
      box-shadow: 0 10px 30px rgba(14, 99, 255, 0.12) !important;
      transform: translateY(-2px);
   }
   .product-detail-image-card:hover .product-main-img {
      transform: scale(1.02);
      transition: transform 0.3s ease;
   }
   .product-detail-image-card:hover .product-zoom-hint {
      background-color: #0E63FF !important;
      color: #ffffff !important;
      border-color: #0E63FF !important;
      box-shadow: 0 4px 14px rgba(14, 99, 255, 0.35) !important;
   }
   .product-detail-image-card:hover .product-zoom-hint i {
      color: #ffffff !important;
   }

   /* Lightbox Modal Overlay */
   .product-lightbox-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: 99999;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.25s ease;
      align-items: center;
      justify-content: center;
   }
   .product-lightbox-modal.active {
      display: flex;
      opacity: 1;
      visibility: visible;
   }
   .lightbox-backdrop {
      position: absolute;
      inset: 0;
      background: rgba(15, 23, 42, 0.94);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      cursor: zoom-out;
   }
   .lightbox-dialog {
      position: relative;
      z-index: 2;
      max-width: 92vw;
      max-height: 92vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transform: scale(0.94);
      transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
   }
   .product-lightbox-modal.active .lightbox-dialog {
      transform: scale(1);
   }
   .lightbox-close-btn {
      position: fixed;
      top: 24px;
      right: 28px;
      width: 48px;
      height: 48px;
      background: rgba(255, 255, 255, 0.15);
      border: 1.5px solid rgba(255, 255, 255, 0.3);
      color: #ffffff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      cursor: pointer;
      transition: all 0.2s ease;
      z-index: 100001;
   }
   .lightbox-close-btn:hover {
      background: #ef4444;
      border-color: #ef4444;
      color: #ffffff;
      transform: rotate(90deg) scale(1.1);
      box-shadow: 0 4px 15px rgba(239, 68, 68, 0.5);
   }
   .lightbox-img-wrap {
      background: #ffffff;
      padding: 16px;
      border-radius: 16px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      max-width: 90vw;
      max-height: 78vh;
      overflow: hidden;
   }
   .lightbox-img {
      max-width: 100%;
      max-height: 74vh;
      width: auto;
      height: auto;
      object-fit: contain;
      border-radius: 8px;
      user-select: none;
   }
   @media (max-width: 576px) {
      .lightbox-close-btn {
         top: 16px;
         right: 16px;
         width: 40px;
         height: 40px;
         font-size: 20px;
      }
      .lightbox-img-wrap {
         padding: 10px;
      }
      .lightbox-img {
         max-height: 68vh;
      }
   }

   .btn-request-demo:hover {
      background-color: #03734a !important;
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(4, 140, 91, 0.35) !important;
   }
   .product-item-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 25px rgba(14, 99, 255, 0.1) !important;
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
</style>

@push('scripts')
<script>
   document.addEventListener('DOMContentLoaded', function () {
      // --- PRODUCT IMAGE FULLSCREEN LIGHTBOX ---
      const imageTrigger = document.getElementById('productImageTrigger');
      const lightboxModal = document.getElementById('productLightboxModal');
      const closeBtn = document.getElementById('lightboxCloseBtn');
      const backdrop = document.getElementById('lightboxBackdrop');

      function openLightbox() {
         if (!lightboxModal) return;
         lightboxModal.classList.add('active');
         lightboxModal.setAttribute('aria-hidden', 'false');
         document.body.style.overflow = 'hidden';
      }

      function closeLightbox() {
         if (!lightboxModal) return;
         lightboxModal.classList.remove('active');
         lightboxModal.setAttribute('aria-hidden', 'true');
         document.body.style.overflow = '';
      }

      if (imageTrigger) {
         imageTrigger.addEventListener('click', openLightbox);
         imageTrigger.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
               e.preventDefault();
               openLightbox();
            }
         });
      }

      if (closeBtn) {
         closeBtn.addEventListener('click', closeLightbox);
      }

      if (backdrop) {
         backdrop.addEventListener('click', closeLightbox);
      }

      document.addEventListener('keydown', function (e) {
         if (e.key === 'Escape' && lightboxModal && lightboxModal.classList.contains('active')) {
            closeLightbox();
         }
      });

      // --- REQUEST A DEMO AJAX SUBMISSION ---
      const demoForm = document.getElementById('demoRequestForm');
      if (demoForm) {
         demoForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const submitBtn = document.getElementById('demoSubmitBtn');
            const spinner = document.getElementById('demoSpinner');
            const successAlert = document.getElementById('demoSuccessAlert');
            const errorAlert = document.getElementById('demoErrorAlert');

            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            successAlert.classList.add('d-none');
            errorAlert.classList.add('d-none');

            const formData = new FormData(demoForm);

            fetch(demoForm.action, {
               method: 'POST',
               body: formData,
               headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json'
               }
            })
            .then(res => res.json())
            .then(data => {
               submitBtn.disabled = false;
               spinner.classList.add('d-none');
               if (data.success) {
                  successAlert.textContent = data.message;
                  successAlert.classList.remove('d-none');
                  demoForm.reset();
                  setTimeout(() => {
                     const modalEl = document.getElementById('requestDemoModal');
                     const modal = bootstrap.Modal.getInstance(modalEl);
                     if (modal) modal.hide();
                  }, 2500);
               } else {
                  errorAlert.textContent = data.message || 'Something went wrong. Please try again.';
                  errorAlert.classList.remove('d-none');
               }
            })
            .catch(err => {
               submitBtn.disabled = false;
               spinner.classList.add('d-none');
               errorAlert.textContent = 'Failed to send request. Please try again or call our help desk.';
               errorAlert.classList.remove('d-none');
            });
         });
      }
   });
</script>
@endpush
@endsection
