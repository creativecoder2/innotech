@extends('layouts.app')

@section('title', \App\Models\Setting::get('team_banner_title', 'Our Specialists & Healthcare Experts') . ' - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))

@section('content')
<main>

   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('team_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-xl-7 col-lg-8 col-md-12 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title">{{ \App\Models\Setting::get('team_banner_title', 'Our Specialists & Healthcare Experts') }}</h2>
               </div>
            </div>
            <div class="col-xl-5 col-lg-4 col-md-12 col-12">
               <div class="tp-breadcrumb__link serv-md d-flex justify-content-lg-end">
                  <span>Innotech : <a href="{{ url('/specialists') }}">{{ \App\Models\Setting::get('team_banner_subtitle', 'Specialists') }}</a></span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- 2. TEAM AREA -->
   <section class="team-area pt-125 pb-70">
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="tp-section text-center">
                  <span class="tp-section__sub-title left-line right-line mb-25">{{ \App\Models\Setting::get('team_subtitle', 'Specialists Team') }}</span>
                  <h3 class="tp-section__title mb-70">{{ \App\Models\Setting::get('team_title', 'Meet Our Specialists') }}</h3>
               </div>
            </div>
         </div>
         <div class="row">
            @forelse($teamMembers as $member)
               <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                  <div class="team-item mb-35 wow fadeInUp" data-wow-delay=".2s">
                     <div class="team-item__thumb mb-40">
                        <a href="{{ route('specialist.detail', $member->slug ?: $member->id) }}">
                           <img src="{{ asset($member->image ?: 'assets/img/team/team-thumb-05.png') }}" alt="{{ $member->name }}">
                        </a>
                     </div>
                     <div class="team-item__content">
                        <h5 class="team-item__title mb-15">
                           <a href="{{ route('specialist.detail', $member->slug ?: $member->id) }}">{{ $member->name }}</a>
                        </h5>
                        <span>{{ $member->designation }}</span>
                        <div class="team-item__social-info">
                           @if($member->facebook_url)
                              <a href="{{ $member->facebook_url }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                           @endif
                           @if($member->twitter_url)
                              <a href="{{ $member->twitter_url }}" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                           @endif
                           @if($member->instagram_url)
                              <a href="{{ $member->instagram_url }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                           @endif
                           @if($member->pinterest_url)
                              <a href="{{ $member->pinterest_url }}" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                           @endif
                        </div>
                     </div>
                  </div>
               </div>
            @empty
               <div class="col-12 text-center py-5">
                  <p class="text-muted">No specialists listed yet.</p>
               </div>
            @endforelse
         </div>
      </div>
   </section>
   <!-- team-area-end -->

   <!-- 3. SUPPORT / CONSULTATION AREA -->
   <section class="support-area grey-bg pt-125 pb-130">
      <div class="container">
         <div class="row text-center">
            <div class="col-lg-12 col-md-12 col-12">
               <div class="tp-section">
                  <span class="tp-section__sub-title left-line right-line mb-20 text-primary fw-bold" style="font-size: 13px; letter-spacing: 1px; text-transform: uppercase;">SEND US AN INQUIRY</span>
                  <h3 class="tp-section__title mb-20">Ready to Upgrade Your Hospital or Laboratory?</h3>
                  <p class="text-muted mb-60 mx-auto" style="max-width: 700px; font-size: 15px; line-height: 24px;">Leave your project requirements, equipment inquiries, or technical support requests below. Our biomedical specialists will assist you immediately.</p>
               </div>
            </div>
         </div>
         <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10 col-md-12 col-12">
               <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
                  @if(session('success'))
                     <div class="alert alert-success mb-25 text-center">
                        {{ session('success') }}
                     </div>
                  @endif
                  <form action="{{ route('contact.store') }}" method="POST">
                     @csrf
                     <div class="row text-start">
                        <div class="col-md-6 mb-3">
                           <input class="form-control py-3 px-3 rounded-2" type="text" name="name" placeholder="Enter your full name" required style="border-color: #E2E8F0; font-size: 15px;">
                        </div>
                        <div class="col-md-6 mb-3">
                           <input class="form-control py-3 px-3 rounded-2" type="email" name="email" placeholder="Enter your email" required style="border-color: #E2E8F0; font-size: 15px;">
                        </div>
                        <div class="col-md-6 mb-3">
                           <input class="form-control py-3 px-3 rounded-2" type="text" name="phone" placeholder="Enter phone / mobile number" style="border-color: #E2E8F0; font-size: 15px;">
                        </div>
                        <div class="col-md-6 mb-3">
                           <input class="form-control py-3 px-3 rounded-2" type="text" name="subject" placeholder="Equipment inquiry / Organization" style="border-color: #E2E8F0; font-size: 15px;">
                        </div>
                        <div class="col-12 mb-4">
                           <textarea class="form-control py-3 px-3 rounded-2" name="message" rows="5" placeholder="How can our biomedical team assist your facility?" required style="border-color: #E2E8F0; font-size: 15px;"></textarea>
                        </div>
                        <div class="col-12 text-start">
                           <button type="submit" class="tp-btn text-uppercase font-weight-bold" style="background-color: #239fda; border-radius: 6px; padding: 16px 36px;">SEND MESSAGE</button>
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
@endsection
