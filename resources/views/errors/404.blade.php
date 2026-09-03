@extends('layouts.app')

@section('title', '404 - Page Not Found | ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))

@section('content')

      <!-- main-area -->
      <main>

         <!-- 404 Error Section -->
         <section class="error-area pt-100 pb-120 text-center position-relative fix" style="background-color: #FAFCFF; min-height: calc(100vh - 450px); display: flex; align-items: center;">
            <div class="container">
               <div class="row justify-content-center">
                  <div class="col-xl-8 col-lg-10 col-12">
                     <div class="error-content text-center py-4">
                        <div class="error-badge-wrap mb-30 d-inline-block">
                           <span class="d-inline-flex align-items-center justify-content-center" style="width: 110px; height: 110px; border-radius: 50%; background: rgba(14, 99, 255, 0.08); color: #0E63FF; font-size: 48px;">
                              <i class="fa-solid fa-triangle-exclamation"></i>
                           </span>
                        </div>
                        
                        <h1 class="error-code mb-20 fw-bold" style="font-size: clamp(80px, 12vw, 150px); line-height: 1; color: #0A192F; font-family: 'Inter', sans-serif; letter-spacing: -2px;">
                           4<span style="color: #0E63FF;">0</span>4
                        </h1>
                        
                        <h2 class="error-title mb-20 fw-bold text-dark" style="font-size: 32px;">
                           Oops! Page Not Found
                        </h2>
                        
                        <p class="error-desc mb-40 text-muted mx-auto" style="max-width: 580px; font-size: 16px; line-height: 1.7;">
                           The page you are looking for might have been removed, had its name changed, or is temporarily unavailable. Please verify the URL or return to our homepage.
                        </p>
                        
                        <div class="error-btn-group d-flex flex-wrap align-items-center justify-content-center gap-3">
                           <a href="{{ url('/') }}" class="tp-btn" style="padding: 16px 36px; border-radius: 8px; font-weight: 600;">
                              <i class="fa-solid fa-house me-2"></i> Back To Homepage
                           </a>
                           <a href="{{ url('/contact') }}" class="tp-btn" style="padding: 16px 36px; border-radius: 8px; font-weight: 600; background: #0A192F; color: #fff;">
                              <i class="fa-solid fa-headset me-2"></i> Contact Support
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- 404 Error Section End -->

      </main>
      <!-- main-area-end -->

@endsection
