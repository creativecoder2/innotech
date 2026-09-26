@extends('layouts.app')

@section('title', $item->title . ' - Gallery Album | ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($item->description ?: 'Explore the ' . $item->title . ' photo gallery album and advanced biomedical facilities at Innotech Medical.'), 155))
@section('og_title', $item->title . ' - Innotech Gallery Album')
@section('og_description', \Illuminate\Support\Str::limit(strip_tags($item->description ?: 'Explore the ' . $item->title . ' photo gallery album.'), 155))
@section('og_image', asset($item->image))

@push('styles')
<style>
.gallery-detail-hero-box {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.06);
    padding: 35px 30px;
    border: 1px solid #edf2f7;
    margin-top: -60px;
    position: relative;
    z-index: 5;
}
.gallery-detail-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(14, 99, 255, 0.08);
    color: #0E63FF;
    font-size: 13px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 50px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}
.gallery-photo-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    background: #f8fafc;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.35s ease;
    height: 270px;
    border: 1px solid #eef2f6;
}
.gallery-photo-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
}
.gallery-photo-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 30px rgba(14, 99, 255, 0.16);
    border-color: #0E63FF;
}
.gallery-photo-card:hover img {
    transform: scale(1.08);
}
.gallery-photo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(14, 99, 255, 0) 40%, rgba(9, 30, 66, 0.85) 100%);
    opacity: 0;
    transition: all 0.35s ease;
    display: flex;
    align-items: flex-end;
    padding: 20px;
    z-index: 2;
}
.gallery-photo-card:hover .gallery-photo-overlay {
    opacity: 1;
}
.gallery-photo-zoom-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.7);
    width: 52px;
    height: 52px;
    background: #0E63FF;
    color: #ffffff !important;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 6px 20px rgba(14, 99, 255, 0.5);
    z-index: 3;
}
.gallery-photo-card:hover .gallery-photo-zoom-btn {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}
.gallery-photo-zoom-btn:hover {
    background: #0a4ecc;
    transform: translate(-50%, -50%) scale(1.1);
}
.gallery-detail-meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    background: #f1f5f9;
    border-radius: 30px;
    color: #475569;
    font-size: 13px;
    font-weight: 600;
}
</style>
@endpush

@section('content')
<main>

   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('gallery_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-xl-8 col-lg-8 col-md-12 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title">{{ Str::limit($item->title, 50) }}</h2>
               </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-12">
               <div class="tp-breadcrumb__link d-flex align-items-center justify-content-lg-end">
                  <span>Innotech : <a href="{{ route('gallery') }}">Gallery</a> &nbsp;/&nbsp; {{ $item->category ?: 'Album' }}</span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   @php
      $allImages = $item->all_images;
   @endphp

   <!-- 2. ALBUM HEADER & OVERVIEW BOX -->
   <div class="container mb-50">
      <div class="gallery-detail-hero-box wow fadeInUp" data-wow-delay=".2s">
         <div class="row align-items-center">
            <div class="col-lg-8 col-md-12">
               @if($item->category)
                  <span class="gallery-detail-tag"><i class="fa-solid fa-tag"></i> {{ $item->category }}</span>
               @endif
               <h1 class="fw-bold text-dark mb-3" style="font-size: 2.1rem; line-height: 1.3;">{{ $item->title }}</h1>
               @if($item->description)
                  <p class="text-secondary lead fs-6 mb-4" style="line-height: 1.7;">
                     {{ $item->description }}
                  </p>
               @endif

               <!-- Meta Pills -->
               <div class="d-flex flex-wrap align-items-center gap-3">
                  <div class="gallery-detail-meta-pill">
                     <i class="fa-solid fa-images text-primary"></i>
                     <span>{{ count($allImages) }} {{ count($allImages) === 1 ? 'High-Res Photo' : 'High-Res Photos' }}</span>
                  </div>
                  @if($item->client)
                  <div class="gallery-detail-meta-pill">
                     <i class="fa-solid fa-hospital text-primary"></i>
                     <span>{{ $item->client }}</span>
                  </div>
                  @endif
                  @if($item->date)
                  <div class="gallery-detail-meta-pill">
                     <i class="fa-solid fa-calendar-days text-primary"></i>
                     <span>{{ $item->date }}</span>
                  </div>
                  @endif
               </div>
            </div>
            <div class="col-lg-4 col-md-12 text-lg-end mt-4 mt-lg-0">
               <a href="{{ asset($allImages[0]) }}" class="tp-btn-second album-popup-trigger d-inline-flex align-items-center gap-2" title="{{ $item->title }} - 1/{{ count($allImages) }}">
                  <i class="fa-solid fa-expand"></i>
                  <span>SLIDESHOW VIEW</span>
               </a>
               <div class="mt-2 text-muted small">
                  <i class="fa-solid fa-circle-info text-primary me-1"></i> Click any photo below to zoom & slide
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- 3. PHOTO GALLERY GRID -->
   <section class="gallery-photos-area pb-80">
      <div class="container">
         <div class="d-flex align-items-center justify-content-between mb-35 pb-2 border-bottom">
            <h4 class="fw-bold text-dark mb-0">
               <i class="fa-solid fa-camera text-primary me-2"></i> All Photos in this Album
            </h4>
            <span class="text-muted small">Showing {{ count($allImages) }} Images</span>
         </div>

         <!-- Grid of Photos -->
         <div class="row g-4" id="albumPhotoGrid">
            @foreach($allImages as $idx => $photo)
               <div class="col-xl-4 col-lg-4 col-md-6 col-12 wow fadeInUp" data-wow-delay="{{ 0.1 * ($idx % 3 + 1) }}s">
                  <div class="gallery-photo-card position-relative">
                     <img src="{{ asset($photo) }}" alt="{{ $item->title }} - Photo {{ $idx + 1 }}">
                     <a class="gallery-photo-zoom-btn album-photo-item" href="{{ asset($photo) }}" title="{{ $item->title }} ({{ $idx + 1 }} of {{ count($allImages) }})">
                        <i class="fa-solid fa-plus"></i>
                     </a>
                     <div class="gallery-photo-overlay">
                        <div>
                           <span class="badge bg-white text-dark py-1 px-2.5 rounded-pill small fw-bold mb-1">Photo #{{ $idx + 1 }}</span>
                           <h6 class="text-white mb-0 fw-semibold text-truncate" style="max-width: 260px;">{{ $item->title }}</h6>
                        </div>
                     </div>
                  </div>
               </div>
            @endforeach
         </div>
      </div>
   </section>

   <!-- 4. RELATED ALBUMS -->
   @if(isset($relatedItems) && $relatedItems->count() > 0)
   <section class="related-gallery-area pt-70 pb-80 bg-light">
      <div class="container">
         <div class="row align-items-center mb-40">
            <div class="col-md-8 col-12">
               <div class="tp-section">
                  <span class="tp-section__sub-title left-line">Explore More</span>
                  <h3 class="tp-section__title">Related Albums & Works</h3>
               </div>
            </div>
            <div class="col-md-4 col-12 text-md-end mt-2 mt-md-0">
               <a href="{{ route('gallery') }}" class="tp-btn-second">View All Gallery</a>
            </div>
         </div>
         <div class="row">
            @foreach($relatedItems as $rel)
               <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-30">
                  <div class="tp-gallery-grid-item">
                     <div class="tp-gallery__img p-relative" style="height: 250px; overflow: hidden; border-radius: 10px;">
                        <img src="{{ asset($rel->image) }}" alt="{{ $rel->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @if($rel->photos_count > 1)
                           <span class="position-absolute top-0 end-0 m-3 badge bg-primary text-white py-1 px-2.5 rounded-pill font-weight-semibold shadow-sm" style="z-index: 2;">
                              <i class="fa-solid fa-images me-1"></i> {{ $rel->photos_count }} Photos
                           </span>
                        @endif
                     </div>
                     <div class="tp-gallery__content pt-3">
                        <h4 class="tp-gallery__title"><a href="{{ route('gallery.detail', $rel->id) }}">{{ $rel->title }}</a></h4>
                        <div class="d-flex align-items-center justify-content-between pt-1">
                           <span><i class="fa-solid fa-tag"></i><a href="{{ route('gallery') }}">{{ $rel->category ?: 'General' }}</a></span>
                           <a href="{{ route('gallery.detail', $rel->id) }}" class="small text-primary fw-bold text-decoration-none">
                              Open Album <i class="fa-solid fa-arrow-right ms-1"></i>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            @endforeach
         </div>
      </div>
   </section>
   @endif

   <!-- 5. CTA SECTION -->
   <section class="cta-area theme-bg pt-50 pb-50">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-8 col-md-8 col-12">
               <div class="tp-cta-title">
                  <h2 class="text-white mb-0">{{ \App\Models\Setting::get('cta_title', 'Looking for biomedical equipment or turnkey laboratory setup?') }}</h2>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-12 text-md-end mt-3 mt-md-0">
               <a class="tp-btn-second" href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('cta_phone', '+92 331 6699992')) }}">{{ \App\Models\Setting::get('cta_phone', '+92 331 6699992') }}</a>
            </div>
         </div>
      </div>
   </section>

</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
   // Init Lightbox Slider for all album photos
   $('#albumPhotoGrid').magnificPopup({
      delegate: '.album-photo-item',
      type: 'image',
      gallery: {
         enabled: true,
         navigateByImgClick: true,
         preload: [0, 2],
         tPrev: 'Previous (Left Arrow)',
         tNext: 'Next (Right Arrow)',
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

   // Top slideshow button trigger
   $('.album-popup-trigger').on('click', function(e) {
      e.preventDefault();
      $('#albumPhotoGrid .album-photo-item').first().trigger('click');
   });
});
</script>
@endpush
