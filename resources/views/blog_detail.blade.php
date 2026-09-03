@extends('layouts.app')

@section('title', $blog->title . ' | Clinical Research & Insights - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($blog->summary ?: $blog->content), 155))
@section('meta_keywords', $blog->tags ?: 'medical equipment, clinical laboratory, biomedical engineering, ICU monitoring')
@section('meta_author', $blog->author ?: 'Innotech Medical Team')
@section('canonical_url', route('blog.detail', $blog->slug))
@section('og_title', $blog->title)
@section('og_description', \Illuminate\Support\Str::limit(strip_tags($blog->summary ?: $blog->content), 155))
@section('og_image', $blog->image ? asset($blog->image) : \App\Helpers\SeoHelper::ogImage())
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
      "logo": "{{ asset(\App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png')) }}"
    },
    {
      "@type": "MedicalWebPage",
      "@id": "{{ route('blog.detail', $blog->slug) }}/#webpage",
      "url": "{{ route('blog.detail', $blog->slug) }}",
      "name": "{{ addslashes($blog->title) }}",
      "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($blog->summary ?: $blog->content), 160)) }}"
    },
    {
      "@type": "BlogPosting",
      "@id": "{{ route('blog.detail', $blog->slug) }}/#article",
      "isPartOf": {
        "@id": "{{ route('blog.detail', $blog->slug) }}/#webpage"
      },
      "headline": "{{ addslashes($blog->title) }}",
      "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($blog->summary ?: $blog->content), 160)) }}",
      "datePublished": "{{ $blog->published_at ? $blog->published_at->toAtomString() : $blog->created_at->toAtomString() }}",
      "dateModified": "{{ $blog->updated_at->toAtomString() }}",
      "author": {
        "@type": "Person",
        "name": "{{ addslashes($blog->author ?: 'Innotech Clinical Specialist') }}"
      },
      "publisher": {
        "@type": "Organization",
        "name": "{{ \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD') }}",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ asset(\App\Models\Setting::get('logo_path', 'assets/img/logo/logo.png')) }}"
        }
      },
      "image": "{{ $blog->image ? asset($blog->image) : \App\Helpers\SeoHelper::ogImage() }}",
      "keywords": "{{ $blog->tags }}"
    }
  ]
}
</script>
@endsection

@section('content')
<main>

   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('blog_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-xl-8 col-lg-8 col-md-12 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title">{{ Str::limit($blog->title, 65) }}</h2>
               </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12 col-12">
               <div class="tp-breadcrumb__link d-flex align-items-center justify-content-lg-end">
                  <span>Innotech : <a href="{{ route('blog') }}">Blog</a> &nbsp;/&nbsp; {{ $blog->category }}</span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- 2. POSTBOX / ARTICLE DETAILS AREA -->
   <div class="postbox__area pt-120 pb-100 wow fadeInUp" data-wow-duration=".8s" data-wow-delay=".2s">
      <div class="container">
         <div class="row">
            <div class="col-xxl-8 col-xl-8 col-lg-7 col-md-12">
               <div class="postbox__wrapper pr-20">
                  <article class="postbox__item format-image mb-50 transition-3">

                     <!-- Featured Media (Video / Slider / Image) -->
                     @if(!empty($blog->video_url))
                        <div class="postbox__thumb postbox__video p-relative w-img mb-35">
                           <img src="{{ asset($blog->image ?: 'assets/img/blog/blog-in-01.jpg') }}" alt="{{ $blog->title }}" class="rounded" style="max-height: 460px; width: 100%; object-fit: cover;">
                           <a href="{{ $blog->video_url }}" class="play-btn popup-video" target="_blank"><i class="fas fa-play"></i></a>
                        </div>
                     @elseif(!empty($blog->slider_images) && is_array($blog->slider_images) && count($blog->slider_images) > 1)
                        <div class="postbox__thumb postbox-active swiper-container w-img p-relative mb-35">
                           <div class="swiper-wrapper">
                              @if($blog->image)
                                 <div class="postbox__slider-item swiper-slide">
                                    <img src="{{ asset($blog->image) }}" alt="{{ $blog->title }}" class="rounded" style="max-height: 460px; width: 100%; object-fit: cover;">
                                 </div>
                              @endif
                              @foreach($blog->slider_images as $sImg)
                                 <div class="postbox__slider-item swiper-slide">
                                    <img src="{{ asset($sImg) }}" alt="{{ $blog->title }}" class="rounded" style="max-height: 460px; width: 100%; object-fit: cover;">
                                 </div>
                              @endforeach
                           </div>
                           <div class="postbox-nav">
                              <button class="postbox-slider-button-next"><i class="fa-solid fa-chevron-right"></i></button>
                              <button class="postbox-slider-button-prev"><i class="fa-solid fa-chevron-left"></i></button>
                           </div>
                        </div>
                     @else
                        <div class="postbox__thumb w-img mb-35">
                           <img src="{{ asset($blog->image ?: 'assets/img/blog/blog-in-01.jpg') }}" alt="{{ $blog->title }}" class="rounded" style="max-height: 460px; width: 100%; object-fit: cover;">
                        </div>
                     @endif

                     <div class="postbox__content">
                        <!-- Meta Bar -->
                        <div class="postbox__meta mb-40 d-flex flex-wrap gap-3 align-items-center">
                           <span><i class="fa-regular fa-user text-primary me-1"></i> {{ $blog->author }}</span>
                           <span><i class="fa-regular fa-clock text-primary me-1"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : date('M d, Y') }}</span>
                           <span><a href="{{ route('blog', ['category' => $blog->category]) }}"><i class="fa-solid fa-folder text-primary me-1"></i> {{ $blog->category }}</a></span>
                           <span><i class="fa-light fa-eye text-primary me-1"></i> {{ number_format($blog->views) }} views</span>
                           <span><i class="fa-regular fa-message-dots text-primary me-1"></i> ({{ sprintf('%02d', $blog->approvedComments->count()) }}) Comments</span>
                           <span><i class="fa-regular fa-book-open text-primary me-1"></i> {{ $blog->reading_time }}</span>
                        </div>

                        <!-- Title -->
                        <h3 class="postbox__title mb-30" style="line-height: 1.35;">
                           {{ $blog->title }}
                        </h3>

                        <!-- Abstract / Summary Lead -->
                        @if($blog->summary)
                           <div class="p-3 mb-35 rounded-3 bg-light border-start border-4 border-primary">
                              <p class="lead text-secondary mb-0" style="font-size: 1.1rem; line-height: 1.7;">
                                 {{ $blog->summary }}
                              </p>
                           </div>
                        @endif

                        <!-- Main Article Body -->
                        <div class="postbox__text mb-40" style="line-height: 1.85; color: #334155; font-size: 1.05rem;">
                           {!! nl2br(e($blog->content)) !!}
                        </div>

                        <!-- Blockquote (if available) -->
                        @if($blog->quote)
                           <blockquote class="postbox__quote mb-40 p-4 rounded-3 border-start border-4 border-primary bg-light">
                              <p class="fst-italic mb-2 fs-5" style="color: #1e293b;">
                                 <i class="fa-solid fa-quote-left text-primary opacity-50 me-2"></i>{{ $blog->quote }}<i class="fa-solid fa-quote-right text-primary opacity-50 ms-2"></i>
                              </p>
                              @if($blog->quote_author)
                                 <cite class="text-primary fw-bold">— {{ $blog->quote_author }}</cite>
                              @endif
                           </blockquote>
                        @endif

                        <!-- Approach / Extended Section (if set) -->
                        @if($blog->approach_title || $blog->approach_text || !empty($blog->approach_points_array))
                           <div class="row align-items-center mb-45 pt-20 border-top">
                              <div class="col-lg-{{ $blog->meta_image_1 ? '7' : '12' }} col-md-12">
                                 <div class="postbox__content-area mb-30">
                                    <h4 class="mb-20">{{ $blog->approach_title ?: 'Our Clinical Approach & Methodology' }}</h4>
                                    @if($blog->approach_text)
                                       <p class="text-secondary mb-20" style="line-height: 1.7;">{{ $blog->approach_text }}</p>
                                    @endif
                                    @if(!empty($blog->approach_points_array))
                                       <div class="postbox__text-list">
                                          <ul class="list-unstyled">
                                             @foreach($blog->approach_points_array as $pt)
                                                <li class="mb-2"><i class="fa-solid fa-circle-check text-success me-2"></i>{{ $pt }}</li>
                                             @endforeach
                                          </ul>
                                       </div>
                                    @endif
                                 </div>
                              </div>
                              @if($blog->meta_image_1)
                                 <div class="col-lg-5 col-md-12">
                                    <div class="postbox__meta-img w-img mb-30">
                                       <img src="{{ asset($blog->meta_image_1) }}" alt="Approach" class="rounded shadow-sm w-100" style="max-height: 300px; object-fit: cover;">
                                    </div>
                                 </div>
                              @endif
                           </div>
                        @endif

                        <!-- Meta Sub-Image 2 (if set) -->
                        @if($blog->meta_image_2)
                           <div class="row mb-40">
                              <div class="col-12">
                                 <img src="{{ asset($blog->meta_image_2) }}" alt="Details" class="rounded shadow-sm w-100" style="max-height: 380px; object-fit: cover;">
                              </div>
                           </div>
                        @endif

                        <!-- Tags and Social Share Bar -->
                        <div class="postbox__tag-border pt-30 pb-30 border-top border-bottom mb-50">
                           <div class="row align-items-center g-3">
                              <!-- Article Tags (Left) -->
                              <div class="col-lg-7 col-12">
                                 <div class="postbox__tag">
                                    <div class="postbox__tag-list tagcloud d-flex align-items-center flex-wrap gap-2">
                                       <span class="fw-bold text-dark me-1"><i class="fa-solid fa-tags text-primary me-1"></i> Tags:</span>
                                       @forelse($blog->tags_array as $t)
                                          <a href="{{ route('blog', ['tag' => $t]) }}" class="px-3 py-1 rounded-pill">{{ $t }}</a>
                                       @empty
                                          <a href="{{ route('blog', ['category' => $blog->category]) }}" class="px-3 py-1 rounded-pill">{{ $blog->category }}</a>
                                          <a href="{{ route('blog') }}" class="px-3 py-1 rounded-pill">Medical News</a>
                                       @endforelse
                                    </div>
                                 </div>
                              </div>

                              <!-- One-Click Social Share (Right on desktop, Left on mobile) -->
                              <div class="col-lg-5 col-12">
                                 <div class="postbox__social-tag d-flex align-items-center justify-content-lg-end justify-content-start flex-nowrap gap-2">
                                    <span class="share-label fw-bold text-dark me-1"><i class="fa-solid fa-share-nodes text-primary me-1"></i> Share:</span>
                                    <a class="social-share-btn share-linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" title="Share on LinkedIn">
                                       <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <a class="social-share-btn share-facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" title="Share on Facebook">
                                       <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                    <a class="social-share-btn share-twitter" href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(request()->fullUrl()) }}" target="_blank" title="Share on Twitter">
                                       <i class="fa-brands fa-twitter"></i>
                                    </a>
                                    <a class="social-share-btn share-whatsapp" href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . request()->fullUrl()) }}" target="_blank" title="Share on WhatsApp">
                                       <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>

                     </div>
                  </article>

                  <!-- PREVIOUS & NEXT ARTICLE NAVIGATION -->
                  <div class="blog-navigation mb-60 p-4 bg-white rounded-3 shadow-sm border">
                     <div class="row align-items-center g-3">
                        <div class="col-md-6 border-end-md">
                           @if($prevBlog)
                              <a href="{{ route('blog.detail', $prevBlog->slug) }}" class="d-flex align-items-center text-decoration-none group-hover">
                                 <div class="me-3 fs-4 text-primary">
                                    <i class="fa-solid fa-arrow-left"></i>
                                 </div>
                                 <div>
                                    <span class="small text-muted text-uppercase fw-semibold d-block">Previous Article</span>
                                    <strong class="text-dark hover-primary">{{ Str::limit($prevBlog->title, 42) }}</strong>
                                 </div>
                              </a>
                           @else
                              <div class="text-muted small">
                                 <i class="fa-regular fa-circle-info me-1"></i> You are viewing the earliest article.
                              </div>
                           @endif
                        </div>
                        <div class="col-md-6 text-md-end">
                           @if($nextBlog)
                              <a href="{{ route('blog.detail', $nextBlog->slug) }}" class="d-flex align-items-center justify-content-md-end text-decoration-none group-hover">
                                 <div class="text-md-end me-3">
                                    <span class="small text-muted text-uppercase fw-semibold d-block">Next Article</span>
                                    <strong class="text-dark hover-primary">{{ Str::limit($nextBlog->title, 42) }}</strong>
                                 </div>
                                 <div class="fs-4 text-primary">
                                    <i class="fa-solid fa-arrow-right"></i>
                                 </div>
                              </a>
                           @else
                              <div class="text-muted small">
                                 <i class="fa-regular fa-circle-info me-1"></i> You are viewing the latest article.
                              </div>
                           @endif
                        </div>
                     </div>
                  </div>

                  <!-- APPROVED COMMENTS LIST (ONLY SHOWS ADMIN APPROVED COMMENTS) -->
                  <div class="postbox__comment mb-60 bg-white p-4 p-md-5 rounded-3 shadow-sm border">
                     <div class="d-flex justify-content-between align-items-center mb-35 pb-2 border-bottom">
                        <h3 class="postbox__comment-title mb-0" style="font-size: 1.35rem;">
                           ({{ sprintf('%02d', $blog->approvedComments->count()) }}) {{ Str::plural('Comment', $blog->approvedComments->count()) }}
                        </h3>
                        <span class="badge bg-light text-primary border"><i class="fa-solid fa-shield-check me-1"></i> Admin Moderated</span>
                     </div>

                     @if($blog->approvedComments->count() > 0)
                        <ul class="list-unstyled mb-0">
                           @foreach($blog->approvedComments as $c)
                              <li class="mb-30 pb-30 {{ !$loop->last ? 'border-bottom' : '' }}">
                                 <div class="postbox__comment-box d-flex">
                                    <div class="postbox__comment-info me-3">
                                       <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 50px; height: 50px; background: linear-gradient(135deg, #0E63FF, #002244); font-size: 18px; flex-shrink: 0;">
                                          {{ strtoupper(substr($c->name, 0, 1)) }}
                                       </div>
                                    </div>
                                    <div class="postbox__comment-text flex-grow-1">
                                       <div class="postbox__comment-name d-flex justify-content-between align-items-center mb-2">
                                          <h5 class="mb-0 fw-bold text-dark" style="font-size: 1rem;">{{ $c->name }}</h5>
                                          <span class="post-meta text-muted small text-uppercase"><i class="fa-regular fa-clock me-1"></i> {{ $c->created_at->format('F d, Y') }}</span>
                                       </div>
                                       <p class="text-secondary mb-0" style="line-height: 1.65; font-size: 0.95rem;">{{ $c->comment }}</p>
                                    </div>
                                 </div>
                              </li>
                           @endforeach
                        </ul>
                     @else
                        <div class="text-center py-4 text-muted">
                           <i class="fa-light fa-comments fa-3x mb-2 d-block text-secondary opacity-50"></i>
                           <p class="mb-1 fw-semibold text-dark">No comments published yet.</p>
                           <small>Be the first to share your thoughts below! (Note: Comments are moderated and will appear once approved by an administrator).</small>
                        </div>
                     @endif
                  </div>

                  <!-- LEAVE A COMMENT / INQUIRY FORM -->
                  <div class="postbox__comment-form bg-white p-4 p-md-5 rounded-3 shadow-sm border mb-50">
                     <h3 class="postbox__comment-form-title mb-10">Leave a Reply / Inquire</h3>
                     <p class="text-muted small mb-30">Have a question or insight about this article? Send us your comment. Once reviewed by our team, it will appear here.</p>

                     <div id="commentAlertBox">
                        @if(session('comment_success'))
                           <div class="alert alert-success d-flex align-items-center mb-4 p-3 rounded-3 shadow-sm border border-success">
                              <i class="fa-solid fa-circle-check fs-4 text-success me-2"></i>
                              <div>{{ session('comment_success') }}</div>
                           </div>
                        @endif
                     </div>

                     <form id="blogCommentForm" action="{{ route('blog.comment', $blog->slug) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                           <div class="col-md-6">
                              <div class="postbox__comment-input">
                                 <input type="text" name="name" placeholder="Your Full Name *" required>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="postbox__comment-input">
                                 <input type="email" name="email" placeholder="Your Email Address *" required>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="postbox__comment-input">
                                 <input type="text" name="phone" placeholder="Your Phone Number (Optional)">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="postbox__comment-input">
                                 <input type="text" name="website" placeholder="Organization / Website (Optional)">
                              </div>
                           </div>
                           <div class="col-12">
                              <div class="postbox__comment-input">
                                 <textarea name="comment" rows="4" placeholder="Write your comment or question here..." required></textarea>
                              </div>
                           </div>
                           <div class="col-12">
                              <div class="postbox__comment-btn">
                                 <button type="submit" id="commentSubmitBtn" class="tp-btn">
                                    <span class="btn-text">Post Comment <i class="fa-solid fa-paper-plane ms-1"></i></span>
                                    <span class="btn-loading d-none"><i class="fa-solid fa-spinner fa-spin me-2"></i>Submitting...</span>
                                 </button>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>

               </div>
            </div>

            <!-- 3. SIDEBAR AREA -->
            <div class="col-xxl-4 col-xl-4 col-lg-5 col-md-12">
               <div class="sidebar__wrapper pl-25 pb-50">

                  <!-- Search Widget -->
                  <div class="sidebar__widget mb-45">
                     <div class="sidebar__widget-content">
                        <h3 class="sidebar__widget-title mb-25">Search</h3>
                        <div class="sidebar__search">
                           <form action="{{ route('blog') }}" method="GET">
                              <div class="sidebar__search-input-2 p-relative">
                                 <input type="text" name="search" placeholder="Search post..." autocomplete="off">
                                 <button type="submit"><i class="far fa-search"></i></button>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>

                  <!-- Category Widget with Counts -->
                  <div class="sidebar__widget mb-40">
                     <h3 class="sidebar__widget-title mb-25">Category</h3>
                     <div class="sidebar__widget-content">
                        <ul>
                           @foreach($categories as $cat)
                              <li>
                                 <a href="{{ route('blog', ['category' => $cat->category]) }}" class="{{ ($blog->category == $cat->category) ? 'text-primary fw-bold' : '' }}">
                                    {{ $cat->category }} <span>{{ sprintf('%02d', $cat->count) }}</span>
                                 </a>
                              </li>
                           @endforeach
                        </ul>
                     </div>
                  </div>

                  <!-- Recent Posts Widget -->
                  <div class="sidebar__widget mb-55">
                     <h3 class="sidebar__widget-title mb-25">Recent Post</h3>
                     <div class="sidebar__widget-content">
                        <div class="sidebar__post rc__post">
                           @foreach($recentBlogs as $rb)
                              <div class="rc__post mb-20 d-flex align-items-center">
                                 <div class="rc__post-thumb">
                                    <a href="{{ route('blog.detail', $rb->slug) }}">
                                       <img src="{{ asset($rb->image ?: 'assets/img/blog/blog-thumb-01.jpg') }}" alt="{{ $rb->title }}" style="width: 75px; height: 75px; object-fit: cover; border-radius: 6px;">
                                    </a>
                                 </div>
                                 <div class="rc__post-content">
                                    <div class="rc__meta">
                                       <span>{{ $rb->published_at ? $rb->published_at->format('d M. Y') : date('d M. Y') }}</span>
                                    </div>
                                    <h3 class="rc__post-title">
                                       <a href="{{ route('blog.detail', $rb->slug) }}">{{ Str::limit($rb->title, 48) }}</a>
                                    </h3>
                                 </div>
                              </div>
                           @endforeach
                        </div>
                     </div>
                  </div>

                  <!-- Popular Tags Cloud -->
                  <div class="sidebar__widget mb-55">
                     <h3 class="sidebar__widget-title mb-25">Popular Tag</h3>
                     <div class="sidebar__widget-content">
                        <div class="tagcloud">
                           @foreach($popularTags as $tag)
                              <a href="{{ route('blog', ['tag' => $tag]) }}">
                                 {{ $tag }}
                              </a>
                           @endforeach
                        </div>
                     </div>
                  </div>

               </div>
            </div>

         </div>
      </div>
   </div>
   <!-- postbox area end -->

</main>

<style>
.hover-primary:hover {
   color: #0E63FF !important;
}
.postbox__social-tag a.social-share-btn {
   margin-right: 0 !important;
   width: 38px !important;
   height: 38px !important;
   display: inline-flex !important;
   align-items: center !important;
   justify-content: center !important;
   border-radius: 50% !important;
   font-size: 15px !important;
   transition: all 0.25s ease !important;
   text-decoration: none !important;
   border: 1px solid #e2e8f0 !important;
   background: #ffffff !important;
   color: #64748b !important;
   flex-shrink: 0 !important;
}
.postbox__social-tag a.social-share-btn.share-linkedin:hover {
   background: #0A66C2 !important;
   border-color: #0A66C2 !important;
   color: #ffffff !important;
   transform: translateY(-2px);
   box-shadow: 0 4px 10px rgba(10, 102, 194, 0.3);
}
.postbox__social-tag a.social-share-btn.share-facebook:hover {
   background: #1877F2 !important;
   border-color: #1877F2 !important;
   color: #ffffff !important;
   transform: translateY(-2px);
   box-shadow: 0 4px 10px rgba(24, 119, 242, 0.3);
}
.postbox__social-tag a.social-share-btn.share-twitter:hover {
   background: #1DA1F2 !important;
   border-color: #1DA1F2 !important;
   color: #ffffff !important;
   transform: translateY(-2px);
   box-shadow: 0 4px 10px rgba(29, 161, 242, 0.3);
}
.postbox__social-tag a.social-share-btn.share-whatsapp:hover {
   background: #25D366 !important;
   border-color: #25D366 !important;
   color: #ffffff !important;
   transform: translateY(-2px);
   box-shadow: 0 4px 10px rgba(37, 211, 102, 0.3);
}
.postbox__social-tag span.share-label {
   font-size: 16px !important;
   margin-right: 8px !important;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('blogCommentForm');
    if (!commentForm) return;

    const alertBox = document.getElementById('commentAlertBox');
    const submitBtn = document.getElementById('commentSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // UI Loading state
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        alertBox.innerHTML = '';

        const formData = new FormData(commentForm);

        fetch(commentForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                let errorMsg = 'Failed to submit comment. Please check your inputs.';
                if (data.errors) {
                    errorMsg = Object.values(data.errors).flat().join('<br>');
                } else if (data.message) {
                    errorMsg = data.message;
                }
                throw new Error(errorMsg);
            }
            return data;
        })
        .then(data => {
            commentForm.reset();
            alertBox.innerHTML = `
                <div class="alert alert-success d-flex align-items-center mb-4 p-3 rounded-3 shadow-sm border border-success fade show">
                    <i class="fa-solid fa-circle-check fs-3 text-success me-3"></i>
                    <div>
                        <strong class="d-block text-success mb-1" style="font-size: 1.05rem;">Comment Received Successfully!</strong>
                        <span style="font-size: 0.95rem;">${data.message || 'Thank you! Your comment has been submitted and will appear on the website once approved by an administrator.'}</span>
                    </div>
                </div>
            `;
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        })
        .catch(err => {
            alertBox.innerHTML = `
                <div class="alert alert-danger d-flex align-items-center mb-4 p-3 rounded-3 shadow-sm border border-danger fade show">
                    <i class="fa-solid fa-circle-exclamation fs-3 text-danger me-3"></i>
                    <div>
                        <strong class="d-block text-danger mb-1" style="font-size: 1.05rem;">Submission Error</strong>
                        <span style="font-size: 0.95rem;">${err.message}</span>
                    </div>
                </div>
            `;
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        })
        .finally(() => {
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
        });
    });
});
</script>
@endpush
