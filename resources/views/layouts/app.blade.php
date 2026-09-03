<!doctype html>
<html class="no-js" lang="zxx">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <!-- SEO & Canonical Configuration -->
      <title>@yield('title', \App\Helpers\SeoHelper::title())</title>
      <meta name="description" content="@yield('meta_description', \App\Helpers\SeoHelper::description())">
      <meta name="keywords" content="@yield('meta_keywords', \App\Helpers\SeoHelper::keywords())">
      <meta name="robots" content="@yield('meta_robots', \App\Helpers\SeoHelper::robots())">
      <meta name="author" content="@yield('meta_author', \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))">
      <link rel="canonical" href="@yield('canonical_url', \App\Helpers\SeoHelper::canonicalUrl())">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <!-- Open Graph / Facebook / LinkedIn / WhatsApp -->
      <meta property="og:type" content="@yield('og_type', 'website')">
      <meta property="og:title" content="@yield('og_title', \App\Helpers\SeoHelper::title())">
      <meta property="og:description" content="@yield('og_description', \App\Helpers\SeoHelper::description())">
      <meta property="og:url" content="@yield('og_url', \App\Helpers\SeoHelper::canonicalUrl())">
      <meta property="og:site_name" content="{{ \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD') }}">
      <meta property="og:image" content="@yield('og_image', \App\Helpers\SeoHelper::ogImage())">
      <meta property="og:locale" content="en_US">

      <!-- Twitter Cards -->
      <meta name="twitter:card" content="{{ \App\Models\Setting::get('seo_twitter_card', 'summary_large_image') }}">
      <meta name="twitter:site" content="{{ \App\Models\Setting::get('seo_twitter_handle', '@InnotechMedical') }}">
      <meta name="twitter:title" content="@yield('twitter_title', \App\Helpers\SeoHelper::title())">
      <meta name="twitter:description" content="@yield('twitter_description', \App\Helpers\SeoHelper::description())">
      <meta name="twitter:image" content="@yield('twitter_image', \App\Helpers\SeoHelper::ogImage())">

      <!-- Google Search Console Verification -->
      @if($gVerify = \App\Models\Setting::get('seo_google_verification'))
      <meta name="google-site-verification" content="{{ $gVerify }}">
      @endif

      <!-- Structured Data (JSON-LD) -->
      @hasSection('schema_markup')
         @yield('schema_markup')
      @else
         <script type="application/ld+json">
         {!! \App\Helpers\SeoHelper::schemaJsonLd() !!}
         </script>
      @endif

      <!-- Google Analytics (GA4) -->
      @if($gaId = \App\Models\Setting::get('seo_google_analytics'))
      <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
      <script>
         window.dataLayer = window.dataLayer || [];
         function gtag(){dataLayer.push(arguments);}
         gtag('js', new Date());
         gtag('config', '{{ $gaId }}');
      </script>
      @endif

      <!-- Google Tag Manager -->
      @if($gtmId = \App\Models\Setting::get('seo_google_tag_manager'))
      <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','{{ $gtmId }}');</script>
      @endif

      <!-- Custom Admin Header Scripts -->
      {!! \App\Models\Setting::get('seo_header_scripts', '') !!}

      <!-- Place favicon.ico in the root directory -->
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset(\App\Models\Setting::get('favicon_path', 'assets/img/logo/favicon.png')) }}">

      <!-- CSS here -->
      <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-pro.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/spacing.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/meanmenu.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
      @stack('styles')
   </head>
   <body>
      <!--[if lte IE 9]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
      <![endif]-->

      <!-- Scroll-top -->
      <button class="scroll-top scroll-to-target" data-target="html">
         <i class="fas fa-angle-up"></i>
      </button>
      <!-- Scroll-top-end-->
	 
      <!-- preloader -->
      <div id="preloadertp">
         <img src="{{ asset('assets/img/preloader.png') }}" alt="">
      </div>
      <!-- preloader end  -->

      @if(request()->is('/'))
         <!-- HOME HEADER (Header Style 1) -->
         <header class="d-none d-xl-block">
            <div class="header__area tp-home-one" id="header-sticky">
               <div class="container-fluid">
                  <div class="row align-items-center">
                     <div class="col-xxl-2 col-lg-3">
                        <div class="logo">
                           <a href="{{ url('/') }}"><img src="{{ asset(\App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png')) }}" alt="logo"></a>
                        </div>
                     </div>
                     <div class="col-xxl-7 col-lg-6">
                        <div class="main-menu">
                           <nav id="mobile-menu">
                              <ul>
                                 @php
                                    $homeNavItems = \App\Models\NavMenu::getHomeMenus();
                                    $globalActiveCompanies = \App\Models\Company::where('is_active', true)->orderBy('order', 'asc')->get();
                                 @endphp
                                 @foreach($homeNavItems as $nav)
                                    @php
                                       $isProducts = ($nav->url == '/products' || \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($nav->title), 'product'));
                                       $hasCompanyDropdown = ($isProducts && $globalActiveCompanies->count() > 0);
                                       $hasDropdown = $hasCompanyDropdown || ($nav->children->count() > 0);
                                       $isActive = ($isProducts && (request()->is('products*') || request()->is('services*'))) || request()->is(trim($nav->url, '/')) || (request()->is('/') && $nav->url == '/');
                                    @endphp
                                    <li class="{{ $hasDropdown ? 'has-dropdown' : '' }}">
                                       <a class="{{ $isActive ? 'active' : '' }}" href="{{ $isProducts ? route('products') : $nav->computed_url }}" target="{{ $nav->target_blank ? '_blank' : '_self' }}">{{ $nav->title }}</a>
                                       @if($hasCompanyDropdown)
                                          <ul class="sub-menu">
                                             <li><a href="{{ route('products') }}"><strong>All Products</strong></a></li>
                                             @foreach($globalActiveCompanies as $comp)
                                                <li><a href="{{ route('products', ['company' => $comp->slug]) }}">{{ $comp->name }} {{ $comp->country ? '('.$comp->country.')' : '' }}</a></li>
                                             @endforeach
                                          </ul>
                                       @elseif($nav->children->count() > 0)
                                          <ul class="sub-menu">
                                             @foreach($nav->children as $sub)
                                                <li><a href="{{ $sub->computed_url }}" target="{{ $sub->target_blank ? '_blank' : '_self' }}">{{ $sub->title }}</a></li>
                                             @endforeach
                                          </ul>
                                       @endif
                                    </li>
                                 @endforeach
                              </ul>
                           </nav>
                        </div>
                     </div>
                     <div class="col-xxl-3 col-lg-3 d-flex align-items-center justify-content-end">
                        <div class="tp-bt-btn-banner">
                           <a class="tp-bt-btn" href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992')) }}">
                              <svg width="14" height="19" viewBox="0 0 14 19" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="#0E63FF"/><circle cx="7" cy="2" r="2" fill="#0E63FF"/><circle cx="12" cy="2" r="2" fill="#0E63FF"/><circle cx="12" cy="7" r="2" fill="#0E63FF"/><circle cx="12" cy="12" r="2" fill="#0E63FF"/><circle cx="7" cy="7" r="2" fill="#0E63FF"/><circle cx="7" cy="12" r="2" fill="#0E63FF"/><circle cx="7" cy="17" r="2" fill="#0E63FF"/><circle cx="2" cy="7" r="2" fill="#0E63FF"/><circle cx="2" cy="12" r="2" fill="#0E63FF"/></svg><span>Help Desk :</span>{{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }}
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </header>

         <!-- tp-mobile-header-area start -->
         <div id="header-mob-sticky" class="tp-mobile-header-area tp-home-lg-banner pt-15 pb-15 d-xl-none">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-4">
                     <div class="tp-mob-logo">
                        <a href="{{ url('/') }}"><img src="{{ asset(\App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png')) }}" alt="logo"></a>
                     </div>
                  </div>
                  <div class="col-8">
                     <div class="tp-mobile-bar d-flex align-items-center justify-content-end">
                        <div class="tp-bt-btn-banner d-none d-md-block d-xl-none mr-30">
                           <a class="tp-bt-btn" href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992')) }}">
                              <svg width="14" height="19" viewBox="0 0 14 19" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="#0E63FF"/><circle cx="7" cy="2" r="2" fill="#0E63FF"/><circle cx="12" cy="2" r="2" fill="#0E63FF"/><circle cx="12" cy="7" r="2" fill="#0E63FF"/><circle cx="12" cy="12" r="2" fill="#0E63FF"/><circle cx="7" cy="7" r="2" fill="#0E63FF"/><circle cx="7" cy="12" r="2" fill="#0E63FF"/><circle cx="7" cy="17" r="2" fill="#0E63FF"/><circle cx="2" cy="7" r="2" fill="#0E63FF"/><circle cx="2" cy="12" r="2" fill="#0E63FF"/></svg><span>Help Desk :</span>{{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }}
                           </a>
                        </div>
                        <button class="tp-menu-toggle"><i class="far fa-bars"></i></button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      @else
         <!-- INNER SCREENS HEADER (Header Custom Style) -->
         <header class="d-none d-xl-block">
            <div class="header-custom" id="header-sticky">
               <div class="header-logo-box">
                  <a href="{{ url('/') }}"><img src="{{ asset(\App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png')) }}" alt="logo"></a>
               </div>
               <div class="header-menu-box">
                  <div class="header-menu-top">
                     <div class="row align-items-center">
                        <div class="col-lg-4">
                           <div class="header-top-mob">
                              <svg width="14" height="19" viewBox="0 0 14 19" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="#0E63FF"/><circle cx="7" cy="2" r="2" fill="#0E63FF"/><circle cx="12" cy="2" r="2" fill="#0E63FF"/><circle cx="12" cy="7" r="2" fill="#0E63FF"/><circle cx="12" cy="12" r="2" fill="#0E63FF"/><circle cx="7" cy="7" r="2" fill="#0E63FF"/><circle cx="7" cy="12" r="2" fill="#0E63FF"/><circle cx="7" cy="17" r="2" fill="#0E63FF"/><circle cx="2" cy="7" r="2" fill="#0E63FF"/><circle cx="2" cy="12" r="2" fill="#0E63FF"/></svg>
                              <span>Help Desk :</span>
                              <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992')) }}"> {{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }} </a>
                           </div>
                        </div>
                        <div class="col-lg-8">
                           <div class="header-time">
                              <span><i class="fa-light fa-clock-ten"></i> {{ \App\Models\Setting::get('working_hours', 'Monday - Friday 10:00 am - 06:00 Pm') }}</span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="header-menu-bottom">
                     <div class="row align-items-center">
                        <div class="col-xxl-9 col-xl-9 col-lg-8">
                           <div class="main-menu main-menu-second">
                              <nav id="mobile-menu">
                                 <ul>
                                    @php
                                       $innerNavItems = \App\Models\NavMenu::getInnerMenus();
                                       $globalActiveCompaniesInner = \App\Models\Company::where('is_active', true)->orderBy('order', 'asc')->get();
                                    @endphp
                                    @foreach($innerNavItems as $nav)
                                       @php
                                          $isProducts = ($nav->url == '/products' || \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($nav->title), 'product'));
                                          $hasCompanyDropdown = ($isProducts && $globalActiveCompaniesInner->count() > 0);
                                          $hasDropdown = $hasCompanyDropdown || ($nav->children->count() > 0);
                                          $isActive = ($isProducts && (request()->is('products*') || request()->is('services*'))) || request()->is(trim($nav->url, '/')) || (request()->is('/') && $nav->url == '/');
                                       @endphp
                                       <li class="{{ $hasDropdown ? 'has-dropdown' : '' }}">
                                          <a class="{{ $isActive ? 'active' : '' }}" href="{{ $isProducts ? route('products') : $nav->computed_url }}" target="{{ $nav->target_blank ? '_blank' : '_self' }}">{{ $nav->title }}</a>
                                          @if($hasCompanyDropdown)
                                             <ul class="sub-menu">
                                                <li><a href="{{ route('products') }}"><strong>All Products</strong></a></li>
                                                @foreach($globalActiveCompaniesInner as $comp)
                                                   <li><a href="{{ route('products', ['company' => $comp->slug]) }}">{{ $comp->name }} {{ $comp->country ? '('.$comp->country.')' : '' }}</a></li>
                                                @endforeach
                                             </ul>
                                          @elseif($nav->children->count() > 0)
                                             <ul class="sub-menu">
                                                @foreach($nav->children as $sub)
                                                   <li><a href="{{ $sub->computed_url }}" target="{{ $sub->target_blank ? '_blank' : '_self' }}">{{ $sub->title }}</a></li>
                                                @endforeach
                                             </ul>
                                          @endif
                                       </li>
                                    @endforeach
                                 </ul>
                              </nav>
                           </div>
                        </div>
                        <div class="col-xxl-3 col-xl-3 col-lg-4">
                           <div class="header-cart-order d-flex align-items-center justify-content-end">
                              <div class="header-cart-list d-flex align-items-center justify-content-end">
                                 <button class="tp-menu-toggle mr-40"><i class="fa-solid fa-list"></i></button>
                              </div>
                              <a class="header-bottom-btn" href="{{ url('/contact') }}">Book Appointment</a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </header>

         <!-- tp-mobile-header-area start -->
         <div id="header-mob-sticky" class="tp-mobile-header-area pt-15 pb-15 d-xl-none">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-md-4 col-10">
                     <div class="tp-mob-logo">
                        <a href="{{ url('/') }}"><img src="{{ asset(\App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png')) }}" alt="logo"></a>
                     </div>
                  </div>
                  <div class="col-md-8 col-2">
                     <div class="tp-mobile-bar d-flex align-items-center justify-content-end">
                        <div class="tp-bt-btn-banner d-none d-md-block d-xl-none mr-30">
                           <a class="tp-bt-btn" href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992')) }}">
                              <svg width="14" height="19" viewBox="0 0 14 19" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="2" fill="#0E63FF"/><circle cx="7" cy="2" r="2" fill="#0E63FF"/><circle cx="12" cy="2" r="2" fill="#0E63FF"/><circle cx="12" cy="7" r="2" fill="#0E63FF"/><circle cx="12" cy="12" r="2" fill="#0E63FF"/><circle cx="7" cy="7" r="2" fill="#0E63FF"/><circle cx="7" cy="12" r="2" fill="#0E63FF"/><circle cx="7" cy="17" r="2" fill="#0E63FF"/><circle cx="2" cy="7" r="2" fill="#0E63FF"/><circle cx="2" cy="12" r="2" fill="#0E63FF"/></svg><span>Help Desk :</span>{{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }}
                           </a>
                        </div>
                        <button class="tp-menu-toggle"><i class="far fa-bars"></i></button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      @endif

      <!-- sidebar-info -->
      <div class="tpsideinfo tp-side-info-area">
         <button class="tpsideinfo__close"><i class="fal fa-times"></i></button>
         <div class="tpsideinfo__logo mb-40">
            <a href="{{ url('/') }}"><img src="{{ asset(\App\Models\Setting::get('sidebar_logo', \App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png'))) }}" alt="logo"></a>
         </div>

         <div class="mobile-menu"></div>

         <div class="tpsideinfo__content mb-60">
            <p class="d-none d-xl-block">{{ \App\Models\Setting::get('sidebar_mission', 'Our mission is to ensure the generation of accurate and precise findings.') }}</p>
            <span>{{ \App\Models\Setting::get('sidebar_contact_title', 'Contact Us') }}</span>
            <a href="#"><i class="fa-solid fa-star"></i>{{ \App\Models\Setting::get('office_address', '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.') }}</a>
            <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992')) }}"><i class="fa-solid fa-star"></i>{{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }}</a>
            <a href="mailto:{{ \App\Models\Setting::get('support_email', 'info@innotecmedical.org') }}"><i class="fa-solid fa-star"></i>{{ \App\Models\Setting::get('support_email', 'info@innotecmedical.org') }}</a>
         </div>

         @if(\App\Models\Setting::get('sidebar_newsletter_enabled', '1') == '1')
         <div class="tpsideinfo__content-inputarea mb-60 d-none d-xl-block">
            <span>{{ \App\Models\Setting::get('sidebar_newsletter_title', 'Get Update') }}</span>
            <div class="tpsideinfo__content-inputarea-input">
               <form id="sidebarNewsletterForm" action="{{ route('contact.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="name" value="Newsletter Subscriber">
                  <input type="hidden" name="message" value="Subscribed to update from sidebar">
                  <input type="email" name="email" id="sidebarNewsletterEmail" placeholder="Enter Mail" required autocomplete="off">
                  <button class="tpsideinfo__content-inputarea-input-btn" type="submit" id="sidebarNewsletterBtn"><i class="fa-solid fa-paper-plane"></i></button>
               </form>
            </div>
            <!-- Sidebar Success Notice (Disappears after 5 seconds) -->
            <div id="sidebarNewsletterSuccess" class="d-none mt-2" style="background: #F0FDF4; border: 1.5px solid #86EFAC; border-radius: 8px; padding: 10px 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: opacity 0.5s ease;">
               <div style="font-weight: 700; color: #166534; font-size: 12.5px; display: flex; align-items: center; gap: 6px;">
                  <i class="fa-solid fa-circle-check text-success"></i> Thank you!
               </div>
               <div style="color: #15803D; font-size: 12px; font-weight: 500; margin-top: 2px;">
                  You have successfully subscribed to Innotech updates.
               </div>
            </div>
            <!-- Sidebar Error / Suspension Notice -->
            <div id="sidebarNewsletterError" class="d-none mt-2"></div>
         </div>
         @endif

         @if(\App\Models\Setting::get('sidebar_gallery_enabled', '1') == '1')
         <div class="tpsideinfo__gallery mb-35 d-none d-xl-block">
            <span>{{ \App\Models\Setting::get('sidebar_gallery_title', 'Check Instagram Post') }}</span>
            <div class="tpsideinfo__gallery-item">
               <a href="{{ asset(\App\Models\Setting::get('sidebar_gallery_img_1', 'assets/img/blog/blog-in-01.jpg')) }}" class="popup-image"><img src="{{ asset(\App\Models\Setting::get('sidebar_gallery_img_1', 'assets/img/blog/blog-in-01.jpg')) }}" alt=""></a>
               <a href="{{ asset(\App\Models\Setting::get('sidebar_gallery_img_2', 'assets/img/blog/blog-in-02.jpg')) }}" class="popup-image"><img src="{{ asset(\App\Models\Setting::get('sidebar_gallery_img_2', 'assets/img/blog/blog-in-02.jpg')) }}" alt=""></a>
               <a href="{{ asset(\App\Models\Setting::get('sidebar_gallery_img_3', 'assets/img/blog/blog-in-03.jpg')) }}" class="popup-image"><img src="{{ asset(\App\Models\Setting::get('sidebar_gallery_img_3', 'assets/img/blog/blog-in-03.jpg')) }}" alt=""></a>
            </div>
         </div>
         @endif

         <div class="tpsideinfo__socialicon">
            <a href="{{ \App\Models\Setting::get('youtube_url', '#') }}"><i class="fa-brands fa-youtube"></i></a>
            <a href="{{ \App\Models\Setting::get('twitter_url', '#') }}"><i class="fa-brands fa-twitter"></i></a>
            <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="{{ \App\Models\Setting::get('instagram_url', \App\Models\Setting::get('linkedin_url', '#')) }}"><i class="fa-brands fa-instagram"></i></a>
         </div>
      </div>
      <!-- sidebar-info-end -->
      
      <div class="body-overlay"></div>

      <!-- Main Content -->
      @yield('content')

      <!-- footer-area -->
      @if(\App\Models\Setting::get('section_footer_enabled', '1') == '1')
      <footer>
         <div class="footer-area theme-bg-3 pt-100 pb-50">
            <div class="container">
               <div class="row">
                  <div class="col-xl-3 col-lg-4 col-md-6">
                     <div class="footer-widget footer-col-1 mb-50 wow fadeInUp" data-wow-delay=".2s">
                        <h4 class="footer-widget__title mb-30">
                           <a href="{{ url('/') }}"><img src="{{ asset(\App\Models\Setting::get('footer_logo', \App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png'))) }}" alt="logo"></a>
                        </h4>
                        <p>{{ \App\Models\Setting::get('footer_about', 'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical.') }}</p>
                        <div class="footer-widget__social">
                           <a class="tp-f-youtube" href="{{ \App\Models\Setting::get('youtube_url', '#') }}"><i class="fa-brands fa-youtube"></i></a>
                           <a class="tp-f-twitter" href="{{ \App\Models\Setting::get('twitter_url', '#') }}"><i class="fa-brands fa-twitter"></i></a>
                           <a class="tp-f-fb" href="{{ \App\Models\Setting::get('facebook_url', '#') }}"><i class="fa-brands fa-facebook-f"></i></a>
                           <a class="tp-f-insta" href="{{ \App\Models\Setting::get('instagram_url', \App\Models\Setting::get('linkedin_url', '#')) }}"><i class="fa-brands fa-instagram"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                     <div class="footer-widget footer-col-2 mb-50 wow fadeInUp" data-wow-delay=".4s">
                        <h4 class="footer-widget__title mb-20">Useful links</h4>
                        <div class="footer-widget__links">
                           <ul>
                              <li><a href="{{ url('/contact') }}">Contact us</a></li>
                              <li><a href="{{ url('/about') }}">About us</a></li>
                              <li><a href="{{ url('/specialists') }}">Our Specialists</a></li>
                              @php
                                 $usefulPages = \App\Models\Page::footerUseful()->get();
                              @endphp
                              @foreach($usefulPages as $uPage)
                                 <li><a href="{{ route('page.show', $uPage->slug) }}">{{ $uPage->title }}</a></li>
                              @endforeach
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-6">
                     <div class="footer-widget footer-col-3 mb-50 wow fadeInUp" data-wow-delay=".6s">
                        <h4 class="footer-widget__title mb-20">Contact info</h4>
                        <div class="footer-widget__info">
                           <ul>
                              <li><a href="#">{{ \App\Models\Setting::get('office_address', '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.') }}</a></li>
                              <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992')) }}">{{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }}</a></li>
                              <li><a href="mailto:{{ \App\Models\Setting::get('support_email', 'info@innotecmedical.org') }}">{{ \App\Models\Setting::get('support_email', 'info@innotecmedical.org') }}</a></li>
                              <li>{{ \App\Models\Setting::get('working_hours', 'Office Hours: 10AM - 6PM') }}</li>
                              <li>Sat/Sund - Wekend Day</li>
                           </ul>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-lg-6 col-md-6">
                     <div class="footer-widget footer-col-4 mb-50 wow fadeInUp" data-wow-delay=".8s">
                        <h4 class="footer-widget__title mb-20">Subscribe Newslatter</h4>
                        <p>Stay updated with the latest biomedical innovations, equipment releases, and healthcare technology insights across Pakistan.</p>
                        <div class="footer-widget__newsletter p-relative">
                           <form action="{{ route('contact.store') }}" method="POST" id="footerNewsletterForm">
                              @csrf
                              <input type="hidden" name="name" value="Newsletter Subscriber">
                              <input type="hidden" name="message" value="Subscribed to newsletter from footer">
                              <input type="email" name="email" id="footerNewsletterEmail" placeholder="Enter Mail" required>
                              <button type="submit" id="footerNewsletterBtn" class="footer-widget__fw-news-btn"><i class="fa-solid fa-paper-plane"></i></button>
                           </form>
                           <div id="footerNewsletterSuccess" class="d-none mt-2" style="color: #22c55e; font-size: 13.5px; font-weight: 600; line-height: 1.4; transition: opacity 0.4s ease;">
                              <i class="fa-solid fa-circle-check me-1"></i> Thank you! You have successfully subscribed to our newsletter.
                           </div>
                           <div id="footerNewsletterError" class="d-none mt-2 text-danger small" style="font-size: 13px; font-weight: 600;"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="footer-area-bottom theme-bg-4">
            <div class="container">
               <div class="row">
                  <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                     <div class="footer-widget__copyright">
                        <span> {{ \App\Models\Setting::get('copyright_text', '© Copyright ©2026 - 2027 INNOTECH MEDICAL Pvt Ltd. All Rights Reserved') }}</span>
                     </div>
                  </div> 
                  <div class="col-xl-6 col-lg-6 col-md-12 col-12">
                     <div class="footer-widget__copyright-info info-direction">
                        <ul class="d-flex align-items-center">
                           @php
                              $bottomPages = \App\Models\Page::footerBottom()->get();
                           @endphp
                           @if($bottomPages->count() > 0)
                              @foreach($bottomPages as $bPage)
                                 <li><a href="{{ route('page.show', $bPage->slug) }}">{{ $bPage->title }}</a></li>
                              @endforeach
                           @else
                              <li><a href="{{ route('page.show', 'terms-and-conditions') }}">Terms and conditions</a></li>
                              <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy policy</a></li>
                           @endif
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </footer>
      @endif
      <!-- footer-area-end -->

      {{-- Floating WhatsApp & Live Chat Widget --}}
      @include('partials.live_chat_widget')

      <!-- JS here -->
      <script src="{{ asset('assets/js/jquery.js') }}"></script>
      <script src="{{ asset('assets/js/waypoints.js') }}"></script>
      <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
      <script src="{{ asset('assets/js/swiper-bundle.js') }}"></script>
      <script src="{{ asset('assets/js/slick.js') }}"></script>
      <script src="{{ asset('assets/js/magnific-popup.js') }}"></script>
      <script src="{{ asset('assets/js/counterup.js') }}"></script>
      <script src="{{ asset('assets/js/wow.js') }}"></script>
      <script src="{{ asset('assets/js/nice-select.js') }}"></script>
      <script src="{{ asset('assets/js/isotope-pkgd.js') }}"></script>
      <script src="{{ asset('assets/js/imagesloaded-pkgd.js') }}"></script>
      <script src="{{ asset('assets/js/ajax-form.js') }}"></script>
      <script src="{{ asset('assets/js/aos.js') }}"></script>
      <script src="{{ asset('assets/js/meanmenu.js') }}"></script>
      <script src="{{ asset('assets/js/main.js') }}"></script>
      <script>
      $(document).ready(function() {
         $('#footerNewsletterForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const emailInput = $('#footerNewsletterEmail');
            const submitBtn = $('#footerNewsletterBtn');
            const successMsg = $('#footerNewsletterSuccess');
            const errorMsg = $('#footerNewsletterError');

            const originalBtnHtml = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm text-white" style="width: 14px; height: 14px;"></span>');
            errorMsg.addClass('d-none').text('');
            successMsg.addClass('d-none');

            $.ajax({
               url: form.attr('action'),
               method: 'POST',
               data: form.serialize(),
               headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json'
               },
               success: function(res) {
                  submitBtn.prop('disabled', false).html(originalBtnHtml);
                  emailInput.val('');
                  
                  // Show success message smoothly below field
                  successMsg.removeClass('d-none').css({ opacity: 1, display: 'block' });

                  // Automatically disappear after 5 seconds (5000ms)
                  setTimeout(function() {
                     successMsg.css({ opacity: 0, transition: 'opacity 0.5s ease' });
                     setTimeout(function() {
                        successMsg.addClass('d-none').css({ opacity: 1 });
                     }, 500);
                  }, 5000);
               },
               error: function(xhr) {
                  submitBtn.prop('disabled', false).html(originalBtnHtml);

                  // 1. Check if 2-hour suspension limit triggered (HTTP 429)
                  if (xhr.status === 429 || (xhr.responseJSON && xhr.responseJSON.suspended)) {
                     const suspendedMsg = (xhr.responseJSON && xhr.responseJSON.message) 
                        ? xhr.responseJSON.message 
                        : 'Maximum attempts exceeded. This email address has been suspended for 2 hours.';

                     errorMsg.removeClass('d-none').html(`
                        <div style="background: #FEF2F2; border: 1.5px solid #F87171; border-radius: 8px; padding: 10px 14px; margin-top: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                           <div style="font-weight: 700; color: #B91C1C; font-size: 12.5px; margin-bottom: 3px; display: flex; align-items: center; gap: 6px;">
                              <i class="fa-solid fa-clock-rotate-left" style="color: #DC2626;"></i> Temporary 2-Hour Suspension
                           </div>
                           <div style="color: #7F1D1D; font-size: 12px; line-height: 1.45; font-weight: 500;">
                              ${suspendedMsg}
                           </div>
                        </div>
                     `);

                     // Keep suspension alert visible for 10 seconds so user can comfortably read it
                     setTimeout(function() {
                        errorMsg.fadeOut(400, function() {
                           errorMsg.addClass('d-none').show().html('');
                        });
                     }, 10000);
                     return;
                  }

                  // 2. Standard validation error
                  let err = 'Please enter a valid email address.';
                  if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.email) {
                     err = xhr.responseJSON.errors.email[0];
                  } else if (xhr.responseJSON && xhr.responseJSON.message) {
                     err = xhr.responseJSON.message;
                  }
                  errorMsg.removeClass('d-none').html(`<span style="color: #B91C1C; font-size: 12.5px; font-weight: 600;"><i class="fa-solid fa-circle-exclamation me-1 text-danger"></i> ${err}</span>`);
                  setTimeout(function() {
                     errorMsg.fadeOut(400, function() {
                        errorMsg.addClass('d-none').show().html('');
                     });
                  }, 5000);
               }
            });
         });

         // Sidebar Offcanvas Newsletter Form Submission
         $('#sidebarNewsletterForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const emailInput = $('#sidebarNewsletterEmail');
            const submitBtn = $('#sidebarNewsletterBtn');
            const successMsg = $('#sidebarNewsletterSuccess');
            const errorMsg = $('#sidebarNewsletterError');

            const originalBtnHtml = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm text-white" style="width: 14px; height: 14px;"></span>');
            errorMsg.addClass('d-none').text('');
            successMsg.addClass('d-none');

            $.ajax({
               url: form.attr('action'),
               method: 'POST',
               data: form.serialize(),
               headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'Accept': 'application/json'
               },
               success: function(res) {
                  submitBtn.prop('disabled', false).html(originalBtnHtml);
                  emailInput.val('');
                  
                  // Show success message smoothly below field
                  successMsg.removeClass('d-none').css({ opacity: 1, display: 'block' });

                  // Automatically disappear after 5 seconds (5000ms)
                  setTimeout(function() {
                     successMsg.css({ opacity: 0, transition: 'opacity 0.5s ease' });
                     setTimeout(function() {
                        successMsg.addClass('d-none').css({ opacity: 1 });
                     }, 500);
                  }, 5000);
               },
               error: function(xhr) {
                  submitBtn.prop('disabled', false).html(originalBtnHtml);

                  // 1. Check if 2-hour suspension limit triggered (HTTP 429)
                  if (xhr.status === 429 || (xhr.responseJSON && xhr.responseJSON.suspended)) {
                     const suspendedMsg = (xhr.responseJSON && xhr.responseJSON.message) 
                        ? xhr.responseJSON.message 
                        : 'Maximum attempts exceeded. This email address has been suspended for 2 hours.';

                     errorMsg.removeClass('d-none').html(`
                        <div style="background: #FEF2F2; border: 1.5px solid #F87171; border-radius: 8px; padding: 10px 14px; margin-top: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                           <div style="font-weight: 700; color: #B91C1C; font-size: 12.5px; margin-bottom: 3px; display: flex; align-items: center; gap: 6px;">
                              <i class="fa-solid fa-clock-rotate-left" style="color: #DC2626;"></i> Temporary 2-Hour Suspension
                           </div>
                           <div style="color: #7F1D1D; font-size: 12px; line-height: 1.45; font-weight: 500;">
                              ${suspendedMsg}
                           </div>
                        </div>
                     `);

                     setTimeout(function() {
                        errorMsg.fadeOut(400, function() {
                           errorMsg.addClass('d-none').show().html('');
                        });
                     }, 10000);
                     return;
                  }

                  // 2. Standard validation error
                  let err = 'Please enter a valid email address.';
                  if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.email) {
                     err = xhr.responseJSON.errors.email[0];
                  } else if (xhr.responseJSON && xhr.responseJSON.message) {
                     err = xhr.responseJSON.message;
                  }
                  errorMsg.removeClass('d-none').html(`<span style="color: #B91C1C; font-size: 12.5px; font-weight: 600;"><i class="fa-solid fa-circle-exclamation me-1 text-danger"></i> ${err}</span>`);
                  setTimeout(function() {
                     errorMsg.fadeOut(400, function() {
                        errorMsg.addClass('d-none').show().html('');
                     });
                  }, 5000);
               }
            });
         });
      });
      </script>
      <script src="{{ asset('assets/js/fix-webm-duration.js') }}"></script>
      
      <!-- Visitor Analytics & Dwell Time Tracker -->
      <script>
      (function() {
         // Skip tracking on admin panels
         if (window.location.pathname.indexOf('/admin') === 0) return;

         let logId = null;
         const startTime = Date.now();
         let durationReported = false;

         // 1. Ping initial page view
         const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
         
         fetch("{{ route('analytics.ping') }}", {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': csrfToken || '',
               'Accept': 'application/json'
            },
            body: JSON.stringify({
               page: window.location.pathname,
               title: document.title
            })
         })
         .then(res => res.json())
         .then(data => {
            if (data && data.log_id) {
               logId = data.log_id;
            }
         })
         .catch(() => {});

         // 2. Report dwell time on exit
         function reportLeave() {
            if (durationReported || !logId) return;
            durationReported = true;
            const durationSec = Math.max(1, Math.round((Date.now() - startTime) / 1000));
            
            const payload = JSON.stringify({
               log_id: logId,
               duration: durationSec
            });

            if (navigator.sendBeacon) {
               const blob = new Blob([payload], { type: 'application/json' });
               navigator.sendBeacon("{{ route('analytics.leave') }}", blob);
            } else {
               fetch("{{ route('analytics.leave') }}", {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken || '' },
                  body: payload,
                  keepalive: true
               }).catch(() => {});
            }
         }

         window.addEventListener('pagehide', reportLeave);
         window.addEventListener('beforeunload', reportLeave);
         document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
               reportLeave();
            }
         });
      })();
      </script>

      @stack('scripts')

      <!-- Custom Admin Footer / Tracking Scripts -->
      {!! \App\Models\Setting::get('seo_footer_scripts', '') !!}
   </body>
</html>
