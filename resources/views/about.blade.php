@extends('layouts.app')

@section('title', 'About Us | Medical Engineering Excellence - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('meta_description', 'Learn about Innotech Medical Pvt Ltd, our vision, ISO 13485 certified quality management, engineering leadership, and turnkey hospital biomedical infrastructure.')
@section('canonical_url', route('about'))
@section('og_title', 'About Innotech Medical Pvt Ltd | Engineering Healthcare')
@section('og_description', 'Learn about Innotech Medical Pvt Ltd, our vision, ISO 13485 certified quality management, engineering leadership, and turnkey hospital biomedical infrastructure.')
@section('og_image', asset(\App\Models\Setting::get('about_banner_image', 'assets/img/banner/breadcrumb-01.jpg')))

@push('styles')
<style>
   /* Equal Height About Page Specialist Cards */
   .about-team-active {
      padding-bottom: 20px !important;
   }
   .about-team-active .swiper-wrapper {
      align-items: stretch !important;
      display: flex !important;
   }
   .about-team-active .swiper-slide {
      height: auto !important;
      display: flex !important;
   }
   .about-team-card {
      background: #FFFFFF;
      border-radius: 16px;
      overflow: hidden;
      border: 1px solid var(--tp-border-primary, #ECEEF3);
      box-shadow: 0 8px 24px rgba(23, 17, 81, 0.06);
      transition: all 0.3s ease;
      height: 100% !important;
      min-height: 100% !important;
      width: 100%;
      display: flex !important;
      flex-direction: column !important;
      margin-bottom: 20px;
   }
   .about-team-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 36px rgba(35, 159, 218, 0.16);
      border-color: var(--tp-theme-blue, #239fda);
   }
   .about-team-card__thumb {
      height: 270px !important;
      min-height: 270px !important;
      max-height: 270px !important;
      overflow: hidden;
      background: #FFFFFF !important;
      flex-shrink: 0;
      width: 100%;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      padding-top: 20px;
   }
   .about-team-card__thumb a {
      display: block;
      width: 100%;
      height: 100%;
   }
   .about-team-card__thumb img {
      width: 100% !important;
      height: 100% !important;
      object-fit: contain !important;
      object-position: bottom center !important;
      display: block;
      transition: transform 0.4s ease;
   }
   .about-team-card:hover .about-team-card__thumb img {
      transform: scale(1.04);
   }
   .about-team-card__content {
      padding: 20px 20px 25px;
      text-align: center;
      flex-grow: 1 !important;
      display: flex !important;
      flex-direction: column !important;
   }
   .about-team-card__title {
      font-size: 18px;
      font-weight: 700;
      color: var(--tp-heading-primary, #171151);
      margin-bottom: 6px;
   }
   .about-team-card__title a {
      color: inherit;
      text-decoration: none;
   }
   .about-team-card__title a:hover {
      color: var(--tp-theme-blue, #239fda);
   }
   .about-team-card__position {
      font-size: 12px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--tp-theme-blue, #239fda);
      margin-bottom: 12px;
      min-height: 34px;
      display: flex;
      align-items: center;
      justify-content: center;
   }
   .about-team-card__bio {
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
      gap: 8px;
   }
   .team-social-btn {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #FFFFFF !important;
      font-size: 15px;
      text-decoration: none;
      transition: all 0.25s ease;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
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
</style>
@endpush

@section('content')

      <!-- main-area -->
      <main>

         <!-- 1. BREADCRUMB AREA -->
         <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('about_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-xl-7 col-lg-8 col-md-12 col-12">
                     <div class="tp-breadcrumb">
                        <h2 class="tp-breadcrumb__title">{{ \App\Models\Setting::get('about_banner_title', 'About us') }}</h2>
                     </div>
                  </div>
                  <div class="col-xl-5 col-lg-4 col-md-12 col-12">
                     <div class="tp-breadcrumb__link serv-md d-flex justify-content-lg-end">
                        <span>Innotech : <a href="{{ url('/about') }}">{{ \App\Models\Setting::get('about_banner_subtitle', 'About Us') }}</a></span>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- breadcrumb-area-end -->

         <!-- 2. ABOUT AREA -->
         <section class="about-area pt-130 pb-70">
            <div class="container">
               <div class="row">
                  <div class="col-xl-6 col-lg-4 col-12">
                     <div class="tp-about-thumb mb-60 wow fadeInLeft" data-wow-delay=".3s">
                        <div class="tp-ab-img d-flex">
                           <div class="tp-ab-main-img p-relative">
                              <img src="{{ asset(\App\Models\Setting::get('about_page_main_image', 'assets/img/about/about-bg-04.jpg')) }}" alt="about-thumb">
                              <div class="about__exprience tp-ab-counter">
                                 <h3 class="counter">{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('about_experience_years', '7')) ?: '7' }}</h3>
                                 <i>{{ \App\Models\Setting::get('about_experience_label', 'Years of Experience') }}</i>
                              </div>
                           </div>
                           <div class="tp-ab-shape d-none d-md-block d-lg-none d-xl-block">
                              <img class="ab-shape-one" src="{{ asset(\App\Models\Setting::get('about_shape_1', 'assets/img/about/about-bg-05.jpg')) }}" alt="about-shape">
                              <img class="ab-shape-two" src="{{ asset(\App\Models\Setting::get('about_shape_2', 'assets/img/about/about-bg-06.jpg')) }}" alt="about-shape">
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-6 col-lg-8 col-12">
                     <div class="about-content about-align mb-60 wow fadeInRight" data-wow-delay=".3s">
                        <div class="tp-section">
                           <h3 class="tp-section__title ab-title mb-25">{{ \App\Models\Setting::get('about_heading', 'Innovating Healthcare with Advance Technologies') }}</h3>
                           <a class="tp-section__link" href="{{ url('/contact') }}">{{ \App\Models\Setting::get('about_link_text', 'Read our Mission & Vision') }} <i class="fa-solid fa-arrow-right"></i></a>
                           <p class="mr-20 mb-40">
                              {!! nl2br(e(\App\Models\Setting::get('about_description', 'At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.'))) !!}
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- about-area-end -->

         <!-- 3. COUNTER AREA -->
         <section class="counter-area pb-100">
            <div class="container">
               <div class="row">
                  <div class="col-xl-3 col-md-6">
                     <div class="counter__item blue-border mb-30 wow fadeInUp" data-wow-delay=".2s">
                        <div class="counter__icon mb-15">
                           <i></i>
                        </div>
                        <div class="counter__content">
                           <h4 class="counter__title"><span class="counter">{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('stat_clients_count', '1492')) ?: '1492' }}</span></h4>
                           <p>{{ \App\Models\Setting::get('stat_clients_label', 'Laboratories in 100+ states') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="counter__item pink-border mb-30 wow fadeInUp" data-wow-delay=".4s">
                        <div class="counter__icon pink-hard mb-15">
                           <i></i>
                        </div>
                        <div class="counter__content">
                           <h4 class="counter__title"><span class="counter">{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('stat_devices_count', '152')) ?: '152' }}</span></h4>
                           <p>{{ \App\Models\Setting::get('stat_devices_label', 'Laboratory specialists') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="counter__item sky-border mb-30 wow fadeInUp" data-wow-delay=".6s">
                        <div class="counter__icon sky-hard mb-15">
                           <i></i>
                        </div>
                        <div class="counter__content">
                           <h4 class="counter__title"><span class="counter">{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('stat_engineers_count', '1022')) ?: '1022' }}</span></h4>
                           <p>{{ \App\Models\Setting::get('stat_engineers_label', 'Material collection points') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="counter__item green-border mb-30 wow fadeInUp" data-wow-delay=".8s">
                        <div class="counter__icon green-hard mb-15">
                           <i></i>
                        </div>
                        <div class="counter__content">
                           <h4 class="counter__title"><span class="counter">{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('stat_support_count', '24332')) ?: '24332' }}</span></h4>
                           <p>{{ \App\Models\Setting::get('stat_support_label', 'Patients diagnosed in 2022') }}</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- counter-area-end -->

         <!-- 4. CHOOSE AREA -->
         <section class="choose-area theme-bg-2 pt-120 pb-130">
            <div class="container">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="tp-section text-center">
                        <span class="tp-section__sub-title left-line right-line mb-25">{{ \App\Models\Setting::get('about_choose_subtitle', 'Our Specialists') }}</span>
                        <h3 class="tp-section__title title-white mb-85">{{ \App\Models\Setting::get('about_choose_title', 'Why Choose Us') }}</h3>
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
                           <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('about_why_1_title', "Global Standards &\nQuality"))) !!}</h4>
                           <p>{{ \App\Models\Setting::get('about_why_1_desc', 'Delivering FDA, CE, and ISO certified medical equipment from global principal brands, ensuring maximum clinical accuracy and patient safety.') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="tp-choose__item ml-35 mb-100 wow fadeInUp" data-wow-delay=".4s">
                        <div class="tp-choose__icon pink-icon mb-40">
                           <i class="flaticon-thinking"></i>
                        </div>
                        <div class="tp-choose__content">
                           <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('about_why_2_title', "Swift Turnkey\nDelivery"))) !!}</h4>
                           <p>{{ \App\Models\Setting::get('about_why_2_desc', 'Streamlined procurement and rapid installation, delivering complete healthcare projects with minimal turnaround time and total precision.') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="tp-choose__item ml-55 mb-100 wow fadeInUp" data-wow-delay=".6s">
                        <div class="tp-choose__icon green-icon mb-40">
                           <i class="flaticon-24-hours-1"></i>
                        </div>
                        <div class="tp-choose__content">
                           <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('about_why_3_title', "24/7 Emergency\nSupport"))) !!}</h4>
                           <p>{{ \App\Models\Setting::get('about_why_3_desc', 'Round-the-clock technical coverage and rapid dispatch troubleshooting to eliminate critical equipment downtime in ICUs and OTs.') }}</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-3 col-md-6">
                     <div class="tp-choose__item ml-75 mb-100 wow fadeInUp" data-wow-delay=".8s">
                        <div class="tp-choose__icon sky-icon mb-40">
                           <i class="flaticon-team"></i>
                        </div>
                        <div class="tp-choose__content">
                           <h4 class="tp-choose__title mb-20">{!! nl2br(e(\App\Models\Setting::get('about_why_4_title', "Certified Biomedical\nExperts"))) !!}</h4>
                           <p>{{ \App\Models\Setting::get('about_why_4_desc', 'Backed by OEM-trained engineers executing precision calibration, complex repairs, and proactive maintenance to global standards.') }}</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row text-center">
                  <div class="col-lg-12">
                     <div class="tp-choose-option">
                        <span>{{ \App\Models\Setting::get('about_choose_bottom_text', 'Scientific Research Laboratories:') }} <a href="{{ url(\App\Models\Setting::get('about_choose_bottom_link_url', '/contact')) }}">{{ \App\Models\Setting::get('about_choose_bottom_link_text', 'Contact Us') }}<i class="fa-solid fa-arrow-right"></i></a></span>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- choose-area-end -->

         <!-- 5. INTERACTIVE PROCESS, MISSION & VALUE TABS -->
         <section class="nav-area tp-common-area pt-130 pb-80">
            <div class="container">
               <!-- Tab Buttons -->
               <ul class="nav tp-nav-tavs mb-70 justify-content-center" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                     <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Our Process</button>
                  </li>
                  <li class="nav-item" role="presentation">
                     <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Our Mission</button>
                  </li>
                  <li class="nav-item" role="presentation">
                     <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Our Value</button>
                  </li>
               </ul>

               <!-- Tab Panes -->
               <div class="tab-content" id="myTabContent">
                  <!-- Tab 1: Our Process -->
                  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                     <span class="nav-info d-flex justify-content-center text-center mb-75">{{ \App\Models\Setting::get('about_process_desc', 'Your trusted partner for medical equipment procurement, turnkey installations, and technical integration across Pakistan.') }}</span>
                     <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6">
                           <div class="navtabs nav-primary p-relative text-center mb-40">
                              <div class="navtabs__icon mb-35">
                                 <i class="flaticon-approval"></i>
                              </div>
                              <div class="navtabs__content">
                                 <h5 class="navtabs__title mb-25 mb-10">{{ \App\Models\Setting::get('about_step_1_title', 'Consultation & Proposal') }}</h5>
                                 <p>{{ \App\Models\Setting::get('about_step_1_desc', 'Understanding facility requirements to recommend compliant, cost-effective medical equipment solutions.') }}</p>
                              </div>
                              <div class="navtabs__shape d-none d-lg-block">
                                 <img src="{{ asset('assets/img/shape/navtabs-01.png') }}" alt="shape">
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                           <div class="navtabs nav-secondary p-relative text-center mb-40">
                              <div class="navtabs__icon mb-35">
                                 <i class="flaticon-flask"></i>
                              </div>
                              <div class="navtabs__content">
                                 <h5 class="navtabs__title mb-25 mb-10">{{ \App\Models\Setting::get('about_step_2_title', 'Seamless Deployment') }}</h5>
                                 <p>{{ \App\Models\Setting::get('about_step_2_desc', 'Rapid procurement, physical installation, and precise site calibration by certified biomedical engineers.') }}</p>
                              </div>
                              <div class="navtabs__shape d-none d-lg-block">
                                 <img src="{{ asset('assets/img/shape/navtabs-01.png') }}" alt="shape">
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                           <div class="navtabs nav-tertiary text-center mb-40">
                              <div class="navtabs__icon mb-35">
                                 <i class="flaticon-report"></i>
                              </div>
                              <div class="navtabs__content">
                                 <h5 class="navtabs__title mb-25 mb-10">{{ \App\Models\Setting::get('about_step_3_title', 'Integration & Support') }}</h5>
                                 <p>{{ \App\Models\Setting::get('about_step_3_desc', 'Comprehensive staff application training alongside 24/7 technical support and routine maintenance.') }}</p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- Tab 2: Our Mission -->
                  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                     <span class="nav-info d-flex justify-content-center text-center mb-75">Your full service partner for healthcare technology. Our mission is to ensure the generation of <br> accurate and precise diagnostic findings.</span>
                     <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 order-lg-2">
                           <div class="nabmission mb-30">
                              <div class="nabmission__content text-center ml-50 mr-50 pt-20">
                                 <h4 class="nabmission__title mb-35">{{ \App\Models\Setting::get('about_mission_title', 'Our Mission is to Give You Always the Best Clinical Results.') }}</h4>
                                 <p class="mb-35">{!! nl2br(e(\App\Models\Setting::get('about_mission_desc', 'To enhance the quality of healthcare across Pakistan by delivering state-of-the-art medical devices, advanced diagnostic technologies, and uncompromised technical support to hospitals and laboratories.'))) !!}</p>
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 order-lg-1">
                           <div class="nabthumb mb-30">
                              <img src="{{ asset(\App\Models\Setting::get('about_mission_img1', 'assets/img/tab/tab-thumb-03.jpg')) }}" alt="tab-thumb" class="rounded-3 w-100">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 order-lg-3">
                           <div class="nabthumb mb-30">
                              <img src="{{ asset(\App\Models\Setting::get('about_mission_img2', 'assets/img/tab/tab-thumb-04.jpg')) }}" alt="tab-thumb" class="rounded-3 w-100">
                           </div>
                        </div>
                     </div>
                  </div>

                  <!-- Tab 3: Our Value -->
                  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                     <span class="nav-info d-flex justify-content-center text-center mb-75">Trusted biomedical engineering excellence across Pakistan.</span>
                     <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 order-lg-2">
                           <div class="nabmission mb-30">
                              <div class="nabmission__content text-center ml-50 mr-50 pt-20">
                                 <h4 class="nabmission__title mb-35">{{ \App\Models\Setting::get('about_value_title', 'Trusted by Leading Clinical Facilities') }}</h4>
                                 <p class="mb-35">{!! nl2br(e(\App\Models\Setting::get('about_value_desc', 'To become Pakistan’s premier and most trusted B2B healthcare partner, driving innovation in biomedical engineering and empowering institutions with futuristic medical solutions.'))) !!}</p>
                              </div>
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 order-lg-1">
                           <div class="nabthumb mb-30">
                              <img src="{{ asset(\App\Models\Setting::get('about_value_img1', 'assets/img/tab/tab-thumb-01.jpg')) }}" alt="tab-thumb" class="rounded-3 w-100">
                           </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 order-lg-3">
                           <div class="nabthumb mb-30">
                              <img src="{{ asset(\App\Models\Setting::get('about_value_img2', 'assets/img/tab/tab-thumb-02.jpg')) }}" alt="tab-thumb" class="rounded-3 w-100">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- nav-tabs-area-end -->

         <!-- 6. SPECIALISTS TEAM SLIDER SECTION -->
         <section class="team-area grey-bg pt-120 pb-100" data-background="{{ asset('assets/img/shape/shape-bg-01.png') }}">
            <div class="container wow fadeInUp" data-wow-delay=".3s">
               <div class="row align-items-center mb-50">
                  <div class="col-lg-8 col-md-8 col-12">
                     <div class="tp-section">
                        <span class="tp-section__sub-title left-line mb-20">{{ \App\Models\Setting::get('team_subtitle', 'Specialists Team') }}</span>
                        <h3 class="tp-section__title mb-0">{{ \App\Models\Setting::get('team_title', 'Meet Our Biomedical Engineers & Healthcare Experts') }}</h3>
                     </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-12 text-md-end mt-3 mt-md-0">
                     <div class="tp-team-arrow d-inline-flex align-items-center">
                        <div class="team-p me-2"><i class="fa-regular fa-arrow-left"></i></div>
                        <div class="team-n"><i class="fa-regular fa-arrow-right"></i></div>
                     </div>
                  </div>
               </div>

               <div class="swiper-container about-team-active">
                  <div class="swiper-wrapper">
                     @forelse($teamMembers as $tm)
                        <div class="swiper-slide">
                           <div class="about-team-card mb-40">
                              <div class="about-team-card__thumb">
                                 <a href="{{ route('specialist.detail', $tm->slug ?: $tm->id) }}">
                                    <img src="{{ asset($tm->image ?: 'assets/img/team/team-thumb-01.jpg') }}" alt="{{ $tm->name }}">
                                 </a>
                              </div>
                              <div class="about-team-card__content">
                                 <h4 class="about-team-card__title">
                                    <a href="{{ route('specialist.detail', $tm->slug ?: $tm->id) }}">{{ $tm->name }}</a>
                                 </h4>
                                 <span class="about-team-card__position">{{ $tm->designation }}</span>
                                 <p class="about-team-card__bio">{{ Str::limit($tm->bio ?: 'Providing insight-driven transformation and medical technology solutions.', 85) }}</p>
                                 <div class="team-social-box">
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
                     @empty
                        <div class="col-12 text-center py-4 text-muted">
                           <p>No specialists added yet.</p>
                        </div>
                     @endforelse
                  </div>
               </div>

               <!-- Bottom Explore More Button -->
               <div class="text-center mt-30">
                  <a href="{{ url('/specialists') }}" class="tp-btn">Explore More</a>
               </div>
            </div>
         </section>
         <!-- team-area-end -->

      </main>
      <!-- main-area-end -->

@endsection

@push('scripts')
<script>
$(document).ready(function() {
   var aboutTeamSwiper = new Swiper('.about-team-active', {
      loop: true,
      slidesPerView: 4,
      spaceBetween: 24,
      autoplay: {
         delay: 4000,
         disableOnInteraction: false,
      },
      navigation: {
         nextEl: '.team-n',
         prevEl: '.team-p',
      },
      breakpoints: {
         '1200': { slidesPerView: 4 },
         '992': { slidesPerView: 3 },
         '768': { slidesPerView: 2 },
         '576': { slidesPerView: 1 },
         '0': { slidesPerView: 1 },
      },
   });
});
</script>
@endpush
