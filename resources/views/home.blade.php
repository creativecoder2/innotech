@extends('layouts.app')

@section('title', \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))

@push('styles')
<style>
   /* Equal Height Specialist Team Cards on Home Page */
   .team-active .swiper-wrapper {
      align-items: stretch;
   }
   .team-active .swiper-slide {
      height: auto !important;
      display: flex;
   }
   .tp-team {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      background: #FFFFFF;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid var(--tp-border-primary, #ECEEF3);
      box-shadow: 0 6px 20px rgba(23, 17, 81, 0.05);
      transition: all 0.3s ease;
      margin-bottom: 50px;
   }
   .tp-team:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 36px rgba(35, 159, 218, 0.16);
      border-color: var(--tp-theme-blue, #239fda);
   }
   .tp-team__thumb {
      height: 280px !important;
      min-height: 280px !important;
      max-height: 280px !important;
      width: 100%;
      overflow: hidden;
      background: #FFFFFF !important;
      flex-shrink: 0;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      padding-top: 20px;
   }
   .tp-team__thumb a {
      display: block;
      width: 100%;
      height: 100%;
   }
   .tp-team__thumb img {
      width: 100% !important;
      height: 100% !important;
      object-fit: contain !important;
      object-position: bottom center !important;
      display: block;
      transition: transform 0.4s ease;
   }
   .tp-team:hover .tp-team__thumb img {
      transform: scale(1.04);
   }
   .tp-team__content {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      padding: 24px 20px 25px;
      text-align: center;
   }
   .tp-team__title {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 6px;
   }
   .tp-team__title a {
      color: var(--tp-heading-primary, #171151);
      text-decoration: none;
   }
   .tp-team__title a:hover {
      color: var(--tp-theme-blue, #239fda);
   }
   .tp-team__position {
      font-size: 12px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--tp-theme-blue, #239fda);
      margin-bottom: 12px;
      min-height: 28px;
      display: flex;
      align-items: center;
      justify-content: center;
   }
   .tp-team__content p {
      font-size: 13px;
      line-height: 20px;
      color: var(--tp-text-2, #8A879F);
      margin-bottom: 20px;
      flex-grow: 1;
   }
   .team-social-box {
      margin-top: auto;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
   }
   .team-social-btn {
      width: 38px;
      height: 38px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #FFFFFF !important;
      font-size: 16px;
      text-decoration: none;
      transition: all 0.25s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
   }
   .team-social-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
      filter: brightness(1.1);
      color: #FFFFFF !important;
   }
   .team-social-fb {
      background-color: #3B5998 !important;
   }
   .team-social-insta {
      background-color: #179BF0 !important;
   }
   .team-social-tweet {
      background-color: #0084FF !important;
   }
   .team-social-in {
      background-color: #E60023 !important;
   }

   /* Equal Height Testimonial Cards */
   .tp-test-active {
      padding-top: 55px !important;
      padding-bottom: 30px !important;
   }
   .tp-test-active .swiper-wrapper {
      align-items: stretch !important;
      display: flex !important;
   }
   .tp-test-active .swiper-slide {
      height: auto !important;
      display: flex !important;
   }
   .tp-testi {
      height: 100% !important;
      min-height: 100% !important;
      display: flex !important;
      flex-direction: column !important;
      justify-content: space-between !important;
      border-radius: 16px;
      padding: 70px 40px 40px 40px !important;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      position: relative;
      margin-bottom: 30px;
      width: 100%;
   }
   .tp-testi__avata {
      position: absolute;
      top: -38px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 5;
   }
   .tp-testi__avata img {
      width: 76px !important;
      height: 76px !important;
      border-radius: 50% !important;
      object-fit: cover !important;
      border: 4px solid #FFFFFF !important;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.18) !important;
      display: block;
   }
   .tp-testi__content {
      display: flex !important;
      flex-direction: column !important;
      flex-grow: 1 !important;
      justify-content: space-between !important;
      height: 100%;
   }
   .tp-testi__content p {
      flex-grow: 1;
      font-size: 15px;
      line-height: 26px;
      color: var(--tp-text-2, #8A879F);
      margin-bottom: 25px;
   }
   .tp-testi__author-info {
      margin-top: auto;
   }
   .tp-testi__avata-title {
      font-size: 18px;
      font-weight: 700;
      color: var(--tp-heading-primary, #171151);
      margin-bottom: 6px;
   }
   .tp-testi__ava-position {
      font-size: 12px;
      font-weight: 700;
      color: var(--tp-theme-blue, #239fda);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: block;
      min-height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
   }

   /* Brand / Trust Partner Cards UI */
   .partner-card {
      background: #FFFFFF !important;
      border: 1px solid #E2E8F0 !important;
      border-radius: 14px !important;
      box-shadow: 0 4px 16px rgba(23, 17, 81, 0.04) !important;
      height: 105px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      padding: 16px 28px !important;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
      margin: 10px 6px !important;
   }
   .partner-card:hover {
      transform: translateY(-5px) !important;
      box-shadow: 0 14px 30px rgba(35, 159, 218, 0.15) !important;
      border-color: #239fda !important;
   }
   .partner-card a {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      width: 100% !important;
      height: 100% !important;
   }
   .partner-card img {
      max-height: 50px !important;
      max-width: 150px !important;
      width: auto !important;
      height: auto !important;
      object-fit: contain !important;
      opacity: 1 !important;
      filter: none !important;
      transition: transform 0.3s ease !important;
   }
   .partner-card:hover img {
      transform: scale(1.05) !important;
   }

   /* Equal Height Service Area Cards on Home Page */
   .service-active .swiper-wrapper {
      align-items: stretch !important;
      display: flex !important;
   }
   .service-active .swiper-slide {
      height: auto !important;
      display: flex !important;
   }
   .service-active .services-item {
      width: 100% !important;
      height: 100% !important;
      display: flex !important;
      flex-direction: column !important;
      padding: 60px 35px 50px 35px !important;
      margin-bottom: 40px !important;
      box-sizing: border-box !important;
      border-radius: 8px !important;
      background-color: var(--tp-common-white, #FFFFFF);
      transition: all 0.3s ease;
   }
   .service-active .services-item__icon {
      flex-shrink: 0 !important;
      margin-bottom: 30px !important;
   }
   .service-active .services-item__content {
      display: flex !important;
      flex-direction: column !important;
      flex-grow: 1 !important;
      width: 100% !important;
   }
   .service-active .services-item__tp-title {
      font-size: 20px !important;
      line-height: 1.35 !important;
      min-height: 72px !important;
      margin-bottom: 18px !important;
      display: flex !important;
      align-items: flex-start !important;
   }
   .service-active .services-item__content p {
      flex-grow: 1 !important;
      margin-bottom: 25px !important;
      font-size: 14px !important;
      line-height: 22px !important;
      color: var(--tp-text-2, #8A879F);
   }
   .service-active .services-item__btn {
      margin-top: auto !important;
      padding-top: 5px !important;
   }
   @media (max-width: 991px) {
      .service-active .services-item {
         padding: 50px 25px 40px 25px !important;
      }
      .service-active .services-item__tp-title {
         min-height: auto !important;
      }
   }
</style>
@endpush

@section('content')

      <!-- main-area -->
      <main>

         <!-- 1. BANNER AREA -->
         @if(\App\Models\Setting::get('section_banner_enabled', '1') == '1')
         <section class="banner-area p-relative pt-90">
            <div class="container">
               <div class="row">
                  <div class="col-xl-8">
                     <div class="banner__content pt-145 mb-135">
                        <span class="banner__sub-title mb-20">{{ \App\Models\Setting::get('banner_badge', 'Welcome to Innotech Medical Pvt Ltd') }}</span>
                        <h2 class="banner__title mb-30">{{ \App\Models\Setting::get('banner_title', 'Innovating Health Care with Advance Technologies') }}</h2>
                        <p>{{ \App\Models\Setting::get('banner_description', 'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.') }}</p>
                        <div class="banner__btn">
                           <a class="tp-btn" href="{{ url(\App\Models\Setting::get('banner_btn_link', '/contact')) }}">{{ \App\Models\Setting::get('banner_btn_text', 'Contact with Us') }}</a>
                           <a class="tp-btn-second ml-25" href="{{ url(\App\Models\Setting::get('banner_btn2_link', '/about')) }}">{{ \App\Models\Setting::get('banner_btn2_text', 'About us') }}</a>
                        </div>
                     </div>

                     <div class="banner__box-item">
                        <div class="row">
                           <div class="col-xl-4 col-lg-4 col-md-6">
                              <div class="banner__item d-flex align-items-center mb-30 wow fadeInUp" data-wow-delay=".2s">
                                 <div class="banner__item-icon">
                                    <i class="flaticon-rating"></i>
                                 </div>
                                 <div class="banner__item-content">
                                    <span>{{ \App\Models\Setting::get('banner_feature_1', '100% Customer Satisfaction') }}</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6">
                              <div class="banner__item pink-border d-flex align-items-center mb-30 wow fadeInUp" data-wow-delay=".4s">
                                 <div class="banner__item-icon pink-icon">
                                    <i class="flaticon-target"></i>
                                 </div>
                                 <div class="banner__item-content">
                                    <span>{{ \App\Models\Setting::get('banner_feature_2', 'Help and Acess is Our Mission') }}</span>
                                 </div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-lg-4 col-md-6">
                              <div class="banner__item green-border d-flex align-items-center mb-30 wow fadeInUp" data-wow-delay=".6s">
                                 <div class="banner__item-icon green-icon">
                                    <i class="flaticon-premium-badge"></i>
                                 </div>
                                 <div class="banner__item-content">
                                    <span>{{ \App\Models\Setting::get('banner_feature_3', '100% Quality Laboratory service') }}</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="bannerscroll d-none d-xl-block">
               <div class="banner-scroll-btn">
                  <a class="bannerscroll-icon" href="#tp-about-scroll"><i class="fa-light fa-computer-mouse"></i>
                     <span>Scrool Down</span></a>
               </div>
            </div>
            <div class="banner__shape d-none d-lg-block">
               <img src="{{ asset(\App\Models\Setting::get('banner_image', 'assets/img/banner/banner-01.png')) }}" alt="banner-img">
                @php
                    $videoType = \App\Models\Setting::get('banner_video_type', 'url');
                    $videoUrl = \App\Models\Setting::get('banner_video_url', 'https://www.youtube.com/watch?v=d8w5SICzzxc');
                    $videoFile = \App\Models\Setting::get('banner_video_file');
                    $isLocalVideo = ($videoType === 'upload' && !empty($videoFile));
                    $finalVideoSrc = $isLocalVideo ? asset($videoFile) : $videoUrl;
                @endphp
                <div class="banner__video-btn">
                    @if($isLocalVideo)
                        <a class="banner__video-icon" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#localVideoModal"><i class="fa-solid fa-play"></i></a>
                    @else
                        <a class="banner__video-icon popup-video" href="{{ $finalVideoSrc }}"><i class="fa-solid fa-play"></i></a>
                    @endif
                </div>
            </div>
         </section>
         @endif
         <!-- banner-area-end -->

         <!-- Local Video Player Modal -->
         @if(isset($isLocalVideo) && $isLocalVideo)
         <div class="modal fade" id="localVideoModal" tabindex="-1" aria-hidden="true">
             <div class="modal-dialog modal-dialog-centered modal-lg">
                 <div class="modal-content bg-dark border-0 rounded-4 overflow-hidden shadow-lg position-relative">
                     <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" style="z-index: 1056;" data-bs-dismiss="modal" aria-label="Close"></button>
                     <div class="p-2">
                         <video id="heroLocalVideo" class="w-100 rounded-3" controls style="max-height: 75vh; background: #000; display: block;">
                             <source src="{{ $finalVideoSrc }}" type="video/mp4">
                             Your browser does not support HTML5 video.
                         </video>
                     </div>
                 </div>
             </div>
         </div>
         @endif

         <!-- 2. SERVICES AREA -->
         @if(\App\Models\Setting::get('section_services_enabled', '1') == '1')
         <section class="services-area pt-95 pb-90 grey-bg mt-60 fix" id="services-section" data-background="{{ asset('assets/img/shape/shape-bg-01.png') }}">
            <div class="container">
               <div class="row mb-125">
                  <div class="col-lg-12">
                     <div class="search-form position-relative">
                        <form action="{{ route('search') }}" method="GET" id="homeSearchForm" autocomplete="off">
                           <div class="search-input-wrapper position-relative">
                              <input type="text" id="homeSearchInput" name="query" placeholder="{{ \App\Models\Setting::get('services_search_placeholder', 'Search services, doctors, medical research, equipment...') }}" autocomplete="off" spellcheck="false">
                              <button type="button" id="homeSearchClear" class="search-clear-btn" style="display: none;" title="Clear search">
                                 <i class="fa-solid fa-xmark"></i>
                              </button>
                              <button class="tp-btn search-btn" type="submit">Search Here <i class="fa-light fa-magnifying-glass ml-5"></i></button>
                           </div>

                           <!-- LIVE AUTO-SUGGESTIONS DROPDOWN -->
                           <div id="homeSearchDropdown" class="search-suggestions-dropdown" style="display: none;">
                              <!-- Dropdown Header -->
                              <div class="search-dropdown-header d-flex align-items-center justify-content-between">
                                 <span class="dropdown-heading"><i class="fa-solid fa-sparkles text-primary me-1"></i> Live Search Suggestions</span>
                                 <span id="searchResultStats" class="search-stats-badge">0 found</span>
                              </div>

                              <!-- Loading Indicator -->
                              <div id="searchDropdownLoading" class="search-dropdown-loading" style="display: none;">
                                 <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                                 <span>Searching website content...</span>
                              </div>

                              <!-- Suggestions List -->
                              <div id="searchSuggestionsList" class="search-suggestions-list">
                                 <!-- Populated dynamically via AJAX -->
                              </div>

                              <!-- Empty State -->
                              <div id="searchDropdownEmpty" class="search-dropdown-empty" style="display: none;">
                                 <div class="empty-icon"><i class="fa-light fa-magnifying-glass"></i></div>
                                 <p class="empty-title mb-1">No matching content found</p>
                                 <p class="empty-desc small text-muted">Try searching for "Cardiology", "Dentistry", "Specialist", "Surgery", or "About"</p>
                              </div>

                              <!-- Dropdown Footer -->
                              <div class="search-dropdown-footer d-flex align-items-center justify-content-between">
                                 <span class="small text-muted" id="searchFooterHint">Use <kbd class="search-kbd">↑</kbd> <kbd class="search-kbd">↓</kbd> to navigate, <kbd class="search-kbd">Enter</kbd> to view</span>
                                 <a href="#" id="searchViewAllLink" class="search-view-all-btn">
                                    View all results <i class="fa-solid fa-arrow-right ms-1"></i>
                                 </a>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
               <div class="row align-items-center">
                  <div class="col-lg-8 col-md-8 col-12">
                     <div class="tp-section">
                        <span class="tp-section__sub-title left-line mb-20">{{ \App\Models\Setting::get('services_subtitle', 'our Services') }}</span>
                        <h3 class="tp-section__title mb-50">{{ \App\Models\Setting::get('services_title', 'Service Area') }}</h3>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-12">
                     <div class="tp-services d-flex align-items-center">
                        <div class="services-p"><i class="fa-regular fa-arrow-left"></i></div>
                        <div class="services-n"><i class="fa-regular fa-arrow-right"></i></div>
                     </div>
                  </div>
               </div>
               <div class="services-slider wow fadeInUp" data-wow-delay=".3s">
                  <div class="swiper-container service-active">
                     <div class="swiper-wrapper">
                        @if(isset($services) && $services->count() > 0)
                           @foreach($services as $index => $serv)
                              @php
                                 $mod = $index % 4;
                                 $iconClass = $mod == 1 ? 'pink-icon' : ($mod == 2 ? 'green-icon' : ($mod == 3 ? 'sky-icon' : ''));
                                 $hexaClass = $mod == 1 ? 'pink-hexa' : ($mod == 2 ? 'green-hexa' : ($mod == 3 ? 'sky-hexa' : ''));
                              @endphp
                              <div class="swiper-slide">
                                 <div class="services-item mb-40">
                                    <div class="services-item__icon {{ $iconClass }} mb-30">
                                       <i class="{{ $serv->icon ?: 'flaticon-hemoglobin-test-meter' }}"></i>
                                    </div>
                                    <div class="services-item__content">
                                       <h4 class="services-item__tp-title mb-30"><a href="{{ url('/services/' . $serv->slug) }}">{{ strtoupper($serv->title) }}</a></h4>
                                       <p>{{ Str::limit($serv->short_description, 95) }}</p>
                                       <div class="services-item__btn">
                                          <a class="btn-hexa {{ $hexaClass }}" href="{{ url('/services/' . $serv->slug) }}"><i></i>Read More</a>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           @endforeach
                        @else
                           <div class="swiper-slide">
                              <div class="services-item mb-40">
                                 <div class="services-item__icon mb-30">
                                    <i class="flaticon-hemoglobin-test-meter"></i>
                                 </div>
                                 <div class="services-item__content">
                                    <h4 class="services-item__tp-title mb-30"><a href="#">HEMOGLOBIN TEST</a></h4>
                                    <p>Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla.</p>
                                    <div class="services-item__btn">
                                       <a class="btn-hexa" href="#"><i></i>Read More</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- services-area-end -->

         <!-- 3. ABOUT AREA -->
         @if(\App\Models\Setting::get('section_about_enabled', '1') == '1')
         <section id="tp-about-scroll" class="about-area pb-70">
            <div class="container">
               <div class="row">
                  <div class="col-xl-6 col-lg-12">
                     <div class="about__thumb mb-60 wow fadeInLeft" data-wow-delay=".4s">
                        <div class="about__img">
                           <img src="{{ asset(\App\Models\Setting::get('about_image', 'assets/img/about/about-bg-01.png')) }}" alt="about-bg-img">
                           <div class="about__exprience">
                              <h3 class="counter">{{ filter_var(\App\Models\Setting::get('about_experience_years', '7'), FILTER_SANITIZE_NUMBER_INT) ?: 7 }}</h3>
                              <i>Years of <br>Experience</i>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-12">
                     <div class="tp-about__content pt-125 ml-60 mb-50 wow fadeInRight" data-wow-delay=".4s">
                        <div class="tp-section">
                           <span class="tp-section__sub-title left-line mb-25">{{ \App\Models\Setting::get('about_badge', 'Welcome to Innotech Medical Pvt Ltd') }}</span>
                           <h3 class="tp-section__title tp-ab-sm-title mb-45">{{ \App\Models\Setting::get('about_heading', 'Innovating Healthcare with Advance Technologies') }}</h3>
                           <i>{{ \App\Models\Setting::get('about_italic_text', '—Empowering hospitals, diagnostic labs, and surgical suites with world-class technology and end-to-end engineering support.') }}</i>
                           <p class="mr-20 mb-45">{{ \App\Models\Setting::get('about_description', 'Innotech Medical Pvt Ltd is Established & Reputable distributor of top-quality medical equipment across Pakistan. From state-of-the-art Medical Devices and Surgical Disposable solutions to comprehensive turnkey hospital projects, our commitment goes beyond equipment distribution. We provide end-to-end technical support, regulatory compliance, and seamless integration, ensuring that healthcare providers across the nation have access to reliable, cutting-edge medical technologies.') }}</p>
                        </div>
                        <div class="tp-about__info-list mb-55">
                           <ul>
                              <li><i class="fa-solid fa-check"></i>{{ \App\Models\Setting::get('about_point_1', 'Critical Care & ICU Equipment') }}</li>
                              <li><i class="fa-solid fa-check"></i>{{ \App\Models\Setting::get('about_point_2', 'Advanced Diagnostic & Lab Instruments') }}</li>
                              <li><i class="fa-solid fa-check"></i>{{ \App\Models\Setting::get('about_point_3', 'Operating Room & General Medical Solutions') }}</li>
                              <li><i class="fa-solid fa-check"></i>{{ \App\Models\Setting::get('about_point_4', 'Turnkey Projects & Technical Support') }}</li>
                           </ul>
                        </div>
                        <div class="tp-about__btn">
                           <a class="tp-btn" href="{{ url('/about') }}">Our HIstory</a>
                           <a class="tp-btn-second ml-25" href="{{ url('/about') }}">About us</a>
                        </div>                       
                     </div>
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- about-area-end -->

         <!-- 4. COUNTER AREA -->
         @if(\App\Models\Setting::get('section_counter_enabled', '1') == '1')
         <section class="counter-area pt-40 pb-100">
            <div class="container">
               <div class="row">
                  <div class="col-xl-3 col-md-6">
                     <div class="counter__item blue-border mb-30 wow fadeInUp" data-wow-delay=".2s">
                        <div class="counter__icon mb-15">
                           <i></i>
                        </div>
                        <div class="counter__content">
                           <h4 class="counter__title"><span class="counter">{{ filter_var(\App\Models\Setting::get('counter_1_number', '1492'), FILTER_SANITIZE_NUMBER_INT) ?: 1492 }}</span></h4>
                           <p>{{ \App\Models\Setting::get('counter_1_title', 'Laboratories in 100+ states') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="counter__item pink-border mb-30 wow fadeInUp" data-wow-delay=".4s">
                        <div class="counter__icon pink-hard mb-15">
                           <i></i>
                        </div>
                        <div class="counter__content">
                           <h4 class="counter__title"><span class="counter">{{ filter_var(\App\Models\Setting::get('counter_2_number', '152'), FILTER_SANITIZE_NUMBER_INT) ?: 152 }}</span></h4>
                           <p>{{ \App\Models\Setting::get('counter_2_title', 'Laboratory specialists') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="counter__item sky-border mb-30 wow fadeInUp" data-wow-delay=".6s">
                        <div class="counter__icon sky-hard mb-15">
                           <i></i>
                        </div>
                        <div class="counter__content">
                           <h4 class="counter__title"><span class="counter">{{ filter_var(\App\Models\Setting::get('counter_3_number', '1022'), FILTER_SANITIZE_NUMBER_INT) ?: 1022 }}</span></h4>
                           <p>{{ \App\Models\Setting::get('counter_3_title', 'Material collection points') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="counter__item green-border mb-30 wow fadeInUp" data-wow-delay=".8s">
                        <div class="counter__icon green-hard mb-15">
                           <i></i>
                        </div>
                        <div class="counter__content">
                           <h4 class="counter__title"><span class="counter">{{ filter_var(\App\Models\Setting::get('counter_4_number', '24332'), FILTER_SANITIZE_NUMBER_INT) ?: 24332 }}</span></h4>
                           <p>{{ \App\Models\Setting::get('counter_4_title', 'Patients diagnosed in 2022') }}</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- counter-area-end -->

         <!-- 5. GALLERY AREA -->
         @if(\App\Models\Setting::get('section_gallery_enabled', '1') == '1')
         <section class="gallery-area grey-bg pt-120 pb-130" data-background="{{ asset('assets/img/shape/shape-bg-01.png') }}">
            <div class="container">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="tp-section text-center">
                        <span class="tp-section__sub-title left-line right-line mb-25">{{ \App\Models\Setting::get('gallery_subtitle', 'Work Gallery') }}</span>
                        <h3 class="tp-section__title mb-70">{{ \App\Models\Setting::get('gallery_title', 'INNOTECH Gallery') }}</h3>
                     </div>
                  </div>
               </div> 
            </div>
            <div class="container-fluid">
               <div class="tp-gallery ml-15 mr-15 wow fadeInUp" data-wow-delay=".4s">
                  <div class="swiper-container gall-active">
                     <div class="swiper-wrapper">
                        @if(isset($galleryItems) && $galleryItems->count() > 0)
                           @foreach($galleryItems as $g)
                              <div class="swiper-slide">
                                 <div class="tp-gallery__item p-relative mb-70">
                                    <div class="tp-gallery__img p-relative">
                                       <img src="{{ asset($g->image) }}" alt="{{ $g->title }}">
                                       <div class="tp-gallery__info">
                                          <a class="popup-image" href="{{ asset($g->image) }}"><i class="fa-solid fa-plus"></i></a>
                                       </div>
                                    </div>
                                    <div class="tp-gallery__content">
                                       <h4 class="tp-gallery__title"><a href="{{ $g->link ? url($g->link) : '#' }}">{{ $g->title }}</a></h4>
                                       <span><i class="fa-solid fa-tag"></i><a href="#">{{ $g->category ?: 'General' }}</a></span>
                                    </div>
                                 </div>
                              </div>
                           @endforeach
                        @else
                           <div class="swiper-slide">
                              <div class="tp-gallery__item p-relative mb-70">
                                 <div class="tp-gallery__img p-relative">
                                    <img src="{{ asset('assets/img/gallery/gal-thum-01.jpg') }}" alt="gallery-img">
                                    <div class="tp-gallery__info">
                                       <a class="popup-image" href="{{ asset('assets/img/gallery/gal-thum-01.jpg') }}"><i class="fa-solid fa-plus"></i></a>
                                    </div>
                                 </div>
                                 <div class="tp-gallery__content">
                                    <h4 class="tp-gallery__title"><a href="#">COVID ANALYSIS</a></h4>
                                    <span><i class="fa-solid fa-tag"></i><a href="#">Radiologist</a></span>
                                 </div>
                              </div>
                           </div>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
            <div class="container">
               <div class="row text-center">
                  <div class="col-lg-12">
                     <a class="tp-btn-second" href="{{ url(\App\Models\Setting::get('gallery_btn_link', '/gallery')) }}">{{ \App\Models\Setting::get('gallery_btn_text', 'Explore More') }}</a>
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- gallery-area-end -->

         <!-- 6. WHY CHOOSE US AREA -->
         @if(\App\Models\Setting::get('section_choose_enabled', '1') == '1')
         <section class="choose-area theme-bg-2 pt-120 pb-130">
            <div class="container">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="tp-section text-center">
                        <span class="tp-section__sub-title left-line right-line mb-25">{{ \App\Models\Setting::get('choose_subtitle', 'Our Specialists') }}</span>
                        <h3 class="tp-section__title title-white mb-85">{{ \App\Models\Setting::get('choose_title', 'Why Choose Us') }}</h3>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-xl-3 col-md-6">
                     <div class="tp-choose__item ml-15 mb-100 wow fadeInUp" data-wow-delay=".2s">
                        <div class="tp-choose__icon mb-40">
                           <i class="flaticon-microscope"></i>
                        </div>
                        <div class="tp-choose__content">
                           <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('choose_card1_title', 'Global Standards & Quality'))) !!}</h4>
                           <p>{{ \App\Models\Setting::get('choose_card1_desc', 'Delivering FDA, CE, and ISO certified medical equipment from global principal brands, ensuring maximum clinical accuracy and patient safety.') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="tp-choose__item ml-35 mb-100 wow fadeInUp" data-wow-delay=".4s">
                        <div class="tp-choose__icon pink-icon mb-40">
                           <i class="flaticon-thinking"></i>
                        </div>
                        <div class="tp-choose__content">
                           <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('choose_card2_title', 'Swift Turnkey Delivery'))) !!}</h4>
                           <p>{{ \App\Models\Setting::get('choose_card2_desc', 'Streamlined procurement and rapid installation, delivering complete healthcare projects with minimal turnaround time and total precision.') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="tp-choose__item ml-55 mb-100 wow fadeInUp" data-wow-delay=".6s">
                        <div class="tp-choose__icon green-icon mb-40">
                           <i class="flaticon-24-hours-1"></i>
                        </div>
                        <div class="tp-choose__content">
                           <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('choose_card3_title', '24/7 Emergency Support'))) !!}</h4>
                           <p>{{ \App\Models\Setting::get('choose_card3_desc', 'Round-the-clock technical coverage and rapid dispatch troubleshooting to eliminate critical equipment downtime in ICUs and OTs.') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="tp-choose__item ml-75 mb-100 wow fadeInUp" data-wow-delay=".8s">
                        <div class="tp-choose__icon sky-icon mb-40">
                           <i class="flaticon-team"></i>
                        </div>
                        <div class="tp-choose__content">
                           <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('choose_card4_title', 'Certified Biomedical Experts'))) !!}</h4>
                           <p>{{ \App\Models\Setting::get('choose_card4_desc', 'Backed by OEM-trained engineers executing precision calibration, complex repairs, and proactive maintenance to global standards.') }}</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row text-center">
                  <div class="col-lg-12">
                     <div class="tp-choose-option">
                        <span>{{ \App\Models\Setting::get('choose_bottom_text', 'Scientific Research Laboratories:') }} <a href="{{ url(\App\Models\Setting::get('choose_bottom_btn_link', '/contact')) }}">{{ \App\Models\Setting::get('choose_bottom_btn_text', 'Contact Us') }}<i class="fa-solid fa-arrow-right"></i></a></span>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- choose-area-end -->

         <!-- 7. APPOINTMENT AREA -->
         @if(\App\Models\Setting::get('section_appointment_enabled', '1') == '1')
         <section class="appoinment-area">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-xxl-6 col-xl-5 col-lg-12 col-md-12 p-0">
                     <div class="appoinment-thumb">
                        <img src="{{ asset(\App\Models\Setting::get('appointment_image', 'assets/img/banner/appoinment-01.jpg')) }}" alt="appoinment-img">
                     </div>
                  </div>
                  <div class="col-xxl-6 col-xl-7 col-lg-12 col-md-12 p-0">
                     <div class="visitor-info">
                        <h4 class="appoinment-title mb-25"><i class="fa-light fa-file-signature"></i>{{ \App\Models\Setting::get('appointment_title', 'GET IN TOUCH WITH US') }}</h4>
                        <div class="visitor-form">
                            <form id="homeContactForm" class="ajax-contact-form" action="{{ route('contact.store') }}" method="POST">
                               @csrf
                               <div class="row">
                                  <div class="col-lg-6">
                                     <div class="visitor-form__input">
                                        <input type="text" name="name" placeholder="Enter your full name" required>
                                     </div>
                                  </div>
                                  <div class="col-lg-6">
                                     <div class="visitor-form__input">
                                        <input type="email" name="email" placeholder="Enter your email" required>
                                     </div>
                                  </div>
                                  <div class="col-lg-6">
                                     <div class="visitor-form__input">
                                        <input type="text" name="phone" placeholder="Enter phone / mobile number">
                                     </div>
                                  </div>
                                  <div class="col-lg-6">
                                     <div class="visitor-form__input">
                                        <input type="text" name="subject" placeholder="Equipment inquiry / Organization">
                                     </div>
                                  </div>
                                  <div class="col-lg-12">
                                     <div class="visitor-form__input">
                                        <textarea placeholder="How can our biomedical team assist your facility?" name="message" required></textarea>
                                     </div>
                                  </div>

                                  {{-- Dynamic AJAX Success/Error Message Container --}}
                                  <div class="col-12">
                                     <div class="ajax-response mt-10 mb-15" style="display: none;"></div>
                                  </div>

                                  <div class="col-12">
                                     <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mt-15">
                                        <div class="visit-btn">
                                           <button type="submit" class="tp-btn-theme" style="background: linear-gradient(135deg, #0E63FF 0%, #0056e0 100%); color: #ffffff; border: none; border-radius: 8px; padding: 15px 34px; font-size: 14px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; box-shadow: 0 4px 14px rgba(14, 99, 255, 0.3); transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; white-space: nowrap;">
                                              <i class="fa-solid fa-paper-plane"></i>
                                              <span>SEND MESSAGE</span>
                                           </button>
                                        </div>
                                        <div class="visit-serial m-0">
                                           <span style="font-size: 14.5px; font-weight: 600; color: #475569;">
                                              <i class="fa-solid fa-phone-volume text-primary me-1"></i>
                                              {{ \App\Models\Setting::get('appointment_subtitle', '24/7 Emergency Service') }} : 
                                              <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('appointment_phone', '+923316699992')) }}" style="color: #0E63FF; font-weight: 700; text-decoration: none;">
                                                 {{ \App\Models\Setting::get('appointment_phone', '+92 331 6699992') }} <i class="fa-regular fa-arrow-right ms-1"></i>
                                              </a>
                                           </span>
                                        </div>
                                     </div>
                                  </div>
                               </div>
                            </form>
                         </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- appoinment-area-end -->

         <!-- 8. TEAM SPECIALISTS AREA -->
         @if(\App\Models\Setting::get('section_team_enabled', '1') == '1')
         <section class="team-area grey-bg pt-120 pb-80" data-background="{{ asset('assets/img/shape/shape-bg-01.png') }}">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-lg-8 col-md-8 col-12">
                     <div class="tp-section">
                        <span class="tp-section__sub-title left-line mb-25">{{ \App\Models\Setting::get('team_subtitle', 'Our Team') }}</span>
                        <h3 class="tp-section__title mb-75">{{ \App\Models\Setting::get('team_title', 'Meet Specialist') }}</h3>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-12">
                     <div class="tp-team-arrow d-flex align-items-center">
                        <div class="team-p"><i class="fa-regular fa-arrow-left"></i></div>
                        <div class="team-n"><i class="fa-regular fa-arrow-right"></i></div>
                     </div>
                  </div>
               </div>
               <div class="swiper-container team-active wow fadeInUp" data-wow-delay=".3s">
                  <div class="swiper-wrapper">
                     @if(isset($teamMembers) && $teamMembers->count() > 0)
                        @foreach($teamMembers as $tm)
                           <div class="swiper-slide">
                              <div class="tp-team mb-50">
                                 <div class="tp-team__thumb fix">
                                    <a href="{{ route('team.detail', $tm->slug) }}"><img src="{{ asset($tm->image ?: 'assets/img/team/team-thumb-01.jpg') }}" alt="{{ $tm->name }}"></a>
                                 </div>
                                 <div class="tp-team__content">
                                    <h4 class="tp-team__title mb-15"><a href="{{ route('team.detail', $tm->slug) }}">{{ $tm->name }}</a></h4>
                                    <span class="tp-team__position mb-30">{{ $tm->designation }}</span>
                                    <p>{{ Str::limit($tm->bio, 80) }}</p>
                                    <div class="team-social-box mt-auto">
                                        @if($tm->facebook_url)
                                           <a class="team-social-btn team-social-fb" href="{{ $tm->facebook_url }}" target="_blank" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                                        @endif
                                        @if($tm->instagram_url)
                                           <a class="team-social-btn team-social-insta" href="{{ $tm->instagram_url }}" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                                        @endif
                                        @if($tm->twitter_url)
                                           <a class="team-social-btn team-social-tweet" href="{{ $tm->twitter_url }}" target="_blank" title="Twitter"><i class="fa-brands fa-twitter"></i></a>
                                        @endif
                                        @if($tm->pinterest_url)
                                           <a class="team-social-btn team-social-in" href="{{ $tm->pinterest_url }}" target="_blank" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                                        @endif
                                        @if(!$tm->facebook_url && !$tm->instagram_url && !$tm->twitter_url && !$tm->pinterest_url)
                                           <a class="team-social-btn team-social-fb" href="https://facebook.com" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                                           <a class="team-social-btn team-social-insta" href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                                           <a class="team-social-btn team-social-tweet" href="https://twitter.com" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                                           <a class="team-social-btn team-social-in" href="https://linkedin.com" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                                        @endif
                                     </div>
                                 </div>
                              </div>
                           </div>
                        @endforeach
                     @else
                        <div class="swiper-slide">
                           <div class="tp-team mb-50">
                              <div class="tp-team__thumb fix">
                                 <a href="#"><img src="{{ asset('assets/img/team/team-thumb-01.jpg') }}" alt="team-thumb"></a>
                              </div>
                              <div class="tp-team__content">
                                 <h4 class="tp-team__title mb-15"><a href="#">Cameron Williamson</a></h4>
                                 <span class="tp-team__position mb-30">Genetic Specialist</span>
                                 <p>Providing insight-driven transformation to investment banks, wealth and asset mana, exchanges, Finance </p>
                              </div>
                           </div>
                        </div>
                     @endif
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- team-area-end -->

         <!-- 9. TESTIMONIAL AREA -->
         @if(\App\Models\Setting::get('section_testimonial_enabled', '1') == '1')
         <section class="testimonial-area testimonial-bg pt-125 pb-130" data-background="{{ asset('assets/img/shape/shape-bg-02.png') }}">
            <div class="container">
               <div class="row wow fadeInUp" data-wow-delay=".3s">
                  <div class="col-lg-12">
                     <div class="tp-section text-center">
                        <span class="tp-section__sub-title sub-title-white left-line-white right-line-white mb-25">{{ \App\Models\Setting::get('testimonial_subtitle', 'Testimonial') }}</span>
                        <h3 class="tp-section__title title-white mb-70">{{ \App\Models\Setting::get('testimonial_title', 'Customer Feedback') }}</h3>
                     </div>
                  </div>
               </div>
               <div class="swiper-container tp-test-active pt-40">
                  <div class="swiper-wrapper">
                     @if(isset($testimonials) && $testimonials->count() > 0)
                        @foreach($testimonials as $testi)
                           <div class="swiper-slide">
                              <div class="tp-testi p-relative">
                                 <div class="tp-testi__avata">
                                    <img src="{{ asset($testi->avatar ?: 'assets/img/icon/testi-ava-01.jpg') }}" alt="testimonial-avata">
                                 </div>
                                 <div class="tp-testi__content text-center">
                                    <p>“{{ $testi->content }}”</p>
                                    <div class="tp-testi__author-info">
                                       <h5 class="tp-testi__avata-title">{{ $testi->name }}</h5>
                                       <span class="tp-testi__ava-position">{{ $testi->designation }} {{ $testi->hospital ? 'of (' . $testi->hospital . ')' : '' }}</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        @endforeach
                     @else
                        <div class="swiper-slide">
                           <div class="tp-testi p-relative">
                              <div class="tp-testi__avata">
                                 <img src="{{ asset('assets/img/icon/testi-ava-01.jpg') }}" alt="testimonial-avata">
                              </div>
                              <div class="tp-testi__content text-center">
                                 <p>“Innotech Medical provided comprehensive turnkey clinical setups for our ICU expansion. Their equipment reliability and prompt 24/7 technical support have set a new benchmark in Pakistan’s healthcare sector.”</p>
                                 <div class="tp-testi__author-info">
                                    <h5 class="tp-testi__avata-title">Prof. Dr. Tariq Mahmood</h5>
                                    <span class="tp-testi__ava-position">Head of Critical Care (National Hospital)</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     @endif
                  </div>
               </div>
               <div class="row text-center">
                  <div class="col-lg-12">
                     <div class="tp-test-arrow d-flex align-items-center justify-content-center">
                        <div class="tp-test-prv"><i class="fa-regular fa-arrow-left"></i></div>
                        <div class="tp-test-nxt"><i class="fa-regular fa-arrow-right"></i></div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- testimonial-area-end -->

         <!-- 10. BRAND PARTNERS AREA -->
         @if(\App\Models\Setting::get('section_brand_enabled', '1') == '1')
         <div class="brand-area pt-100 pb-100" id="brand-section">
            <div class="container">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="tp-section text-center mb-50 wow fadeInUp" data-wow-delay=".2s">
                        <span class="tp-section__sub-title left-line right-line mb-15">{{ \App\Models\Setting::get('partners_subtitle', 'Global Collaborations') }}</span>
                        <h3 class="tp-section__title">{{ \App\Models\Setting::get('partners_title', 'Our Trust Partners') }}</h3>
                     </div>
                  </div>
               </div>
               <div class="swiper-container brand-active wow fadeInUp" data-wow-delay=".3s">
                  <div class="swiper-wrapper brand-items align-items-center">
                     @if(isset($partners) && $partners->count() > 0)
                        @foreach($partners as $partner)
                           <div class="swiper-slide">
                              <div class="partner-card">
                                 <a href="{{ $partner->url ?: '#' }}" target="_blank" title="{{ $partner->name }}">
                                    <img src="{{ asset($partner->logo) }}" alt="{{ $partner->name }}">
                                 </a>
                              </div>
                           </div>
                        @endforeach
                     @else
                        <div class="swiper-slide"><div class="partner-card"><a href="#"><img src="{{ asset('assets/img/brand/scitek.svg') }}" alt="SCITEK"></a></div></div>
                        <div class="swiper-slide"><div class="partner-card"><a href="#"><img src="{{ asset('assets/img/brand/micare.svg') }}" alt="MICARE"></a></div></div>
                        <div class="swiper-slide"><div class="partner-card"><a href="#"><img src="{{ asset('assets/img/brand/meditech.svg') }}" alt="MEDITECH"></a></div></div>
                        <div class="swiper-slide"><div class="partner-card"><a href="#"><img src="{{ asset('assets/img/brand/bng-medical.svg') }}" alt="BNG Medical"></a></div></div>
                        <div class="swiper-slide"><div class="partner-card"><a href="#"><img src="{{ asset('assets/img/brand/lynmou.svg') }}" alt="LYNMOU"></a></div></div>
                     @endif
                  </div>
               </div>
            </div>
         </div>
         @endif
         <!-- brand-area-end -->

         <!-- 11. CTA AREA -->
         @if(\App\Models\Setting::get('section_cta_enabled', '1') == '1')
         <section class="cta-area">
            <div class="container"> 
               <div class="row">
                  <div class="col-lg-12">
                     <div class="cta-bg theme-light-bg pt-65 pb-70" data-background="{{ asset('assets/img/shape/shape-bg-03.png') }}">
                        <div class="cta-content ml-90">
                           <h2 class="cta-title mb-35">{!! nl2br(e(\App\Models\Setting::get('cta_title', 'Looking for a best lebatory Service'))) !!}</h2>
                           <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('cta_phone', '+923316699992')) }}" class="tp-cta-btn">
                              <svg width="14" height="19" viewBox="0 0 14 19" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="white"/><circle cx="7" cy="2" r="2" fill="white"/><circle cx="12" cy="2" r="2" fill="white"/><circle cx="12" cy="7" r="2" fill="white"/><circle cx="12" cy="12" r="2" fill="white"/><circle cx="7" cy="7" r="2" fill="white"/><circle cx="7" cy="12" r="2" fill="white"/><circle cx="7" cy="17" r="2" fill="white"/><circle cx="2" cy="7" r="2" fill="white"/><circle cx="2" cy="12" r="2" fill="white"/></svg><span>{{ \App\Models\Setting::get('cta_btn_text', 'Call :') }}</span>{{ \App\Models\Setting::get('cta_phone', '+92 331 6699992') }}
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- cta-area-end -->

         <!-- 12. BLOG AREA -->
         @if(\App\Models\Setting::get('section_blog_enabled', '1') == '1')
         <section class="blog-area pt-125 pb-100" id="blog-section">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-md-8 col-12">
                     <div class="tp-section">
                        <span class="tp-section__sub-title left-line mb-25">{{ \App\Models\Setting::get('blog_subtitle', 'Waht’s New') }}</span>
                        <h3 class="tp-section__title mb-65">{{ \App\Models\Setting::get('blog_title', 'Blog & Article') }}</h3>
                     </div>
                  </div>
                  <div class="col-md-4 col-12">
                     <div class="tp-blog-arrow d-flex align-items-center">
                        <div class="tp-blog-p"><i class="fa-regular fa-arrow-left"></i></div>
                        <div class="tp-blog-n"><i class="fa-regular fa-arrow-right"></i></div>
                     </div>
                  </div>
               </div>
               <div class="swiper-container tp-blog-active wow fadeInUp" data-wow-delay=".3s">
                  <div class="swiper-wrapper">
                     @if(isset($blogs) && $blogs->count() > 0)
                        @foreach($blogs as $b)
                           <div class="swiper-slide">
                              <div class="tp-blog mb-30">
                                 <div class="tp-blog__thumb p-relative fix">
                                    <a href="{{ url('/blog/' . $b->slug) }}"><img src="{{ asset($b->image ?: 'assets/img/blog/blog-thumb-01.jpg') }}" alt="blog-item"></a>
                                    <div class="tp-blog__date text-center">
                                       <h4>{{ $b->published_at ? $b->published_at->format('d') : '26' }}<span>{{ $b->published_at ? $b->published_at->format('M') : 'Dec' }}</span></h4>
                                    </div>
                                 </div>
                                 <div class="tp-blog__content">
                                    <span class="tp-blog__category mb-30"><a href="{{ url('/blog/' . $b->slug) }}">{{ $b->category }}</a></span>
                                    <h5 class="tp-blog__title mb-20"><a href="{{ url('/blog/' . $b->slug) }}">{{ $b->title }}</a></h5>
                                    <p>{{ Str::limit($b->summary, 90) }}</p>
                                    <div class="tp-blog__btn">
                                       <a href="{{ url('/blog/' . $b->slug) }}">Read moRe</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        @endforeach
                     @else
                        <div class="swiper-slide">
                           <div class="tp-blog mb-30">
                              <div class="tp-blog__thumb p-relative fix">
                                 <a href="#"><img src="{{ asset('assets/img/blog/blog-thumb-01.jpg') }}" alt="blog-item"></a>
                                 <div class="tp-blog__date text-center">
                                    <h4>26<span>Dec</span></h4>
                                 </div>
                              </div>
                              <div class="tp-blog__content">
                                 <span class="tp-blog__category mb-30"><a href="#">Medicine</a></span>
                                 <h5 class="tp-blog__title mb-20"><a href="#">Heart Diseases Tests Ordered By Doctors</a></h5>
                                 <p>Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla ...</p>
                                 <div class="tp-blog__btn">
                                    <a href="#">Read moRe</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     @endif
                  </div>
               </div>
            </div>
         </section>
         @endif
         <!-- blog-area-end -->

      </main>
      <!-- main-area-end -->

@endsection

@push('styles')
<style>
/* =========================================================
   HOME LIVE SEARCH STYLES
   ========================================================= */
.search-input-wrapper {
   position: relative;
   width: 100%;
}
.search-clear-btn {
   position: absolute;
   right: 215px;
   top: 50%;
   transform: translateY(-50%);
   width: 32px;
   height: 32px;
   border-radius: 50%;
   border: none;
   background: #f1f5f9;
   color: #64748b;
   display: flex;
   align-items: center;
   justify-content: center;
   cursor: pointer;
   z-index: 10;
   transition: all 0.2s ease;
}
.search-clear-btn:hover {
   background: #e2e8f0;
   color: #0f172a;
}
@media (max-width: 767px) {
   .search-clear-btn {
      right: 15px;
      top: 25px;
      transform: translateY(0);
   }
}

.search-suggestions-dropdown {
   position: absolute;
   top: calc(100% + 12px);
   left: 0;
   right: 0;
   background: #ffffff;
   border-radius: 16px;
   box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.08);
   z-index: 1050;
   overflow: hidden;
   animation: searchDropdownFade 0.2s cubic-bezier(0.16, 1, 0.3, 1);
   text-align: left;
}
@keyframes searchDropdownFade {
   from { opacity: 0; transform: translateY(-8px); }
   to { opacity: 1; transform: translateY(0); }
}

.search-dropdown-header {
   padding: 12px 20px;
   background: #f8fafc;
   border-bottom: 1px solid #edf2f7;
}
.search-dropdown-header .dropdown-heading {
   font-size: 13px;
   font-weight: 700;
   text-transform: uppercase;
   letter-spacing: 0.5px;
   color: #475569;
}
.search-stats-badge {
   font-size: 12px;
   background: #e2e8f0;
   color: #334155;
   padding: 3px 10px;
   border-radius: 20px;
   font-weight: 600;
}

.search-dropdown-loading {
   padding: 26px 20px;
   text-align: center;
   color: #64748b;
   font-size: 14px;
}

.search-suggestions-list {
   max-height: 420px;
   overflow-y: auto;
}
.search-suggestions-list::-webkit-scrollbar {
   width: 6px;
}
.search-suggestions-list::-webkit-scrollbar-thumb {
   background: #cbd5e1;
   border-radius: 4px;
}

.search-suggestion-item {
   display: flex;
   align-items: center;
   padding: 12px 20px;
   text-decoration: none;
   border-bottom: 1px solid #f1f5f9;
   transition: background 0.15s ease, border-left-color 0.15s ease;
   color: inherit;
   border-left: 3px solid transparent;
}
.search-suggestion-item:last-child {
   border-bottom: none;
}
.search-suggestion-item:hover,
.search-suggestion-item.is-selected {
   background: #f0f7ff;
   border-left-color: #0E63FF;
   text-decoration: none;
}

.search-item-thumb {
   width: 52px;
   height: 52px;
   border-radius: 10px;
   object-fit: cover;
   flex-shrink: 0;
   margin-right: 15px;
   box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
   background: #f8fafc;
   border: 1px solid #e2e8f0;
}

.search-item-content {
   flex-grow: 1;
   min-width: 0;
}
.search-item-title {
   font-size: 15px;
   font-weight: 600;
   color: #0f172a;
   margin-bottom: 3px;
   line-height: 1.3;
}
.search-item-subtitle {
   font-size: 12.5px;
   color: #64748b;
   margin: 0;
   line-height: 1.4;
   white-space: nowrap;
   overflow: hidden;
   text-overflow: ellipsis;
}

.search-item-meta {
   display: flex;
   align-items: center;
   gap: 10px;
   margin-left: 12px;
   flex-shrink: 0;
}
.search-badge {
   font-size: 11px;
   font-weight: 600;
   padding: 4px 10px;
   border-radius: 20px;
   text-transform: uppercase;
   letter-spacing: 0.3px;
}
.search-item-arrow {
   color: #94a3b8;
   font-size: 12px;
   transition: transform 0.2s ease, color 0.2s ease;
}
.search-suggestion-item:hover .search-item-arrow,
.search-suggestion-item.is-selected .search-item-arrow {
   color: #0E63FF;
   transform: translateX(3px);
}

.search-highlight {
   background: #fef08a;
   color: #854d0e;
   font-weight: 700;
   padding: 0 2px;
   border-radius: 2px;
}

.search-dropdown-empty {
   padding: 35px 20px;
   text-align: center;
}
.search-dropdown-empty .empty-icon {
   font-size: 36px;
   color: #94a3b8;
   margin-bottom: 10px;
}
.search-dropdown-empty .empty-title {
   font-weight: 600;
   color: #334155;
   font-size: 16px;
}

.search-dropdown-footer {
   padding: 12px 20px;
   background: #f8fafc;
   border-top: 1px solid #edf2f7;
}
.search-kbd {
   display: inline-block;
   padding: 2px 6px;
   font-size: 11px;
   font-family: monospace;
   background: #ffffff;
   border: 1px solid #cbd5e1;
   border-radius: 4px;
   color: #475569;
   box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
}
.search-view-all-btn {
   font-size: 13px;
   font-weight: 600;
   color: #0E63FF;
   text-decoration: none;
   transition: color 0.2s ease;
}
.search-view-all-btn:hover {
   color: #0945b5;
   text-decoration: underline;
}
</style>
@endpush

@push('scripts')
<script>
(function() {
   const searchInput = document.getElementById('homeSearchInput');
   const searchDropdown = document.getElementById('homeSearchDropdown');
   const searchLoading = document.getElementById('searchDropdownLoading');
   const suggestionsList = document.getElementById('searchSuggestionsList');
   const searchEmpty = document.getElementById('searchDropdownEmpty');
   const clearBtn = document.getElementById('homeSearchClear');
   const statsBadge = document.getElementById('searchResultStats');
   const viewAllBtn = document.getElementById('searchViewAllLink');
   const searchForm = document.getElementById('homeSearchForm');

   if (!searchInput || !searchDropdown) return;

   let debounceTimer = null;
   let activeAbortController = null;
   let selectedIndex = -1;
   let currentSuggestions = [];

   // Safe Regex Escaper
   function escapeRegExp(string) {
      return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
   }

   // Safe HTML Escaper
   function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
   }

   // Highlight query within title
   function highlightQuery(text, query) {
      if (!query || !text) return escapeHtml(text);
      const escapedQuery = escapeRegExp(query);
      const regex = new RegExp('(' + escapedQuery + ')', 'gi');
      return escapeHtml(text).replace(regex, '<mark class="search-highlight">$1</mark>');
   }

   // Render suggestions list
   function renderSuggestions(data, query) {
      suggestionsList.innerHTML = '';
      selectedIndex = -1;
      currentSuggestions = data.suggestions || [];

      statsBadge.textContent = `${data.total} found`;
      viewAllBtn.href = `{{ route('search') }}?query=${encodeURIComponent(query)}`;
      viewAllBtn.innerHTML = `View all ${data.total} results <i class="fa-solid fa-arrow-right ms-1"></i>`;

      if (currentSuggestions.length === 0) {
         searchEmpty.style.display = 'block';
         suggestionsList.style.display = 'none';
      } else {
         searchEmpty.style.display = 'none';
         suggestionsList.style.display = 'block';

         currentSuggestions.forEach((item, idx) => {
            const row = document.createElement('a');
            row.href = item.url;
            row.className = 'search-suggestion-item';
            row.setAttribute('data-index', idx);

            const highlightedTitle = highlightQuery(item.title, query);
            const subtitleText = escapeHtml(item.subtitle);
            const snippetText = item.snippet ? ` &bull; ${escapeHtml(item.snippet)}` : '';

            row.innerHTML = `
               <img src="${item.image}" alt="${escapeHtml(item.title)}" class="search-item-thumb" onerror="this.src='{{ asset('assets/img/services/services-thumb-01.jpg') }}'">
               <div class="search-item-content">
                  <div class="search-item-title">${highlightedTitle}</div>
                  <p class="search-item-subtitle">${subtitleText}${snippetText}</p>
               </div>
               <div class="search-item-meta">
                  <span class="search-badge" style="background-color: ${item.badge_bg}; color: ${item.badge_color};">${escapeHtml(item.type_label)}</span>
                  <i class="fa-regular fa-chevron-right search-item-arrow"></i>
               </div>
            `;

            row.addEventListener('mouseenter', () => {
               updateSelected(idx);
            });

            suggestionsList.appendChild(row);
         });
      }

      searchLoading.style.display = 'none';
      searchDropdown.style.display = 'block';
   }

   // Update Keyboard Selection index
   function updateSelected(index) {
      const items = suggestionsList.querySelectorAll('.search-suggestion-item');
      items.forEach(el => el.classList.remove('is-selected'));
      selectedIndex = index;

      if (selectedIndex >= 0 && selectedIndex < items.length) {
         items[selectedIndex].classList.add('is-selected');
         items[selectedIndex].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
      }
   }

   // Perform live search AJAX
   function doSearch(query) {
      if (activeAbortController) {
         activeAbortController.abort();
      }
      activeAbortController = new AbortController();

      searchDropdown.style.display = 'block';
      searchLoading.style.display = 'block';
      suggestionsList.style.display = 'none';
      searchEmpty.style.display = 'none';

      fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(query)}`, {
         signal: activeAbortController.signal,
         headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
         }
      })
      .then(res => res.json())
      .then(data => {
         renderSuggestions(data, query);
      })
      .catch(err => {
         if (err.name !== 'AbortError') {
            searchLoading.style.display = 'none';
         }
      });
   }

   // Input Event Listener
   searchInput.addEventListener('input', function() {
      const val = this.value.trim();

      if (val.length > 0) {
         clearBtn.style.display = 'flex';
      } else {
         clearBtn.style.display = 'none';
      }

      clearTimeout(debounceTimer);

      if (val.length < 2) {
         searchDropdown.style.display = 'none';
         currentSuggestions = [];
         if (activeAbortController) activeAbortController.abort();
         return;
      }

      debounceTimer = setTimeout(() => {
         doSearch(val);
      }, 260);
   });

   // Input Focus Listener
   searchInput.addEventListener('focus', function() {
      const val = this.value.trim();
      if (val.length >= 2) {
         if (currentSuggestions.length > 0 || searchEmpty.style.display === 'block') {
            searchDropdown.style.display = 'block';
         } else {
            doSearch(val);
         }
      }
   });

   // Clear Button Listener
   clearBtn.addEventListener('click', function(e) {
      e.preventDefault();
      searchInput.value = '';
      clearBtn.style.display = 'none';
      searchDropdown.style.display = 'none';
      currentSuggestions = [];
      if (activeAbortController) activeAbortController.abort();
      searchInput.focus();
   });

   // Keyboard Navigation on Search Input
   searchInput.addEventListener('keydown', function(e) {
      const items = suggestionsList.querySelectorAll('.search-suggestion-item');

      if (searchDropdown.style.display === 'none' || items.length === 0) {
         return;
      }

      if (e.key === 'ArrowDown') {
         e.preventDefault();
         let nextIndex = selectedIndex + 1;
         if (nextIndex >= items.length) nextIndex = 0;
         updateSelected(nextIndex);
      } else if (e.key === 'ArrowUp') {
         e.preventDefault();
         let prevIndex = selectedIndex - 1;
         if (prevIndex < 0) prevIndex = items.length - 1;
         updateSelected(prevIndex);
      } else if (e.key === 'Enter') {
         if (selectedIndex >= 0 && selectedIndex < items.length) {
            e.preventDefault();
            const targetUrl = items[selectedIndex].getAttribute('href');
            if (targetUrl) {
               window.location.href = targetUrl;
            }
         }
         // Otherwise let standard submit happen to /search
      } else if (e.key === 'Escape') {
         searchDropdown.style.display = 'none';
      }
   });

   // Dismiss dropdown on outside click
   document.addEventListener('click', function(e) {
      if (!searchForm.contains(e.target)) {
         searchDropdown.style.display = 'none';
      }
   });

})();
</script>
@endpush

