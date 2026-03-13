@props(['categories', 'brands'])
<div class="table-card" style="margin-bottom: 30px">
    <div class="table-card__header">
        <div class="title-with-icon">
            <i class="fas fa-filter" style="color: var(--color-gold)"></i>
            <h3>تصفية التقارير</h3>
        </div>
    </div>
    <div class="p-4">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="form-row">
            <div class="form-group">
                <label>من تاريخ</label>
                <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->toDateString()) }}">
            </div>
            <div class="form-group">
                <label>إلى تاريخ</label>
                <input type="date" name="end_date" value="{{ request('end_date', now()->toDateString()) }}">
            </div>
            <div class="form-group">
                <label>القسم</label>
                <select name="category_id">
                    <option value="">كل الأقسام</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>البراند</label>
                <select name="brand_id">
                    <option value="">كل البراندات</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="display: flex; align-items: flex-end; gap: 10px;">
                <button type="submit" class="btn btn-gold" style="flex: 1">تطبيق الفلتر</button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline">إعادة تعيين</a>
            </div>
        </form>
    </div>
</div>
