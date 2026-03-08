@extends('admin.layout.master')

@section('title', 'إدارة مكونات العطور — لوكس بارفيوم')

@section('page_title', 'مكونات العطور')
@section('page_subtitle', 'إدارة الروائح والمكونات (القمة، القلب، القاعدة)')

@section('content')
<section class="dashboard-section active">
    <!-- Stats Row -->
    <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 24px">
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon reviews"><i class="fas fa-wind"></i></div>
            </div>
            <div class="stat-card__value">{{ $notes->count() }}</div>
            <div class="stat-card__label">إجمالي المكونات المعرفة</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-flask"></i></div>
            </div>
            <div class="stat-card__value">{{ \DB::table('product_fragrance_note')->distinct('product_id')->count() }}</div>
            <div class="stat-card__label">منتجات تحتوي على مكونات</div>
        </div>
    </div>

    <div class="table-card">
        <div class="table-card__header">
            <h3>قائمة المكونات</h3>
            <div class="table-card__actions">
                <button class="btn btn-gold" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> إضافة مكون جديد
                </button>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>المكون</th>
                    <th>الوصف</th>
                    <th>عدد العطور</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notes as $note)
                <tr>
                    <td style="font-weight:700; color:var(--color-gold)">
                        {{ $note->getTranslation('name', 'ar') }}
                        <div style="font-size: 0.75rem; color: var(--color-text-muted)">{{ $note->getTranslation('name', 'en') }}</div>
                    </td>
                    <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--color-text-muted)">
                        {{ $note->getTranslation('description', 'ar') ?? '—' }}
                    </td>
                    <td>
                        <span class="status-badge" style="background: rgba(201, 168, 76, 0.1); color: var(--color-gold) !important">
                            {{ $note->products_count ?? $note->products->count() }} عطر
                        </span>
                    </td>
                    <td style="font-size: 0.8rem; color: var(--color-text-muted)">
                        {{ $note->created_at->format('Y/m/d') }}
                    </td>
                    <td>
                        <div style="display:flex; gap:8px">
                            <button class="btn btn-outline btn-sm" title="تعديل" 
                                onclick="openEditModal({{ $note->id }}, '{{ addslashes($note->getTranslation('name', 'ar')) }}', '{{ addslashes($note->getTranslation('name', 'en')) }}', '{{ addslashes($note->getTranslation('description', 'ar')) }}', '{{ addslashes($note->getTranslation('description', 'en')) }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.fragrance-notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المكون؟')">
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
                    <td colspan="5" style="text-align:center; padding: 40px">لا توجد مكونات مضافة حالياً</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

<!-- Add Modal -->
<div id="addModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center; padding: 20px">
    <div style="background:var(--color-bg-card); width:100%; max-width:600px; border-radius:12px; overflow:hidden; position:relative">
        <div style="padding:20px; border-bottom: 1px solid var(--color-border); display:flex; justify-content:space-between; align-items:center">
            <h3 style="margin:0">إضافة مكون عطري جديد</h3>
            <button onclick="closeModal('addModal')" style="background:none; border:none; color:var(--color-text); font-size:1.5rem; cursor:pointer">&times;</button>
        </div>
        <form action="{{ route('admin.fragrance-notes.store') }}" method="POST" style="padding:24px">
            @csrf
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px">
                <div class="form-group">
                    <label>الاسم (بالعربي)</label>
                    <input type="text" name="name_ar" class="form-input" required placeholder="مثال: ياسمين">
                </div>
                <div class="form-group">
                    <label>الاسم (بالإنجليزي)</label>
                    <input type="text" name="name_en" class="form-input" placeholder="Example: Jasmine">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:16px">
                <label>الوصف (بالعربي)</label>
                <textarea name="description_ar" class="form-input" rows="3" placeholder="وصف موجز للمكون..."></textarea>
            </div>
            <div class="form-group" style="margin-bottom:24px">
                <label>الوصف (بالإنجليزي)</label>
                <textarea name="description_en" class="form-input" rows="3" placeholder="Short description..."></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:12px">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">إلغاء</button>
                <button type="submit" class="btn btn-gold">حفظ المكون</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center; padding: 20px">
    <div style="background:var(--color-bg-card); width:100%; max-width:600px; border-radius:12px; overflow:hidden; position:relative">
        <div style="padding:20px; border-bottom: 1px solid var(--color-border); display:flex; justify-content:space-between; align-items:center">
            <h3 style="margin:0">تعديل المكون العطري</h3>
            <button onclick="closeModal('editModal')" style="background:none; border:none; color:var(--color-text); font-size:1.5rem; cursor:pointer">&times;</button>
        </div>
        <form id="editForm" method="POST" style="padding:24px">
            @csrf
            @method('PUT')
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px">
                <div class="form-group">
                    <label>الاسم (بالعربي)</label>
                    <input type="text" name="name_ar" id="edit_name_ar" class="form-input" required>
                </div>
                <div class="form-group">
                    <label>الاسم (بالإنجليزي)</label>
                    <input type="text" name="name_en" id="edit_name_en" class="form-input">
                </div>
            </div>
            <div class="form-group" style="margin-bottom:16px">
                <label>الوصف (بالعربي)</label>
                <textarea name="description_ar" id="edit_description_ar" class="form-input" rows="3"></textarea>
            </div>
            <div class="form-group" style="margin-bottom:24px">
                <label>الوصف (بالإنجليزي)</label>
                <textarea name="description_en" id="edit_description_en" class="form-input" rows="3"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:12px">
                <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">إلغاء</button>
                <button type="submit" class="btn btn-gold">تحديث البيانات</button>
            </div>
        </form>
    </div>
</div>

<style>
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(5px);
    }
    .modal-overlay.active {
        display: flex !important;
    }
</style>

@endsection

@section('scripts')
<script>
function openAddModal() {
    document.getElementById('addModal').classList.add('active');
}

function openEditModal(id, nameAr, nameEn, descAr, descEn) {
    const form = document.getElementById('editForm');
    form.action = `/admin/fragrance-notes/${id}`;
    
    document.getElementById('edit_name_ar').value = nameAr;
    document.getElementById('edit_name_en').value = nameEn;
    document.getElementById('edit_description_ar').value = descAr;
    document.getElementById('edit_description_en').value = descEn;
    
    document.getElementById('editModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Close when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
        event.target.classList.remove('active');
    }
}
</script>
@endsection
