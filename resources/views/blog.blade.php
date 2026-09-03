@extends('layouts.app')

@section('title', \App\Models\Setting::get('blog_banner_title', 'Blog & Clinical Research') . ' | Healthcare Articles - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('meta_description', 'Read clinical engineering research, ICU equipment maintenance protocols, calibration standards, and diagnostic medical technology updates from Innotech specialists.')
@section('canonical_url', route('blog'))
@section('og_title', 'Clinical Research & Healthcare Insights | ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))
@section('og_description', 'Read clinical engineering research, ICU equipment maintenance protocols, calibration standards, and diagnostic medical technology updates from Innotech specialists.')
@section('og_image', asset(\App\Models\Setting::get('blog_banner_image', 'assets/img/banner/breadcrumb-01.jpg')))

@section('content')
<main>

   <!-- 1. BREADCRUMB AREA -->
   <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset(\App\Models\Setting::get('blog_banner_image', 'assets/img/banner/breadcrumb-01.jpg')) }}">
      <div class="container">
         <div class="row align-items-center">
            <div class="col-lg-6 col-md-7 col-12">
               <div class="tp-breadcrumb">
                  <h2 class="tp-breadcrumb__title">{{ \App\Models\Setting::get('blog_banner_title', 'Blog & Clinical Insights') }}</h2>
               </div>
            </div>
            <div class="col-lg-6 col-md-5 col-12">
               <div class="tp-breadcrumb__link d-flex align-items-center justify-content-md-end">
                  <span>Innotech : <a href="{{ route('blog') }}">{{ \App\Models\Setting::get('blog_banner_subtitle', 'Blog') }}</a></span>
               </div>
            </div>
         </div>
      </div>
   </section>
   <!-- breadcrumb-area-end -->

   <!-- 2. POSTBOX / BLOG LIST AREA -->
   <div class="postbox-area pt-120 pb-80">
      <div class="container">
         <div class="row">
            <div class="col-xxl-8 col-xl-8 col-lg-7 col-md-12">
               <div class="postbox pr-20 pb-50">

                  <!-- Active Filter Header (if searching or filtering by category/tag) -->
                  @if(!empty($search) || !empty($categoryFilter) || !empty($tagFilter))
                     <div class="alert alert-light border d-flex justify-content-between align-items-center mb-40 p-3 rounded-3 shadow-sm">
                        <div>
                           <span class="text-muted small text-uppercase fw-bold">Active Filter:</span>
                           @if(!empty($search))
                              <span class="badge bg-primary ms-1">Search: "{{ $search }}"</span>
                           @endif
                           @if(!empty($categoryFilter))
                              <span class="badge bg-success ms-1">Category: {{ $categoryFilter }}</span>
                           @endif
                           @if(!empty($tagFilter))
                              <span class="badge bg-info text-dark ms-1">Tag: #{{ $tagFilter }}</span>
                           @endif
                           <span class="text-muted ms-2 small">({{ $blogs->total() }} {{ Str::plural('article', $blogs->total()) }} found)</span>
                        </div>
                        <a href="{{ route('blog') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                           <i class="fa-solid fa-xmark me-1"></i> Clear Filter
                        </a>
                     </div>
                  @endif

                  @forelse($blogs as $b)
                     @if(!empty($b->video_url))
                        <!-- VIDEO FORMAT ARTICLE -->
                        <article class="postbox__item format-video mb-60 transition-3">
                           <div class="postbox__thumb postbox__video p-relative w-img mb-35">
                              <a href="{{ route('blog.detail', $b->slug) }}">
                                 <img src="{{ asset($b->image ?: 'assets/img/blog/blog-in-03.jpg') }}" alt="{{ $b->title }}" style="max-height: 420px; object-fit: cover;">
                              </a>
                              <a href="{{ $b->video_url }}" class="play-btn popup-video" target="_blank"><i class="fas fa-play"></i></a>
                           </div>
                           <div class="postbox__content">
                              <div class="postbox__meta mb-40">
                                 <span><i class="fa-regular fa-user"></i> {{ $b->author }}</span>
                                 <span><i class="fa-regular fa-clock"></i> {{ $b->published_at ? $b->published_at->format('M d, Y') : date('M d, Y') }}</span>
                                 <span><a href="{{ route('blog', ['category' => $b->category]) }}"><i class="fa-solid fa-folder"></i> {{ $b->category }}</a></span>
                                 <span><i class="fa-light fa-eye"></i> {{ number_format($b->views) }} views</span>
                              </div>
                              <h3 class="postbox__title mb-40">
                                 <a href="{{ route('blog.detail', $b->slug) }}">{{ $b->title }}</a>
                              </h3>
                              <div class="postbox__text mb-40">
                                 <p>{{ $b->summary }}</p>
                              </div>
                              <div class="postbox__read-more">
                                 <a href="{{ route('blog.detail', $b->slug) }}" class="tp-btn">read more</a>
                              </div>
                           </div>
                        </article>

                     @elseif(!empty($b->slider_images) && is_array($b->slider_images) && count($b->slider_images) > 1)
                        <!-- GALLERY SLIDER FORMAT ARTICLE -->
                        <article class="postbox__item format-image mb-60 transition-3">
                           <div class="postbox__thumb postbox-active swiper-container w-img p-relative mb-35">
                              <div class="swiper-wrapper">
                                 @if($b->image)
                                    <div class="postbox__slider-item swiper-slide">
                                       <img src="{{ asset($b->image) }}" alt="{{ $b->title }}" style="max-height: 420px; object-fit: cover;">
                                    </div>
                                 @endif
                                 @foreach($b->slider_images as $sImg)
                                    <div class="postbox__slider-item swiper-slide">
                                       <img src="{{ asset($sImg) }}" alt="{{ $b->title }}" style="max-height: 420px; object-fit: cover;">
                                    </div>
                                 @endforeach
                              </div>
                              <div class="postbox-nav">
                                 <button class="postbox-slider-button-next"><i class="fa-solid fa-chevron-right"></i></button>
                                 <button class="postbox-slider-button-prev"><i class="fa-solid fa-chevron-left"></i></button>
                              </div>
                           </div>
                           <div class="postbox__content">
                              <div class="postbox__meta mb-40">
                                 <span><i class="fa-regular fa-user"></i> {{ $b->author }}</span>
                                 <span><i class="fa-regular fa-clock"></i> {{ $b->published_at ? $b->published_at->format('M d, Y') : date('M d, Y') }}</span>
                                 <span><a href="{{ route('blog', ['category' => $b->category]) }}"><i class="fa-solid fa-folder"></i> {{ $b->category }}</a></span>
                                 <span><i class="fa-light fa-eye"></i> {{ number_format($b->views) }} views</span>
                              </div>
                              <h3 class="postbox__title mb-40">
                                 <a href="{{ route('blog.detail', $b->slug) }}">{{ $b->title }}</a>
                              </h3>
                              <div class="postbox__text mb-40">
                                 <p>{{ $b->summary }}</p>
                              </div>
                              <div class="postbox__read-more">
                                 <a href="{{ route('blog.detail', $b->slug) }}" class="tp-btn">read more</a>
                              </div>
                           </div>
                        </article>

                     @else
                        <!-- STANDARD IMAGE FORMAT ARTICLE -->
                        <article class="postbox__item format-image mb-60 transition-3">
                           <div class="postbox__thumb w-img mb-35">
                              <a href="{{ route('blog.detail', $b->slug) }}">
                                 <img src="{{ asset($b->image ?: 'assets/img/blog/blog-in-01.jpg') }}" alt="{{ $b->title }}" style="max-height: 420px; object-fit: cover;">
                              </a>
                           </div>
                           <div class="postbox__content">
                              <div class="postbox__meta mb-40">
                                 <span><i class="fa-regular fa-user"></i> {{ $b->author }}</span>
                                 <span><i class="fa-regular fa-clock"></i> {{ $b->published_at ? $b->published_at->format('M d, Y') : date('M d, Y') }}</span>
                                 <span><a href="{{ route('blog', ['category' => $b->category]) }}"><i class="fa-solid fa-folder"></i> {{ $b->category }}</a></span>
                                 <span><i class="fa-light fa-eye"></i> {{ number_format($b->views) }} views</span>
                              </div>
                              <h3 class="postbox__title mb-40">
                                 <a href="{{ route('blog.detail', $b->slug) }}">{{ $b->title }}</a>
                              </h3>
                              <div class="postbox__text mb-40">
                                 <p>{{ $b->summary }}</p>
                              </div>
                              <div class="postbox__read-more">
                                 <a href="{{ route('blog.detail', $b->slug) }}" class="tp-btn">read more</a>
                              </div>
                           </div>
                        </article>
                     @endif

                  @empty
                     <!-- EMPTY STATE -->
                     <div class="card border-0 shadow-sm text-center p-5 rounded-4 bg-white mb-60">
                        <div class="mb-4">
                           <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: #eef2ff; color: #0E63FF;">
                              <i class="fa-light fa-newspaper fa-3x"></i>
                           </div>
                        </div>
                        <h4 class="fw-bold mb-2">No Articles Found</h4>
                        <p class="text-muted mb-4">We couldn't find any articles matching your query. Try searching with different keywords or exploring all categories.</p>
                        <div>
                           <a href="{{ route('blog') }}" class="tp-btn">
                              <i class="fa-solid fa-rotate-left me-2"></i> View All Articles
                           </a>
                        </div>
                     </div>
                  @endforelse

                  <!-- DYNAMIC THEME UI PAGINATION -->
                  @if($blogs->total() > 0)
                     <div class="basic-pagination">
                        <nav>
                           <ul>
                              {{-- Previous Page Link --}}
                              @if ($blogs->onFirstPage())
                                 <li class="disabled"><span><i class="fa-light fa-arrow-left-long"></i></span></li>
                              @else
                                 <li><a href="{{ $blogs->previousPageUrl() }}"><i class="fa-light fa-arrow-left-long"></i></a></li>
                              @endif

                              {{-- Pagination Elements --}}
                              @foreach ($blogs->getUrlRange(1, max(1, $blogs->lastPage())) as $page => $url)
                                 @if ($page == $blogs->currentPage())
                                    <li><span class="current">{{ $page }}</span></li>
                                 @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                 @endif
                              @endforeach

                              {{-- Next Page Link --}}
                              @if ($blogs->hasMorePages())
                                 <li><a href="{{ $blogs->nextPageUrl() }}"><i class="fa-light fa-arrow-right-long"></i></a></li>
                              @else
                                 <li class="disabled"><span><i class="fa-light fa-arrow-right-long"></i></span></li>
                              @endif
                           </ul>
                        </nav>
                     </div>
                  @endif

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
                                 <input type="text" name="search" placeholder="Search post..." value="{{ $search ?? '' }}" autocomplete="off">
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
                                 <a href="{{ route('blog', ['category' => $cat->category]) }}" class="{{ ($categoryFilter == $cat->category) ? 'text-primary fw-bold' : '' }}">
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
                              <a href="{{ route('blog', ['tag' => $tag]) }}" class="{{ ($tagFilter == $tag) ? 'active-tag' : '' }}">
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
.tagcloud a.active-tag {
   background: #0E63FF !important;
   color: #ffffff !important;
   border-color: #0E63FF !important;
}
.basic-pagination ul li.disabled span {
   opacity: 0.45;
   cursor: not-allowed;
   display: inline-block;
   width: 50px;
   height: 50px;
   line-height: 50px;
   text-align: center;
   border: 1px solid #e2e8f0;
   border-radius: 7px;
   color: #94a3b8;
}
</style>
@endsection
