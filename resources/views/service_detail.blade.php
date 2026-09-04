@extends('layouts.app')

@section('title', $service->title . ' | Medical Equipment & Services - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($service->short_description ?: $service->description), 155))
@section('canonical_url', route('service.detail', $service->slug))
@section('og_title', $service->title . ' | ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('og_description', \Illuminate\Support\Str::limit(strip_tags($service->short_description ?: $service->description), 155))
@section('og_image', $service->image ? asset($service->image) : \App\Helpers\SeoHelper::ogImage())
@section('og_type', 'article')

@section('schema_markup')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "MedicalBusiness",
      "@id": "{{ url('/') }}/#organization",
      "name": "{{ \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD') }}",
      "url": "{{ url('/') }}",
      "logo": "{{ asset(\App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png')) }}",
      "telephone": "{{ \App\Models\Setting::get('helpdesk_phone', '+92 331 6699992') }}"
    },
    {
      "@type": "Service",
      "name": "{{ addslashes($service->title) }}",
      "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($service->short_description ?: $service->description), 200)) }}",
      "provider": {
        "@type": "MedicalBusiness",
        "name": "{{ \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD') }}"
      },
      "url": "{{ route('service.detail', $service->slug) }}",
      "areaServed": {
        "@type": "Country",
        "name": "Pakistan"
      }
    }
  ]
}
</script>
@endsection

@section('content')

      <!-- main-area -->
      <main>

         <!-- breadcrumb-area -->
         <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset($service->banner_image ?: \App\Models\Setting::get('service_detail_banner_image', \App\Models\Setting::get('services_banner_image', 'assets/img/banner/breadcrumb-01.jpg'))) }}">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-xl-7 col-lg-7 col-md-12 col-12">
                     <div class="tp-breadcrumb">
                        <h2 class="tp-breadcrumb__title">{{ $service->title }}</h2>
                        @if($service->banner_subtitle)
                           <p class="text-white opacity-75 mt-2 mb-0 fs-6">{{ $service->banner_subtitle }}</p>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                     <div class="tp-breadcrumb__link serv-md d-flex justify-content-lg-end">
                        <span>Innotech : <a href="{{ url('/#services-section') }}"> {{ $service->category ?: 'Medical Services' }}</a></span>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!-- breadcrumb-area-end -->

         <!-- services-details-area -->
         <section class="services-details pt-130 pb-100">
            <div class="container">

               <!-- Dual Showcase Images -->
               <div class="row mb-30">
                  <div class="col-lg-6 col-md-12 mb-30">
                     <div class="services-thumb-img wow fadeInLeft" data-wow-delay=".3s">
                        <img src="{{ asset($service->image ?: 'assets/img/services/services-thumb-07.jpg') }}" alt="{{ $service->title }}" class="w-100 rounded" style="max-height: 420px; object-fit: cover;">
                     </div>
                  </div>
                  <div class="col-lg-6 col-md-12 mb-30">
                     <div class="services-thumb-img wow fadeInRight" data-wow-delay=".3s">
                        <img src="{{ asset($service->image_2 ?: 'assets/img/services/services-thumb-08.jpg') }}" alt="{{ $service->title }} showcase" class="w-100 rounded" style="max-height: 420px; object-fit: cover;">
                     </div>
                  </div>
               </div>

               <!-- Overview & Detailed Specs -->
               <div class="row">
                  <div class="col-lg-12">
                     <div class="tp-srv-process mb-50">
                        <h4 class="tp-srv-process__title mb-25">{{ $service->process_title ?: ($service->title . ' - Overview & Specifications') }}</h4>
                        
                        @if($service->short_description)
                           <p class="mb-20 fs-5 text-dark fw-medium">{{ $service->short_description }}</p>
                        @endif

                        @if($service->description)
                           <p class="mb-35 text-secondary" style="line-height: 1.8;">{{ $service->description }}</p>
                        @endif

                        @if(!empty($service->features))
                           <div class="tp-srv-process__list mb-40">
                              <ul>
                                 @foreach(preg_split('/\r\n|\r|\n/', $service->features) as $feature)
                                    @if(trim($feature))
                                       <li><i class="fa-solid fa-check text-primary"></i> {{ trim($feature) }}</li>
                                    @endif
                                 @endforeach
                              </ul>
                           </div>
                        @endif
                     </div>
                  </div>
               </div>

               <!-- 4 Simple Steps / Implementation Workflow -->
               @if($service->steps_title || $service->steps_description || $service->step_1_points)
                  <div class="row">
                     <div class="col-lg-12">
                        <div class="tp-srv-stap mb-40">
                           <h4 class="tp-srv-stap__title mb-20">{{ $service->steps_title ?: '4 Simple Steps to Implementation' }}</h4>
                           @if($service->steps_description)
                              <p class="text-secondary" style="line-height: 1.8;">{{ $service->steps_description }}</p>
                           @endif
                        </div>
                     </div>

                     <!-- Step 1 -->
                     @if($service->step_1_title || $service->step_1_points)
                        <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                           <div class="tp-srv-stap__list p-4 rounded bg-white shadow-sm border h-100 wow fadeInUp" data-wow-delay=".2s">
                              <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-circle-check me-2"></i>{{ $service->step_1_title ?: 'Step 01' }}</h5>
                              <ul class="list-unstyled mb-0">
                                 @foreach(preg_split('/\r\n|\r|\n/', $service->step_1_points ?: '') as $pt)
                                    @if(trim($pt))
                                       <li class="mb-2"><i class="fa-solid fa-check text-primary me-2"></i>{{ trim($pt) }}</li>
                                    @endif
                                 @endforeach
                              </ul>
                           </div>
                        </div>
                     @endif

                     <!-- Step 2 -->
                     @if($service->step_2_title || $service->step_2_points)
                        <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                           <div class="tp-srv-stap__list p-4 rounded bg-white shadow-sm border h-100 wow fadeInUp" data-wow-delay=".4s">
                              <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-circle-check me-2"></i>{{ $service->step_2_title ?: 'Step 02' }}</h5>
                              <ul class="list-unstyled mb-0">
                                 @foreach(preg_split('/\r\n|\r|\n/', $service->step_2_points ?: '') as $pt)
                                    @if(trim($pt))
                                       <li class="mb-2"><i class="fa-solid fa-check text-primary me-2"></i>{{ trim($pt) }}</li>
                                    @endif
                                 @endforeach
                              </ul>
                           </div>
                        </div>
                     @endif

                     <!-- Step 3 -->
                     @if($service->step_3_title || $service->step_3_points)
                        <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                           <div class="tp-srv-stap__list p-4 rounded bg-white shadow-sm border h-100 wow fadeInUp" data-wow-delay=".6s">
                              <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-circle-check me-2"></i>{{ $service->step_3_title ?: 'Step 03' }}</h5>
                              <ul class="list-unstyled mb-0">
                                 @foreach(preg_split('/\r\n|\r|\n/', $service->step_3_points ?: '') as $pt)
                                    @if(trim($pt))
                                       <li class="mb-2"><i class="fa-solid fa-check text-primary me-2"></i>{{ trim($pt) }}</li>
                                    @endif
                                 @endforeach
                              </ul>
                           </div>
                        </div>
                     @endif

                     <!-- Step 4 -->
                     @if($service->step_4_title || $service->step_4_points)
                        <div class="col-xl-3 col-lg-6 col-md-6 mb-30">
                           <div class="tp-srv-stap__list p-4 rounded bg-white shadow-sm border h-100 wow fadeInUp" data-wow-delay=".8s">
                              <h5 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-circle-check me-2"></i>{{ $service->step_4_title ?: 'Step 04' }}</h5>
                              <ul class="list-unstyled mb-0">
                                 @foreach(preg_split('/\r\n|\r|\n/', $service->step_4_points ?: '') as $pt)
                                    @if(trim($pt))
                                       <li class="mb-2"><i class="fa-solid fa-check text-primary me-2"></i>{{ trim($pt) }}</li>
                                    @endif
                                 @endforeach
                              </ul>
                           </div>
                        </div>
                     @endif
                  </div>
               @endif

               <!-- Research / Clinical Verification & Bottom CTA -->
               @if($service->research_title || $service->research_description || $service->research_image)
                  <div class="row mt-40">
                     <div class="col-lg-12">
                        <div class="tp-srv-research mb-35">
                           <h4 class="tp-srv-research__title mb-20">{{ $service->research_title ?: 'Our Research & Clinical Verification' }}</h4>
                           @if($service->research_description)
                              <p class="text-secondary" style="line-height: 1.8;">{{ $service->research_description }}</p>
                           @endif
                        </div>
                     </div>
                  </div>

                  <div class="row">
                     <div class="col-md-12">
                        <div class="tp-srv-bg mb-40">
                           <img src="{{ asset($service->research_image ?: 'assets/img/services/services-thumb-09.jpg') }}" alt="{{ $service->title }} research" class="w-100 rounded shadow-sm" style="max-height: 380px; object-fit: cover;">
                        </div>
                        <div class="services-link tp-srv-link mb-50">  
                           <span>Inquire or Request Official Quotation : <a href="{{ url($service->bottom_link_url ?: '/contact') }}">{{ $service->bottom_link_text ?: 'Inquire About This Service' }} <i class="fa-solid fa-arrow-right"></i></a></span>
                        </div>
                     </div>
                  </div>
               @endif

            </div>
         </section>
         <!-- services-details-area-end -->

         <!-- Direct Support & Inquiry Section -->
         <section class="support-area grey-bg pt-90 pb-100">
            <div class="container">
               <div class="row text-center">
                  <div class="col-lg-12">
                     <div class="tp-section">
                        <span class="tp-section__sub-title left-line right-line mb-15">Get In Touch</span>
                        <h3 class="tp-section__title mb-50">Request Information for {{ $service->title }}</h3>
                     </div>
                  </div>
               </div>

               @if(session('success'))
                  <div class="row justify-content-center mb-30">
                     <div class="col-xl-8 col-lg-10">
                        <div class="alert alert-success text-center py-3">
                           <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                        </div>
                     </div>
                  </div>
               @endif

                 <div class="row justify-content-center">
                    <div class="col-xl-9 col-lg-10 col-md-12">
                       <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border-0 text-start">
                          <span class="tp-section__sub-title left-line mb-10 text-primary fw-bold" style="font-size: 13px; letter-spacing: 1px; text-transform: uppercase;">SEND US AN INQUIRY</span>
                          <h3 class="tp-section__title mb-15" style="font-size: 28px;">Ready to Upgrade Your Hospital or Laboratory?</h3>
                          <p class="text-muted mb-35" style="font-size: 15px; line-height: 24px;">Leave your project requirements, equipment inquiries, or technical support requests below. Our biomedical specialists will assist you immediately.</p>

                           <form class="ajax-contact-form" action="{{ route('contact.store') }}" method="POST">
                              @csrf
                              <input type="hidden" name="service_interested" value="{{ $service->title }}">
                              <div class="row">
                                 <div class="col-md-6 mb-20">
                                    <input class="form-control py-3 px-3 rounded-2" type="text" name="name" placeholder="Enter your full name" required style="border-color: #E2E8F0; font-size: 15px;">
                                 </div>
                                 <div class="col-md-6 mb-20">
                                    <input class="form-control py-3 px-3 rounded-2" type="email" name="email" placeholder="Enter your email" required style="border-color: #E2E8F0; font-size: 15px;">
                                 </div>
                                 <div class="col-md-6 mb-20">
                                    <input class="form-control py-3 px-3 rounded-2" type="text" name="phone" placeholder="Enter phone / mobile number" style="border-color: #E2E8F0; font-size: 15px;">
                                  </div>
                                 <div class="col-md-6 mb-20">
                                    <input class="form-control py-3 px-3 rounded-2" type="text" name="subject" value="Inquiry: {{ $service->title }}" placeholder="Equipment inquiry / Organization" style="border-color: #E2E8F0; font-size: 15px;">
                                 </div>
                                 <div class="col-12 mb-25">
                                    <textarea class="form-control py-3 px-3 rounded-2" name="message" rows="5" placeholder="How can our biomedical team assist your facility?" required style="border-color: #E2E8F0; font-size: 15px;"></textarea>
                                 </div>
                                 <div class="col-12 text-start">
                                    <button type="submit" class="tp-btn-theme" style="padding: 14px 34px; font-size: 14px; font-weight: 700; letter-spacing: 0.5px; border-radius: 8px;">
                                       <span style="display: inline-block; margin-bottom: 0; font-size: 14px;">SEND MESSAGE</span>
                                       <i class="fa-solid fa-paper-plane ms-2"></i>
                                    </button>
                                    <div class="ajax-response mt-3 mb-0" style="display: none;"></div>
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
      <!-- main-area-end -->

@endsection
