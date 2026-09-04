@extends('layouts.app')

@section('title', 'Contact Us | 24/7 Clinical Equipment Support - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('meta_description', 'Get in touch with Innotech Medical Pvt Ltd for equipment procurement, biomedical calibration services, repair requests, and turnkey healthcare consultations.')
@section('canonical_url', route('contact'))
@section('og_title', 'Contact Innotech Medical Support Desk')
@section('og_description', 'Get in touch with Innotech Medical Pvt Ltd for equipment procurement, biomedical calibration services, repair requests, and turnkey healthcare consultations.')
@section('og_image', asset(\App\Models\Setting::get('contact_banner_image', 'assets/img/banner/breadcrumb-01.jpg')))

@section('content')

      <!-- main-area -->
      <main>

         <!-- breadcrumb-area -->
         <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('contact_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-xl-7 col-lg-8 col-md-12 col-12">
                     <div class="tp-breadcrumb">
                        <h2 class="tp-breadcrumb__title">{{ \App\Models\Setting::get('contact_banner_title', 'Contact us') }}</h2>
                     </div>
                  </div>
                  <div class="col-xl-5 col-lg-4 col-md-12 col-12">
                     <div class="tp-breadcrumb__link serv-md d-flex justify-content-lg-end">
                        <span>Innotech : <a href="{{ url('/contact') }}">{{ \App\Models\Setting::get('contact_banner_subtitle', 'Contact') }}</a></span>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- breadcrumb-area-end -->
         
         <!-- contact-area -->
         <section class="contact-area pt-130 pb-115">
            <div class="container">
               <div class="row">
                  <div class="col-lg-4 col-md-5 col-12 wow fadeInLeft" data-wow-delay=".4s">
                     <!-- 1. Address Item -->
                     <div class="tpcontact mr-60 mb-60 wow fadeInUp" data-wow-delay=".2s">
                        <div class="tpcontact__item text-center">
                           <div class="tpcontact__icon mb-20">
                              <img src="{{ asset('assets/img/icon/contact-01.svg') }}" alt="contact-icon">
                           </div>
                           <div class="tpcontact__address">
                              <h4 class="tpcontact__title mb-15">Address Line</h4>
                              <span><a href="{{ url('/contact') }}">{{ \App\Models\Setting::get('office_address', '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.') }}</a></span>
                           </div>
                        </div>
                     </div>

                     <!-- 2. Phone Item -->
                     <div class="tpcontact mr-60 mb-60 wow fadeInUp" data-wow-delay=".4s">
                        <div class="tpcontact__item text-center">
                           <div class="tpcontact__icon mb-20">
                              <img src="{{ asset('assets/img/icon/contact-02.svg') }}" alt="phone-icon">
                           </div>
                           <div class="tpcontact__address">
                              <h4 class="tpcontact__title mb-15">Phone Number</h4>
                              <span>
                                 <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992')) }}">
                                    {{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }}
                                 </a>
                                 @if($altPhone = \App\Models\Setting::get('emergency_phone'))
                                    <br>
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $altPhone) }}" class="text-muted small">
                                       <i class="fa-solid fa-phone-volume text-danger me-1"></i> {{ $altPhone }}
                                    </a>
                                 @endif
                              </span>
                           </div>
                        </div>
                     </div>

                     <!-- 3. Working Hours Item -->
                     <div class="tpcontact mr-60 mb-60 wow fadeInUp" data-wow-delay=".6s">
                        <div class="tpcontact__item text-center">
                           <div class="tpcontact__icon mb-20">
                              <img src="{{ asset('assets/img/icon/contact-03.svg') }}" alt="hours-icon">
                           </div>
                           <div class="tpcontact__address">
                              <h4 class="tpcontact__title mb-15">Opening Hours</h4>
                              <span>{!! nl2br(e(\App\Models\Setting::get('working_hours', "Monday - Saturday:\n10:00 AM - 06:00 PM"))) !!}</span>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="col-lg-8 col-md-7 col-12">
                     <div class="contactform wow fadeInRight" data-wow-delay=".4s">
                        <span class="tp-section__sub-title left-line mb-10 text-primary fw-bold" style="font-size: 13px; letter-spacing: 1px; text-transform: uppercase;">
                           {{ \App\Models\Setting::get('contact_form_subtitle', 'SEND US AN INQUIRY') }}
                        </span>
                        <h3 class="tp-section__title mb-15" style="font-size: 32px;">
                           {{ \App\Models\Setting::get('contact_form_title', 'Ready to Upgrade Your Hospital or Laboratory?') }}
                        </h3>
                        <p class="text-muted mb-35" style="font-size: 15px; line-height: 24px;">
                           {{ \App\Models\Setting::get('contact_form_description', 'Leave your project requirements, equipment inquiries, or technical support requests below. Our biomedical specialists will assist you immediately.') }}
                        </p>
                        
                        @if(session('success'))
                           <div class="alert alert-success mb-30 rounded-3 shadow-sm" style="background-color: #ECFDF5; border: 1.5px solid #10B981; color: #065F46;">
                              <i class="fa-solid fa-circle-check text-success me-2 fs-5"></i>{{ session('success') }}
                           </div>
                        @endif

                        @if(session('error'))
                           <div class="alert alert-danger mb-30 rounded-3 shadow-sm" style="background-color: #FEF2F2; border: 1.5px solid #F87171; color: #7F1D1D;">
                              <div style="font-weight: 700; color: #B91C1C; font-size: 13.5px; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                                 <i class="fa-solid fa-clock-rotate-left" style="color: #DC2626;"></i> Temporary 2-Hour Suspension
                              </div>
                              <div style="font-size: 13px; line-height: 1.5; font-weight: 500;">
                                 {{ session('error') }}
                              </div>
                           </div>
                        @endif

                        <div class="contactform__list mb-60">
                           <form id="contact-form" class="ajax-contact-form" action="{{ route('contact.store') }}" method="post">
                              @csrf
                              <div class="row">
                                 <div class="col-lg-6 mb-20">
                                    <input class="form-control py-3 px-3 rounded-2" name="name" type="text" placeholder="Enter your full name *" required style="border-color: #E2E8F0; font-size: 15px;">
                                 </div>
                                 <div class="col-lg-6 mb-20">
                                    <input class="form-control py-3 px-3 rounded-2" name="email" type="email" placeholder="Enter your email *" required style="border-color: #E2E8F0; font-size: 15px;">
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
                                    <button type="submit" class="tp-btn-theme" style="padding: 14px 34px; font-size: 14px; font-weight: 700; letter-spacing: 0.5px; border-radius: 8px;">
                                       <span style="display: inline-block; margin-bottom: 0; font-size: 14px;">SEND MESSAGE</span>
                                       <i class="fa-solid fa-paper-plane ms-2"></i>
                                    </button>
                                    <div class="ajax-response mt-3 mb-0" style="display: none;"></div>
                                 </div>
                              </div>
                           </form>
                        </div>

                        <!-- Dynamic Google Map Location -->
                        @php
                           $rawMap = trim(\App\Models\Setting::get('contact_map_iframe', ''));
                           $mapSrc = '';
                           if (!empty($rawMap)) {
                              if (preg_match('/src=["\']([^"\']+)["\']/', $rawMap, $matches)) {
                                 $mapSrc = $matches[1];
                              } else {
                                 $mapSrc = $rawMap;
                              }
                           }
                           if (empty($mapSrc)) {
                              $address = \App\Models\Setting::get('office_address', '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.');
                              $mapSrc = 'https://maps.google.com/maps?q=' . urlencode($address) . '&t=&z=16&ie=UTF8&iwloc=&output=embed';
                           }
                        @endphp
                        <div class="row">
                           <div class="col-lg-12">
                              <div class="tpcontactmap rounded-4 overflow-hidden shadow-sm" style="border: 1px solid #E2E8F0;">
                                 <iframe 
                                    src="{{ $mapSrc }}" 
                                    width="100%" 
                                    height="450" 
                                    style="border:0; display: block;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                                 </iframe>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- contact-area-end -->

      </main>
      <!-- main-area-end -->

@endsection
