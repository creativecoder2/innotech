@extends('layouts.app')

@section('title', 'Work Gallery - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))

@push('styles')
<style>
   /* ==========================================================================
      Theme-Integrated Work Gallery Styles
      ========================================================================== */

   /* Gallery Filter Tabs */
   .tp-gallery-filter-wrap {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      justify-content: center;
      margin-bottom: 50px;
      font-family: var(--tp-ff-heading, 'Archivo', sans-serif);
   }
   .tp-gallery-filter-btn {
      background: #FFFFFF;
      border: 1px solid var(--tp-border-primary, #ECEEF3);
      color: var(--tp-heading-primary, #171151);
      padding: 11px 26px;
      border-radius: 50px;
      font-size: 13px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 9px;
      box-shadow: 0 4px 12px rgba(23, 17, 81, 0.04);
      font-family: var(--tp-ff-heading, 'Archivo', sans-serif);
   }
   .tp-gallery-filter-btn .filter-count {
      background: var(--tp-grey-1, #F2F5FA);
      color: var(--tp-theme-blue, #239fda);
      padding: 2px 9px;
      border-radius: 50px;
      font-size: 11px;
      font-weight: 800;
      transition: all 0.3s ease;
   }
   .tp-gallery-filter-btn:hover {
      background: var(--tp-theme-blue, #239fda);
      border-color: var(--tp-theme-blue, #239fda);
      color: var(--tp-common-white, #FFFFFF);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(35, 159, 218, 0.28);
   }
   .tp-gallery-filter-btn:hover .filter-count {
      background: rgba(255, 255, 255, 0.25);
      color: #FFFFFF;
   }
   .tp-gallery-filter-btn.active {
      background: var(--tp-theme-blue, #239fda);
      border-color: var(--tp-theme-blue, #239fda);
      color: var(--tp-common-white, #FFFFFF);
      box-shadow: 0 8px 20px rgba(35, 159, 218, 0.3);
   }
   .tp-gallery-filter-btn.active .filter-count {
      background: rgba(255, 255, 255, 0.25);
      color: #FFFFFF;
   }

   /* Gallery Grid Item */
   .tp-gallery-grid-item {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
      margin-bottom: 35px;
      box-shadow: 0 8px 24px rgba(23, 17, 81, 0.08);
      background: #E2E8F0;
      transition: all 0.4s ease;
   }
   .tp-gallery-grid-item:hover {
      transform: translateY(-6px);
      box-shadow: 0 18px 38px rgba(35, 159, 218, 0.2);
   }
   .tp-gallery-grid-item .tp-gallery__img {
      position: relative;
      height: 330px;
      width: 100%;
      overflow: hidden;
   }
   .tp-gallery-grid-item .tp-gallery__img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
   }
   .tp-gallery-grid-item:hover .tp-gallery__img img {
      transform: scale(1.08);
   }
   .tp-gallery-grid-item .tp-gallery__img::before {
      position: absolute;
      content: "";
      width: 100%;
      height: 0%;
      background: linear-gradient(180deg, rgba(35, 159, 218, 0) 15%, rgba(35, 159, 218, 0.92) 90%);
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 1;
      transition: all 0.4s ease-out;
   }
   .tp-gallery-grid-item:hover .tp-gallery__img::before {
      height: 100%;
   }

   /* Green Hexagon Button */
   .tp-gallery-grid-item .tp-gallery__info {
      position: absolute;
      top: 0px;
      right: 25px;
      z-index: 5;
      opacity: 0;
      visibility: hidden;
      transition: all 0.4s ease-out;
   }
   .tp-gallery-grid-item:hover .tp-gallery__info {
      top: 25px;
      transform: rotateY(180deg);
      opacity: 1;
      visibility: visible;
   }
   .tp-gallery-grid-item .tp-gallery__info a {
      clip-path: polygon(0 25%, 50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%);
      background-color: var(--tp-common-white, #FFFFFF);
      display: block;
      height: 68px;
      width: 60px;
      text-align: center;
      line-height: 68px;
      font-size: 20px;
      color: var(--tp-common-white, #FFFFFF) !important;
      position: relative;
      cursor: pointer;
   }
   .tp-gallery-grid-item .tp-gallery__info a::before {
      position: absolute;
      content: "";
      clip-path: polygon(0 25%, 50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%);
      background-color: var(--tp-icon-green, #0b9748);
      display: block;
      height: 58px;
      width: 52px;
      left: 4px;
      top: 5px;
      z-index: -1;
      transition: background-color 0.3s ease;
   }
   .tp-gallery-grid-item .tp-gallery__info a:hover::before {
      background-color: var(--tp-theme-blue, #239fda);
   }

   /* Bottom Overlay Content */
   .tp-gallery-grid-item .tp-gallery__content {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 25px;
      z-index: 5;
      opacity: 0;
      visibility: hidden;
      transition: all 0.4s ease-out;
      font-family: var(--tp-ff-heading, 'Archivo', sans-serif);
   }
   .tp-gallery-grid-item:hover .tp-gallery__content {
      bottom: 12px;
      opacity: 1;
      visibility: visible;
   }
   .tp-gallery-grid-item .tp-gallery__title {
      text-transform: uppercase;
      font-size: 20px;
      color: var(--tp-common-white, #FFFFFF);
      margin-bottom: 5px;
      font-weight: 700;
      letter-spacing: 0.5px;
      font-family: var(--tp-ff-heading, 'Archivo', sans-serif);
   }
   .tp-gallery-grid-item .tp-gallery__title a {
      color: var(--tp-common-white, #FFFFFF);
      text-decoration: none;
   }
   .tp-gallery-grid-item .tp-gallery__content span {
      color: var(--tp-common-white, #FFFFFF);
      text-transform: uppercase;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.5px;
      font-family: var(--tp-ff-body, 'Archivo', sans-serif);
   }
   .tp-gallery-grid-item .tp-gallery__content span i {
      margin-right: 6px;
      font-size: 12px;
   }

   /* ==========================================================================
      High-End Magnific Popup Lightbox (Theme Fonts & SVG Arrows)
      ========================================================================== */
   .mfp-bg {
      background: rgba(15, 23, 42, 0.94) !important;
      backdrop-filter: blur(10px) !important;
      -webkit-backdrop-filter: blur(10px) !important;
      z-index: 99991 !important;
      opacity: 0.96 !important;
   }
   .mfp-wrap {
      z-index: 99992 !important;
      font-family: var(--tp-ff-body, 'Archivo', sans-serif) !important;
   }
   .mfp-image-holder .mfp-content {
      max-width: 90% !important;
   }
   .mfp-figure {
      line-height: 0;
      position: relative;
   }
   .mfp-figure:after {
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6) !important;
      background: transparent !important;
   }
   .mfp-img {
      border-radius: 12px !important;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5) !important;
      padding: 40px 0 !important;
      max-height: 82vh !important;
      margin: 0 auto !important;
   }
   .mfp-bottom-bar {
      margin-top: -30px !important;
      padding: 0 8px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      font-family: var(--tp-ff-body, 'Archivo', sans-serif) !important;
   }
   .mfp-title {
      font-family: var(--tp-ff-heading, 'Archivo', sans-serif) !important;
      font-size: 17px !important;
      font-weight: 700 !important;
      color: #FFFFFF !important;
      line-height: 1.4 !important;
      letter-spacing: 0.3px !important;
   }
   .mfp-counter {
      font-family: var(--tp-ff-body, 'Archivo', sans-serif) !important;
      font-size: 14px !important;
      font-weight: 700 !important;
      color: rgba(255, 255, 255, 0.9) !important;
      background: rgba(255, 255, 255, 0.16) !important;
      padding: 4px 14px !important;
      border-radius: 50px !important;
      position: relative !important;
      top: 0 !important;
      right: 0 !important;
      letter-spacing: 0.5px !important;
   }
   .mfp-close {
      width: 48px !important;
      height: 48px !important;
      line-height: 48px !important;
      background: rgba(255, 255, 255, 0.15) !important;
      border-radius: 50% !important;
      top: 25px !important;
      right: 25px !important;
      color: #FFFFFF !important;
      transition: all 0.3s ease !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      cursor: pointer !important;
      padding: 0 !important;
      border: 1px solid rgba(255, 255, 255, 0.2) !important;
   }
   .mfp-close:hover {
      background: var(--tp-theme-blue, #239fda) !important;
      border-color: var(--tp-theme-blue, #239fda) !important;
      transform: rotate(90deg) !important;
      color: #FFFFFF !important;
   }
   .mfp-arrow {
      width: 58px !important;
      height: 58px !important;
      background: rgba(255, 255, 255, 0.15) !important;
      border-radius: 50% !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      opacity: 0.92 !important;
      transition: all 0.3s ease !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      margin: 0 !important;
      border: 1px solid rgba(255, 255, 255, 0.22) !important;
      color: #FFFFFF !important;
   }
   .mfp-arrow:before, .mfp-arrow:after {
      display: none !important;
   }
   .mfp-arrow:hover {
      background: var(--tp-theme-blue, #239fda) !important;
      border-color: var(--tp-theme-blue, #239fda) !important;
      opacity: 1 !important;
      transform: translateY(-50%) scale(1.1) !important;
   }
   .mfp-arrow-left {
      left: 30px !important;
   }
   .mfp-arrow-right {
      right: 30px !important;
   }
   .mfp-arrow svg, .mfp-close svg {
      display: block !important;
      stroke: #FFFFFF !important;
   }

   /* Contact Section Polishing */
   .gallery-contact-box {
      background: #FFFFFF;
      border-radius: 12px;
      padding: 40px;
      box-shadow: 0px 16px 32px rgba(189, 196, 205, 0.14);
      border: 1px solid var(--tp-border-primary, #ECEEF3);
   }
</style>
@endpush

@section('content')
<main>

   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('gallery_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-xl-7 col-lg-8 col-md-12 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title">{{ \App\Models\Setting::get('gallery_banner_title', 'Work Gallery') }}</h2>
               </div>
            </div>
            <div class="col-xl-5 col-lg-4 col-md-12 col-12">
               <div class="tp-breadcrumb__link serv-md d-flex justify-content-lg-end">
                  <span>Innotech : <a href="{{ url('/gallery') }}">{{ \App\Models\Setting::get('gallery_banner_subtitle', 'Gallery') }}</a></span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- 2. GALLERY AREA -->
   <section class="gallery-area grey-bg pt-120 pb-100" data-background="{{ asset('assets/img/shape/shape-bg-01.png') }}">
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="tp-section text-center">
                  <span class="tp-section__sub-title left-line right-line mb-25">{{ \App\Models\Setting::get('gallery_subtitle', 'Work Gallery') }}</span>
                  <h3 class="tp-section__title mb-50">Our Biomedical Portfolio</h3>
               </div>
            </div>
         </div>

         <!-- Category Filter Pills -->
         <div class="tp-gallery-filter-wrap wow fadeInUp" data-wow-delay=".2s">
            <button type="button" class="tp-gallery-filter-btn active" data-filter="all">
               All Works <span class="filter-count">{{ $galleryItems->count() }}</span>
            </button>
            @foreach($categories as $cat)
               @php
                  $catCount = $galleryItems->where('category', $cat)->count();
               @endphp
               <button type="button" class="tp-gallery-filter-btn" data-filter="{{ Str::slug($cat) }}">
                  {{ $cat }} <span class="filter-count">{{ $catCount }}</span>
               </button>
            @endforeach
         </div>

         <!-- Gallery Grid Items -->
         <div class="row" id="galleryGridList">
            @forelse($galleryItems as $g)
               @php
                  $catSlug = Str::slug($g->category ?: 'general');
               @endphp
               <div class="col-xl-4 col-lg-4 col-md-6 col-12 gallery-filter-item mb-30 wow fadeInUp" data-wow-delay=".3s" data-category="{{ $catSlug }}">
                  <div class="tp-gallery-grid-item">
                     <div class="tp-gallery__img p-relative">
                        <img src="{{ asset($g->image) }}" alt="{{ $g->title }}">
                        <div class="tp-gallery__info">
                           <a class="popup-image" href="{{ asset($g->image) }}" title="{{ $g->title }} - {{ $g->category ?: 'Innotech Medical' }}"><i class="fa-solid fa-plus"></i></a>
                        </div>
                     </div>
                     <div class="tp-gallery__content">
                        <h4 class="tp-gallery__title"><a class="popup-image" href="{{ asset($g->image) }}" title="{{ $g->title }} - {{ $g->category ?: 'Innotech Medical' }}">{{ $g->title }}</a></h4>
                        <span><i class="fa-solid fa-tag"></i><a href="javascript:void(0)">{{ $g->category ?: 'General' }}</a></span>
                     </div>
                  </div>
               </div>
            @empty
               <div class="col-12 text-center py-5">
                  <div class="p-5 bg-white rounded-3 border text-muted">
                     <i class="fa-solid fa-images fa-3x text-primary mb-3"></i>
                     <h4>No Gallery Items Found</h4>
                     <p>Items will appear here once added in Admin Panel.</p>
                  </div>
               </div>
            @endforelse
         </div>
      </div>
   </section>
   <!-- gallery-area-end -->

   <!-- 3. CTA CALL BAR -->
   <section class="cta-area theme-bg pt-50 pb-50">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-8 col-md-8 col-12">
               <div class="tp-cta-title">
                  <h2 class="text-white mb-0">{{ \App\Models\Setting::get('cta_title', 'Looking for a best laboratory Service') }}</h2>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 text-md-end mt-3 mt-md-0">
               <a class="tp-btn-second" href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('cta_phone', '+92 331 6699992')) }}">{{ \App\Models\Setting::get('cta_phone', '+92 331 6699992') }}</a>
            </div>
         </div>
      </div>
   </section>
   <!-- cta-area-end -->

   <!-- 4. CONTACT / CONSULTATION AREA -->
   <section class="contact-area pt-120 pb-115">
      <div class="container">
         <div class="row">
            <div class="col-lg-4 col-md-5 col-12 wow fadeInLeft" data-wow-delay=".3s">
               <div class="tpcontact mr-60 mb-60">
                  <div class="tpcontact__item text-center">
                     <div class="tpcontact__icon mb-20">
                        <img src="{{ asset('assets/img/icon/contact-01.svg') }}" alt="address">
                     </div>
                     <div class="tpcontact__address">
                        <h4 class="tpcontact__title mb-15">Head Office</h4>
                        <span>{{ \App\Models\Setting::get('office_address', '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.') }}</span>
                     </div>
                  </div>
               </div>
               <div class="tpcontact mr-60 mb-60">
                  <div class="tpcontact__item text-center">
                     <div class="tpcontact__icon mb-20">
                        <img src="{{ asset('assets/img/icon/contact-02.svg') }}" alt="phone">
                     </div>
                     <div class="tpcontact__address">
                        <h4 class="tpcontact__title mb-15">24/7 Technical Support</h4>
                        <span><a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992')) }}">{{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }}</a></span>
                     </div>
                  </div>
               </div>
               <div class="tpcontact mr-60 mb-60">
                  <div class="tpcontact__item text-center">
                     <div class="tpcontact__icon mb-20">
                        <img src="{{ asset('assets/img/icon/contact-03.svg') }}" alt="hours">
                     </div>
                     <div class="tpcontact__address">
                        <h4 class="tpcontact__title mb-15">Working Hours</h4>
                        <span>{{ \App\Models\Setting::get('working_hours', 'Monday - Friday 09:00 AM - 05:00 PM') }}</span>
                     </div>
                  </div>
               </div>
            </div>

            <div class="col-lg-8 col-md-7 col-12">
               <div class="contactform gallery-contact-box wow fadeInRight" data-wow-delay=".3s">
                  <span class="tp-section__sub-title left-line mb-10 text-primary fw-bold" style="font-size: 13px; letter-spacing: 1px; text-transform: uppercase;">SEND US AN INQUIRY</span>
                  <h3 class="tp-section__title mb-15" style="font-size: 32px;">Ready to Upgrade Your Hospital or Laboratory?</h3>
                  <p class="text-muted mb-35" style="font-size: 15px; line-height: 24px;">Leave your project requirements, equipment inquiries, or technical support requests below. Our biomedical specialists will assist you immediately.</p>

                  @if(session('success'))
                     <div class="alert alert-success alert-dismissible fade show rounded-3 p-3 mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>
                  @endif

                  <div class="contactform__list">
                     <form id="contact-form" action="{{ route('contact.store') }}" method="post">
                        @csrf
                        <div class="row">
                           <div class="col-lg-6 mb-20">
                              <input class="form-control py-3 px-3 rounded-2" name="name" type="text" placeholder="Enter your full name" required style="border-color: #E2E8F0; font-size: 15px;">
                           </div>
                           <div class="col-lg-6 mb-20">
                              <input class="form-control py-3 px-3 rounded-2" name="email" type="email" placeholder="Enter your email" required style="border-color: #E2E8F0; font-size: 15px;">
                           </div>
                           <div class="col-lg-6 mb-20">
                              <input class="form-control py-3 px-3 rounded-2" name="phone" type="text" placeholder="Enter phone / mobile number" style="border-color: #E2E8F0; font-size: 15px;">
                           </div>
                           <div class="col-lg-6 mb-20">
                              <input class="form-control py-3 px-3 rounded-2" name="subject" type="text" placeholder="Equipment inquiry / Organization" style="border-color: #E2E8F0; font-size: 15px;">
                           </div>
                           <div class="col-lg-12 mb-25">
                              <textarea class="form-control py-3 px-3 rounded-2" name="message" rows="5" placeholder="How can our biomedical team assist your facility?" required style="border-color: #E2E8F0; font-size: 15px;"></textarea>
                           </div>
                           <div class="col-lg-12">
                              <button type="submit" class="tp-btn text-uppercase font-weight-bold" style="background-color: #239fda; border-radius: 6px; padding: 16px 36px;">SEND MESSAGE</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- contact-area-end -->

</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
   // Initialize Magnific Popup with full Gallery Next / Prev Navigation & Crisp Vector SVGs
   function initGalleryPopup() {
      $('#galleryGridList').magnificPopup({
         delegate: '.gallery-filter-item:visible .popup-image',
         type: 'image',
         gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 2],
            tPrev: 'Previous',
            tNext: 'Next',
            tCounter: '<span class="mfp-counter">%curr% of %total%</span>'
         },
         closeMarkup: '<button title="%title%" type="button" class="mfp-close"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>',
         image: {
            titleSrc: function(item) {
               return item.el.attr('title') || '';
            }
         },
         mainClass: 'mfp-fade mfp-with-zoom',
         removalDelay: 300,
         callbacks: {
            buildControls: function() {
               // Inject crisp theme SVG vector arrows
               this.contentContainer.find('.mfp-arrow-left').html('<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>');
               this.contentContainer.find('.mfp-arrow-right').html('<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>');
            },
            change: function() {
               var self = this;
               setTimeout(function() {
                  self.contentContainer.find('.mfp-arrow-left').html('<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>');
                  self.contentContainer.find('.mfp-arrow-right').html('<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>');
               }, 10);
            }
         }
      });
   }

   // Initial setup
   initGalleryPopup();

   // Interactive Category Filtering
   $('.tp-gallery-filter-btn').on('click', function() {
      $('.tp-gallery-filter-btn').removeClass('active');
      $(this).addClass('active');

      const filter = $(this).data('filter');
      const items = $('.gallery-filter-item');

      if (filter === 'all') {
         items.stop(true, true).fadeIn(300, function() {
            initGalleryPopup();
         });
      } else {
         items.each(function() {
            const cat = $(this).data('category');
            if (cat === filter) {
               $(this).stop(true, true).fadeIn(300);
            } else {
               $(this).stop(true, true).fadeOut(200);
            }
         });
         setTimeout(function() {
            initGalleryPopup();
         }, 350);
      }
   });
});
</script>
@endpush
