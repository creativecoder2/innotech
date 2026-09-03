@extends('layouts.app')

@section('title', \App\Models\Setting::get('services_banner_title', 'Products & Services') . ' | Hospital Equipment & Engineering - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('meta_description', 'Explore Innotech Medical catalog of hospital ICU systems, clinical diagnostics, biomedical calibration, operating theatre infrastructure, and certified medical devices.')
@section('canonical_url', route('services'))
@section('og_title', 'Hospital Products & Biomedical Services | ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('og_description', 'Explore Innotech Medical catalog of hospital ICU systems, clinical diagnostics, biomedical calibration, operating theatre infrastructure, and certified medical devices.')
@section('og_image', asset(\App\Models\Setting::get('services_banner_image', 'assets/img/banner/breadcrumb-01.jpg')))

@section('content')
<main>

   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('services_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-6 col-md-7 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title">{{ \App\Models\Setting::get('services_banner_title', 'Products & Services') }}</h2>
               </div>
            </div>
            <div class="col-lg-6 col-md-5 col-12">
               <div class="tp-breadcrumb__link d-flex align-items-center justify-content-md-end">
                  <span>Innotech : <a href="{{ route('services') }}">{{ \App\Models\Setting::get('services_banner_subtitle', 'Services') }}</a></span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- 2. DYNAMIC SERVICES GRID AREA -->
   <section class="services-area pt-120 pb-90 grey-bg" data-background="{{ asset('assets/img/shape/shape-bg-01.png') }}">
      <div class="container">
         <div class="row text-center">
            <div class="col-lg-12 col-md-12 col-12">
               <div class="tp-section">
                  <span class="tp-section__sub-title left-line right-line mb-20">{{ \App\Models\Setting::get('services_section_subtitle', 'Our Medical Services') }}</span>
                  <h3 class="tp-section__title mb-70">{{ \App\Models\Setting::get('services_section_title', 'Clinical Equipment & Engineering Area') }}</h3>
               </div>
            </div>
         </div>

         <div class="row g-4 mb-30">
            @php
               $iconClasses = ['', 'pink-icon', 'green-icon', 'sky-icon', 'pink-icon', 'green-icon'];
               $hexaClasses = ['', 'pink-hexa', 'green-hexa', 'sky-hexa', 'pink-hexa', 'green-hexa'];
               $defaultIcons = [
                  'flaticon-hemoglobin-test-meter',
                  'flaticon-blood-test',
                  'flaticon-biochemistry',
                  'flaticon-dna-1',
                  'flaticon-bacteria',
                  'flaticon-dna'
               ];
            @endphp

            @forelse($services as $index => $srv)
               @php
                  $iconClass = $iconClasses[$index % count($iconClasses)];
                  $hexaClass = $hexaClasses[$index % count($hexaClasses)];
                  $defaultIcon = $defaultIcons[$index % count($defaultIcons)];
               @endphp
               <div class="col-xl-4 col-md-6 mb-40">
                  <div class="services-item wow fadeInUp h-100 d-flex flex-column" data-wow-delay=".{{ ($index % 3 + 1) * 2 }}s">
                     <div class="services-item__icon {{ $iconClass }} mb-30">
                        @if(!empty($srv->icon))
                           <i class="{{ $srv->icon }}"></i>
                        @else
                           <i class="{{ $defaultIcon }}"></i>
                        @endif
                     </div>
                     <div class="services-item__content d-flex flex-column flex-grow-1">
                        <h4 class="services-item__tp-title tp-srv-title mb-25">
                           <a href="{{ route('service.detail', $srv->slug) }}">{{ $srv->title }}</a>
                        </h4>
                        <p class="flex-grow-1 text-secondary mb-30" style="line-height: 1.65;">
                           {{ Str::limit(strip_tags($srv->short_description ?: $srv->description), 115) }}
                        </p>
                        <div class="services-item__btn mt-auto">
                           <a class="btn-hexa {{ $hexaClass }}" href="{{ route('service.detail', $srv->slug) }}">
                              <i></i>Read More
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            @empty
               <div class="col-12 text-center py-5">
                  <div class="card p-5 border-0 shadow-sm rounded-4">
                     <p class="text-muted mb-0">No active services published currently.</p>
                  </div>
               </div>
            @endforelse
         </div>
      </div>
   </section>
   <!-- services-area-end -->

   <!-- 3. WHY CHOOSE US AREA (DYNAMIC VIA ADMIN SETTINGS) -->
   <section class="choose-area theme-bg pt-120 pb-130">
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="tp-section text-center">
                  <span class="tp-section__sub-title left-line right-line mb-25">{{ \App\Models\Setting::get('services_why_choose_subtitle', 'Why Choose Us') }}</span>
                  <h3 class="tp-section__title title-white mb-85">{{ \App\Models\Setting::get('services_why_choose_title', 'Biomedical Excellence & Reliability') }}</h3>
               </div>
            </div>
         </div>
         <div class="row">
            <!-- Feature 1 -->
            <div class="col-xl-3 col-md-6">
               <div class="tp-choose__item ml-15 mb-100 wow fadeInUp" data-wow-delay=".2s">
                  <div class="tp-choose__icon mb-40">
                     <i class="flaticon-microscope"></i>
                  </div>
                  <div class="tp-choose__content">
                     <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('services_why_1_title', 'High Quality Equipment'))) !!}</h4>
                     <p>{!! nl2br(e(\App\Models\Setting::get('services_why_1_desc', 'ISO 13485 and CE certified biomedical systems built for accuracy.'))) !!}</p>
                  </div>
               </div>
            </div>
            <!-- Feature 2 -->
            <div class="col-xl-3 col-md-6">
               <div class="tp-choose__item ml-35 mb-100 wow fadeInUp" data-wow-delay=".4s">
                  <div class="tp-choose__icon pink-icon mb-40">
                     <i class="flaticon-thinking"></i>
                  </div>
                  <div class="tp-choose__content">
                     <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('services_why_2_title', 'Rapid Field Response'))) !!}</h4>
                     <p>{!! nl2br(e(\App\Models\Setting::get('services_why_2_desc', 'Immediate calibration & servicing for critical care hospital wards.'))) !!}</p>
                  </div>
               </div>
            </div>
            <!-- Feature 3 -->
            <div class="col-xl-3 col-md-6">
               <div class="tp-choose__item ml-55 mb-100 wow fadeInUp" data-wow-delay=".6s">
                  <div class="tp-choose__icon green-icon mb-40">
                     <i class="flaticon-24-hours-1"></i>
                  </div>
                  <div class="tp-choose__content">
                     <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('services_why_3_title', '24/7 Biomedical Support'))) !!}</h4>
                     <p>{!! nl2br(e(\App\Models\Setting::get('services_why_3_desc', 'Dedicated clinical support desk with round-the-clock availability.'))) !!}</p>
                  </div>
               </div>
            </div>
            <!-- Feature 4 -->
            <div class="col-xl-3 col-md-6">
               <div class="tp-choose__item ml-75 mb-100 wow fadeInUp" data-wow-delay=".8s">
                  <div class="tp-choose__icon sky-icon mb-40">
                     <i class="flaticon-team"></i>
                  </div>
                  <div class="tp-choose__content">
                     <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('services_why_4_title', 'Certified Expert Team'))) !!}</h4>
                     <p>{!! nl2br(e(\App\Models\Setting::get('services_why_4_desc', 'Factory-certified biomedical engineers and hospital project managers.'))) !!}</p>
                  </div>
               </div>
            </div>
         </div>
         <div class="row text-center">
            <div class="col-lg-12">
               <div class="tp-choose-option">
                  <span>{{ \App\Models\Setting::get('services_why_banner_text', 'Healthcare Infrastructure & Turnkey Hospital Engineering :') }} <a href="{{ url(\App\Models\Setting::get('services_why_banner_btn_url', '/contact')) }}">{{ \App\Models\Setting::get('services_why_banner_btn_text', 'Request Consultation') }} <i class="fa-solid fa-arrow-right"></i></a></span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- choose-area-end -->

   <!-- NOTE: Pricing Section completely removed as requested by the user -->

   <!-- 4. SUPPORT / DIRECT CONTACT AREA -->
   <section class="support-area grey-bg pt-125 pb-130">
      <div class="container">
         <div class="row text-center">
            <div class="col-lg-12 col-md-12 col-12">
               <div class="tp-section">
                  <span class="tp-section__sub-title left-line right-line mb-20">Get in touch</span>
                  <h3 class="tp-section__title mb-70">Need Any Equipment Assistance?</h3>
               </div>
            </div>
         </div>
         <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12 col-12">
               <div class="tp-support-form text-center bg-white p-4 p-md-5 rounded-4 shadow-sm">
                  <span class="text-primary fw-bold text-uppercase d-block mb-3">Direct Contact with our Engineering Desk</span>

                  @if(session('success'))
                     <div class="alert alert-success d-flex align-items-center mb-4 text-start">
                        <i class="fa-solid fa-circle-check fs-4 me-2"></i>
                        <div>{{ session('success') }}</div>
                     </div>
                  @endif

                  <form action="{{ route('contact.store') }}" method="POST">
                     @csrf
                     <div class="row g-3 text-start">
                        <div class="col-md-6">
                           <input type="text" name="name" class="form-control py-3 px-3" placeholder="Enter your Name *" required>
                        </div>
                        <div class="col-md-6">
                           <input type="email" name="email" class="form-control py-3 px-3" placeholder="Enter your Email *" required>
                        </div>
                        <div class="col-md-6">
                           <input type="text" name="phone" class="form-control py-3 px-3" placeholder="Phone Number (Optional)">
                        </div>
                        <div class="col-md-6">
                           <input type="text" name="subject" class="form-control py-3 px-3" placeholder="Service Interested In / Equipment Inquiry">
                        </div>
                        <div class="col-12">
                           <textarea name="message" class="form-control p-3" rows="4" placeholder="Type your message, equipment requirements, or project details..." required></textarea>
                        </div>
                        <div class="col-12 text-center mt-4">
                           <div class="tp-support-form__btn">
                              <button type="submit" class="tp-btn">
                                 Send Inquiry Message <i class="fa-solid fa-paper-plane ms-1"></i>
                              </button>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- support-area-end -->

</main>

<style>
.services-item {
   border: 1px solid #eef2f6;
   border-radius: 12px;
   box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
   transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
   padding: 60px 45px 55px 45px !important;
}
.services-item:hover {
   transform: translateY(-6px);
   box-shadow: 0 16px 36px rgba(14, 99, 255, 0.12);
   border-color: rgba(14, 99, 255, 0.25);
}
</style>
@endsection
