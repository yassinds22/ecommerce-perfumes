@forelse($products as $product)
<tr>
    <td>
        <div style="position: relative; width: 45px; height: 45px; cursor: pointer;" title="تكبير الصورة">
            @if($product->getFirstMediaUrl('images'))
                <img src="{{ $product->getFirstMediaUrl('images') }}" alt="" style="width: 100%; height: 100%; object-fit: cover; border-radius: 6px">
                <div style="position: absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; border-radius:6px; opacity:0; transition:0.3s; color:#fff;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                    <i class="fas fa-eye"></i>
                </div>
            @else
                <div style="width: 100%; height: 100%; background: var(--color-bg-light); border-radius: 6px; display: flex; align-items: center; justify-content: center">
                    <i class="fas fa-image" style="color: var(--color-text-muted)"></i>
                </div>
            @endif
        </div>
    </td>

    <td>
        <div style="font-weight:600">{{ $product->name }}</div>
        <small style="color:var(--color-text-muted)">SKU: {{ $product->sku }}</small>
    </td>
    <td>{{ $product->category->name ?? '—' }}</td>
    <td>{{ $product->brand->name ?? '—' }}</td>
    <td style="font-weight:600">
        @if($product->sale_price)
            <span style="color:var(--color-gold)">${{ number_format($product->sale_price, 2) }}</span>
            <del style="font-size:0.8rem; color:var(--color-text-muted)">${{ number_format($product->price, 2) }}</del>
        @else
            ${{ number_format($product->price, 2) }}
        @endif
    </td>
    <td>
        @php
            $coupon = $product->getApplicableCoupon();
        @endphp
        @if($coupon)
            <span class="status-badge" style="background: rgba(201, 168, 76, 0.1); color: var(--color-gold) !important; font-size: 0.75rem">
                <i class="fas fa-tag" style="margin-left: 5px"></i> {{ $coupon->code }}
            </span>
        @else
            <span style="color:var(--color-text-dim); font-size: 0.8rem">—</span>
        @endif
    </td>
    <td style="font-weight:700; color:var(--color-gold); font-size: 1.1rem">
        ${{ number_format($product->getDiscountedPrice(), 2) }}
    </td>


    <td>
        <span class="status-badge {{ $product->is_out_of_stock ? 'cancelled' : ($product->isLowStock() ? 'pending' : 'shipped') }}">
            @if($product->is_out_of_stock)
                <i class="fas fa-times-circle" style="margin-left:5px"></i> نفذ من المخزون
            @elseif($product->isLowStock())
                {{ $product->stock_quantity }} قطعة (منخفض)
            @else
                {{ $product->stock_quantity }} قطعة
            @endif
        </span>
    </td>
    <td>
        @if($product->status)
            <span class="status-badge shipped">نشط</span>
        @else
            <span class="status-badge cancelled">مسودة</span>
        @endif
    </td>
    <td>
        <div style="display:flex; gap:8px">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-outline btn-sm" title="تعديل">
                <i class="fas fa-edit"></i>
            </a>
            <button class="btn btn-outline btn-sm" title="عرض الصور" onclick="showProductGallery({{ $product->id }})">
                <i class="fas fa-eye"></i>
            </button>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">

                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline btn-sm" style="color:var(--color-danger)" title="حذف">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="10" style="text-align:center; padding: 40px">لا توجد منتجات مضافة حالياً</td>
</tr>
@endforelse

<tr class="pagination-row">
    <td colspan="10" style="padding: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; background: rgba(255, 255, 255, 0.02); border-top: 1px solid var(--color-border);">
            <div class="pagination-info" style="display: flex; align-items: center; gap: 10px; color: var(--color-text-muted); font-size: 0.85rem;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(201, 168, 76, 0.1); display: flex; align-items: center; justify-content: center; color: var(--color-gold);">
                    <i class="fas fa-layer-group" style="font-size: 0.75rem;"></i>
                </div>
                <span>
                    عرض <span style="color: var(--color-gold); font-weight: 600;">{{ $products->firstItem() }}</span> 
                    إلى <span style="color: var(--color-gold); font-weight: 600;">{{ $products->lastItem() }}</span> 
                    من أصل <span style="color: var(--color-text); font-weight: 600;">{{ $products->total() }}</span> منتج
                </span>
            </div>
            <div class="pagination-container">
                {!! $products->links('admin.layout.pagination') !!}
            </div>

        </div>
    </td>
</tr>


