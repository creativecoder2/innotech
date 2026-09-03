@extends('layouts.app')

@section('title', $member->name . ' (' . $member->designation . ') - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))

@push('styles')
<style>
   .tp-team-dtls__thumb {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(23, 17, 81, 0.08);
      background: #FFFFFF !important;
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
   }
   .tp-team-dtls__thumb img {
      width: 100%;
      max-height: 500px;
      border-radius: 12px;
      object-fit: contain;
      display: block;
   }
   .tp-team-dtls__info ul li {
      margin-bottom: 12px;
      font-weight: 600;
      color: var(--tp-heading-primary, #171151);
   }
   .tp-team-dtls__info ul li span {
      font-weight: 500;
      color: var(--tp-text-2, #8A879F);
      margin-left: 8px;
   }
   .tp-team-dtls-item__list ul li {
      margin-bottom: 10px;
      font-size: 15px;
      color: var(--tp-heading-primary, #171151);
   }
   .tp-team-dtls-item__list ul li i {
      color: var(--tp-icon-green, #0b9748);
      margin-right: 10px;
   }
   .team-social-box {
      display: flex;
      align-items: center;
      gap: 10px;
   }
   .team-social-btn {
      width: 42px;
      height: 42px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #FFFFFF !important;
      font-size: 18px;
      text-decoration: none;
      transition: all 0.25s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
   }
   .team-social-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.22);
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
<main>

   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('team_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-xl-7 col-lg-8 col-md-12 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title">{{ $member->name }}</h2>
               </div>
            </div>
            <div class="col-xl-5 col-lg-4 col-md-12 col-12">
               <div class="tp-breadcrumb__link serv-md d-flex justify-content-lg-end">
                  <span>Innotech : <a href="{{ url('/specialists') }}"> Specialist Profile</a></span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- 2. TEAM DETAILS AREA -->
   <section class="team-details-area pt-130 pb-70">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-5 col-md-6 col-12">
               <div class="tp-team-dtls__thumb mb-50">
                  <img src="{{ asset($member->image ?: 'assets/img/team/team-thumb-05.png') }}" alt="{{ $member->name }}">
               </div>
            </div>
            <div class="col-lg-4 col-md-6 col-12">
               <div class="tp-team-dtls__content mt-30 mb-50">
                  <h4 class="tp-team-dtls__title mb-10"><a href="javascript:void(0)">{{ $member->name }}</a></h4>
                  <span class="text-primary font-weight-bold d-block mb-20">{{ $member->designation }}</span>
                  <p class="mb-30">{{ $member->bio ?: 'Providing insight-driven biomedical solutions and turnkey clinical equipment integrations.' }}</p>
                  <div class="tp-team-dtls__info">
                     <ul class="list-unstyled">
                        <li>Expertise: <span>{{ $member->expertise ?: $member->designation }}</span></li>
                        <li>Experience: <span>{{ $member->experience ?: '10+ Years' }}</span></li>
                        @if($member->email)
                           <li>E-mail: <span><a href="mailto:{{ $member->email }}">{{ $member->email }}</a></span></li>
                        @endif
                        @if($member->phone)
                           <li>Phone: <span><a href="tel:{{ preg_replace('/[^0-9+]/', '', $member->phone) }}">{{ $member->phone }}</a></span></li>
                        @endif
                     </ul>
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-md-12 col-12">
               <div class="team-social-box mt-30 mb-50 justify-content-lg-end">
                  @if($member->facebook_url)
                     <a class="team-social-btn team-social-fb" href="{{ $member->facebook_url }}" target="_blank" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                  @endif
                  @if($member->instagram_url)
                     <a class="team-social-btn team-social-insta" href="{{ $member->instagram_url }}" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                  @endif
                  @if($member->twitter_url)
                     <a class="team-social-btn team-social-tweet" href="{{ $member->twitter_url }}" target="_blank" title="Twitter"><i class="fa-brands fa-twitter"></i></a>
                  @endif
                  @if($member->pinterest_url)
                     <a class="team-social-btn team-social-in" href="{{ $member->pinterest_url }}" target="_blank" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                  @endif
                  @if(!$member->facebook_url && !$member->instagram_url && !$member->twitter_url && !$member->pinterest_url)
                     <a class="team-social-btn team-social-fb" href="https://facebook.com" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                     <a class="team-social-btn team-social-insta" href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                     <a class="team-social-btn team-social-tweet" href="https://twitter.com" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                     <a class="team-social-btn team-social-in" href="https://linkedin.com" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                  @endif
               </div>
            </div>
         </div>

         <!-- Personal Experience Section -->
         @if($member->personal_experience)
            <div class="row">
               <div class="col-lg-12">
                  <div class="tp-team-dtls-text mt-40">
                     <h4 class="tp-team-dtls-text__title mb-25">Professional Background & Clinical Experience</h4>
                     {!! nl2br(e($member->personal_experience)) !!}
                  </div>
               </div>
            </div>
         @endif

         <!-- Skills, Education, Awards Section -->
         <div class="row mt-40">
            @if(count($member->skills_array) > 0)
               <div class="col-lg-4 col-md-6 col-12">
                  <div class="tp-team-dtls-item mb-50 mt-35 wow fadeInUp" data-wow-delay=".2s">
                     <h4 class="tp-team-dtls-item__title mb-25">Key Competencies & Skills</h4>
                     <p class="text-muted mb-25">Core technical and clinical engineering capabilities:</p>
                     <div class="tp-team-dtls-item__list">
                        <ul class="list-unstyled">
                           @foreach($member->skills_array as $skill)
                              <li><i class="fa-solid fa-check"></i> {{ $skill }}</li>
                           @endforeach
                        </ul>
                     </div>
                  </div>
               </div>
            @endif

            @if(count($member->education_array) > 0)
               <div class="col-lg-4 col-md-6 col-12">
                  <div class="tp-team-dtls-item mb-50 mt-35 wow fadeInUp" data-wow-delay=".4s">
                     <h4 class="tp-team-dtls-item__title mb-25">Education & Certifications</h4>
                     <p class="text-muted mb-25">Academic qualifications and certified proficiencies:</p>
                     <div class="tp-team-dtls-item__list">
                        <ul class="list-unstyled">
                           @foreach($member->education_array as $edu)
                              <li><i class="fa-solid fa-check"></i> {{ $edu }}</li>
                           @endforeach
                        </ul>
                     </div>
                  </div>
               </div>
            @endif

            @if(count($member->awards_array) > 0)
               <div class="col-lg-4 col-md-6 col-12">
                  <div class="tp-team-dtls-item mb-50 mt-35 wow fadeInUp" data-wow-delay=".6s">
                     <h4 class="tp-team-dtls-item__title mb-25">Honors & Industry Awards</h4>
                     <p class="text-muted mb-25">Industry recognitions and project excellence:</p>
                     <div class="tp-team-dtls-item__list">
                        <ul class="list-unstyled">
                           @foreach($member->awards_array as $award)
                              <li><i class="fa-solid fa-check"></i> {{ $award }}</li>
                           @endforeach
                        </ul>
                     </div>
                  </div>
               </div>
            @endif
         </div>
      </div>
   </section>
   <!-- team-details-area-end -->

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
                     <div class="alert alert-success alert-dismissible fade show rounded-3 p-3 mb-4" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                           <input class="form-control py-3 px-3 rounded-2" type="text" name="subject" value="Consultation for: {{ $member->name }}" placeholder="Equipment inquiry / Organization" style="border-color: #E2E8F0; font-size: 15px;">
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
