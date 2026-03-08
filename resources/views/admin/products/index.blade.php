@extends('admin.layout.master')

@section('title', 'إدارة المنتجات — لوكس بارفيوم')

@section('page_title', 'المنتجات')
@section('page_subtitle', 'إدارة المخزون والمنتجات')

@section('content')
<style>
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        gap: 10px;
        align-items: center;
    }
    .pagination li a, .pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 14px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.03);
        color: var(--color-text-muted);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--color-border);
    }
    .pagination li.active span {
        background: linear-gradient(135deg, var(--color-gold), #b38f4d);
        color: #000;
        border-color: var(--color-gold);
        box-shadow: 0 4px 12px rgba(201, 168, 76, 0.2);
    }
    .pagination li a:hover {
        background: rgba(201, 168, 76, 0.1);
        border-color: var(--color-gold);
        color: var(--color-gold);
        transform: translateY(-2px);
    }
    .pagination li.disabled span {
        opacity: 0.3;
        cursor: not-allowed;
    }
    .pagination-info {
        color: var(--color-text-muted);
        font-size: 0.85rem;
    }

</style>

<section class="dashboard-section active">
    <!-- Stats Row for Products -->
    <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 24px">
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-box"></i></div>
            </div>
            <div class="stat-card__value">{{ $products->count() }}</div>
            <div class="stat-card__label">إجمالي المنتجات</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon orders"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-card__value">{{ $products->where('status', true)->count() }}</div>
            <div class="stat-card__label">نشط</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon customers"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
            <div class="stat-card__value">{{ $products->where('stock', '<=', 5)->count() }}</div>
            <div class="stat-card__label">مخزون منخفض</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon reviews"><i class="fas fa-star"></i></div>
            </div>
            <div class="stat-card__value">{{ $products->where('is_featured', true)->count() }}</div>
            <div class="stat-card__label">مميز</div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card__header">
            <h3>قائمة المنتجات</h3>
            <div class="table-card__actions">
                <a href="{{ route('admin.products.create') }}" class="btn btn-gold">
                    <i class="fas fa-plus"></i> إضافة منتج جديد
                </a>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>صورة</th>
                    <th>المنتج</th>
                    <th>التصنيف</th>
                    <th>الماركة</th>
                    <th>السعر الأصلي</th>
                    <th>الكوبون المطبق</th>
                    <th>السعر الصافي</th>
                    <th>المخزون</th>
                    <th>الحالة</th>
                    <th>إجراءات</th>

                </tr>
            </thead>
            <tbody id="products-table-body">
                @include('admin.products._table')
            </tbody>
        </table>
    </div>
</section>

<!-- Gallery Modal -->
<div id="galleryModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center; padding: 20px">
    <div style="background:var(--color-bg-card); width:100%; max-width:800px; border-radius:12px; overflow:hidden; position:relative">
        <div style="padding:20px; border-bottom: 1px solid var(--color-border); display:flex; justify-content:space-between; align-items:center">
            <h3 style="margin:0">معرض صور المنتج</h3>
            <button onclick="closeGallery()" style="background:none; border:none; color:var(--color-text); font-size:1.5rem; cursor:pointer">&times;</button>
        </div>
        <div id="galleryContainer" style="padding:24px; display:flex; gap:16px; flex-wrap:wrap; max-height:600px; overflow-y:auto; justify-content:center">
            <!-- Images will be injected here -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// AJAX Pagination
document.addEventListener('click', function (e) {
    if (e.target.closest('.pagination a')) {
        e.preventDefault();
        const url = e.target.closest('a').href;
        fetchProducts(url);
    }
});

function fetchProducts(url) {
    const tableBody = document.getElementById('products-table-body');
    tableBody.style.opacity = '0.5';
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        tableBody.innerHTML = html;
        tableBody.style.opacity = '1';
        // Scroll to table top smoothly
        document.querySelector('.table-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    })
    .catch(error => {
        console.error('Error fetching products:', error);
        tableBody.style.opacity = '1';
    });
}

function showProductGallery(productId) {
    const modal = document.getElementById('galleryModal');
    const container = document.getElementById('galleryContainer');
    container.innerHTML = '<div style="padding:20px; text-align:center"><i class="fas fa-spinner fa-spin"></i> جاري التحميل...</div>';
    modal.style.display = 'flex';

    fetch(`/admin/products/${productId}/media`)
        .then(response => response.json())
        .then(data => {
            container.innerHTML = '';
            if (data.images.length === 0) {
                container.innerHTML = '<p style="text-align:center; padding:20px">لا توجد صور لهذا المنتج</p>';
                return;
            }
            
            data.images.forEach(img => {
                const div = document.createElement('div');
                div.style.cssText = 'width: 150px; height: 150px; border-radius: 8px; overflow: hidden; border: 1px solid var(--color-border); position:relative';
                div.innerHTML = `
                    <img src="${img.url}" style="width:100%; height:100%; object-fit:cover">
                    <button onclick="deleteProductMedia(${img.id}, this)" style="position:absolute; top:5px; right:5px; background:rgba(255,255,255,0.9); color:#ff4d4d; border:none; border-radius:50%; width:28px; height:28px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:14px; box-shadow:0 2px 4px rgba(0,0,0,0.2)" title="حذف">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                container.appendChild(div);
            });
        });
}

function deleteProductMedia(mediaId, btn) {
    if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) return;

    fetch(`/admin/media/${mediaId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.parentElement.remove();
        }
    });
}

function closeGallery() {
    document.getElementById('galleryModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('galleryModal');
    if (event.target == modal) {
        closeGallery();
    }
}
</script>
@endsection


