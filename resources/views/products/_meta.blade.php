@if($selectedCompany || $search)
   <div class="d-flex align-items-center justify-content-between mt-3 pt-3 border-top border-light">
      <div class="small text-muted">
         Showing products @if($selectedCompany) for <strong>{{ $selectedCompany->name }} @if($selectedCompany->country)({{ $selectedCompany->country }})@endif</strong> @endif
         @if($search) matching "<strong>{{ $search }}</strong>" @endif
         ({{ $products->total() }} items found)
      </div>
      <button type="button" class="btn btn-link text-danger small fw-semibold text-decoration-none p-0 filter-pill-btn" data-url="{{ route('products') }}">
         <i class="fa-solid fa-times-circle me-1"></i> Clear Filters
      </button>
   </div>
@endif
