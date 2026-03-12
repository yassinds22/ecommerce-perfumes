@extends('clints.layout.master')

@section('title', 'نتائج البحث — لوكس بارفيوم')

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/clints/css/shop.css')}}">
@endsection

@section('content')
    <div class="page-spacer"></div>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-2">نتائج البحث عن: "{{ $query }}"</h1>
                <p class="text-muted">تم العثور على {{ $products->total() }} منتج</p>
            </div>
        </div>

        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="card border-0 shadow-sm" style="background: var(--color-bg-light); border-radius: 12px; padding: 20px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3" style="color: var(--color-gold); font-weight: 600;">تصفية النتائج</h5>
                        <form action="{{ route('search') }}" method="GET" id="searchFiltersForm">
                            <input type="hidden" name="q" value="{{ $query }}">
                            
                            <div class="mb-3">
                                <label class="form-label">الجنس</label>
                                <select name="gender" class="form-select filter-trigger" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                                    <option value="">الكل</option>
                                    <option value="Men" {{ request('gender') == 'Men' ? 'selected' : '' }}>رجالي</option>
                                    <option value="Women" {{ request('gender') == 'Women' ? 'selected' : '' }}>نسائي</option>
                                    <option value="Unisex" {{ request('gender') == 'Unisex' ? 'selected' : '' }}>للجنسين</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Results Grid -->
            <div class="col-lg-9">
                <div id="productResultsContainer" class="product-grid">
                    @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-card__image">
                            <img src="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}"
                                alt="{{ $product->getTranslation('name', 'ar') }}">
                            <div class="product-card__actions">
                                <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                                <button onclick="window.location.href='{{ route('product', $product->id) }}'"><i class="far fa-eye"></i></button>
                            </div>
                        </div>
                        <div class="product-card__info">
                            <p class="product-card__brand">{{ $product->brand->name ?? 'لوكس بارفيوم' }}</p>
                            <h4 class="product-card__name">{{ $product->getTranslation('name', 'ar') }}</h4>
                            <p class="product-card__price">${{ $product->price }}</p>
                            <button class="product-card__btn">أضف للسلة</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4 d-flex justify-content-center">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('searchFiltersForm');
            if (filterForm) {
                filterForm.querySelectorAll('.filter-trigger').forEach(trigger => {
                    trigger.addEventListener('change', function() {
                        filterForm.submit();
                    });
                });
            }
        });
    </script>
@endsection
