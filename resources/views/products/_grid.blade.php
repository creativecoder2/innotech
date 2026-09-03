@if($products->count() > 0)
   <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-50">
      @foreach($products as $product)
         <div class="col">
            <div class="product-item-card h-100 bg-white rounded-3 d-flex flex-column transition" 
                 style="border: 1.5px solid #e2e8f0; border-radius: 14px; padding: 22px 20px 18px 20px; transition: all 0.3s ease; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
               
               <!-- Product Image Area -->
               <a href="{{ route('product.detail', $product->slug) }}" 
                  class="product-image-box d-flex align-items-center justify-content-center mb-3" 
                  style="height: 230px; background-color: #ffffff; border-radius: 10px; overflow: hidden; position: relative;">
                  <img src="{{ asset($product->image ?: 'assets/img/shop/shop-01.jpg') }}" 
                       alt="{{ $product->title }}" 
                       class="img-fluid transition-transform" 
                       style="max-height: 210px; max-width: 90%; object-fit: contain; transition: transform 0.35s ease;">
               </a>

               <!-- Product Bottom Bar: Title & Arrow -->
               <div class="product-card-footer mt-auto pt-2 border-top border-light d-flex align-items-center justify-content-between">
                  <a href="{{ route('product.detail', $product->slug) }}" 
                     class="product-card-title text-decoration-none fw-bold flex-grow-1 pe-2" 
                     style="font-size: 13px; color: #1e293b; line-height: 1.35; letter-spacing: 0.3px; text-transform: uppercase;">
                     {{ Str::limit($product->title, 48) }}
                  </a>
                  
                  <a href="{{ route('product.detail', $product->slug) }}" 
                     class="product-card-arrow d-flex align-items-center justify-content-center text-dark flex-shrink-0" 
                     style="width: 28px; height: 28px; transition: all 0.2s ease;" 
                     title="View Details">
                     <i class="fa-solid fa-chevron-right" style="font-size: 14px; font-weight: 900; color: #334155;"></i>
                  </a>
               </div>

            </div>
         </div>
      @endforeach
   </div>

   <!-- PAGINATION -->
   @if($products->hasPages())
      <div class="row">
         <div class="col-12 d-flex justify-content-center ajax-pagination">
            {{ $products->links() }}
         </div>
      </div>
   @endif
@else
   <!-- EMPTY STATE -->
   <div class="card p-5 border-0 shadow-sm rounded-4 text-center my-4 bg-white">
      <div class="py-5">
         <div class="mb-3 text-muted">
            <i class="fa-solid fa-box-open fa-3x text-primary opacity-50"></i>
         </div>
         <h4 class="fw-bold text-dark mb-2">No Products Found</h4>
         <p class="text-secondary mb-4">No medical equipment matches your selected company or search term.</p>
         <button type="button" class="btn btn-primary rounded-pill px-4 py-2 filter-pill-btn" data-url="{{ route('products') }}">
            <i class="fa-solid fa-arrow-rotate-left me-1"></i> View All Products
         </button>
      </div>
   </div>
@endif
