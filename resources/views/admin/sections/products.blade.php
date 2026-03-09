<!-- ===== PRODUCTS SECTION ===== -->
<section class="dashboard-section" id="products">
    <div class="table-card">
        <div class="table-card__header">
            <h3>جميع المنتجات <span style="color:var(--color-text-muted);font-weight:400">({{ $allProducts->total() }})</span></h3>
            <div class="table-card__actions">
                <button class="btn btn-gold" onclick="openModal('addProductModal')"><i
                        class="fas fa-plus"></i> إضافة منتج</button>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>التصنيف</th>
                    <th>السعر</th>
                    <th>المخزون</th>
                    <th>التقييم</th>
                    <th>الشارة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="productsTableBody">
                @foreach($allProducts as $product)
                <tr>
                    <td>
                        <div class="table-product">
                            <div class="table-product__img">
                                <img src="{{ $product->getFirstMediaUrl('main_image') ?: asset('assets/clints/images/products/p1.jpg') }}" alt="{{ $product->name }}">
                            </div>
                            <div>
                                <div class="table-product__name">{{ $product->name }}</div>
                                <div class="table-product__brand">{{ $product->brand->name ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $product->category->name ?? '' }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>
                        <div class="stars">
                            @php $rating = 5.0; // Placeholder for now @endphp
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= $rating ? '' : 'far' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td>
                        @if($product->is_new) <span class="status-badge shipped">جديد</span>
                        @elseif($product->is_on_sale) <span class="status-badge warning">تخفيض</span>
                        @else — @endif
                    </td>
                    <td>
                        <div class="table-actions">
                            <button title="تعديل" onclick="editProduct({{ $product->id }})"><i class="fas fa-pen"></i></button>
                            <button class="delete" title="حذف" onclick="deleteProduct({{ $product->id }})"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="table-footer">
            <span>عرض {{ $allProducts->count() }} من {{ $allProducts->total() }} نتيجة</span>
            <div class="pagination">
                {{ $allProducts->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    </div>
</section>
