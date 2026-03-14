@props(['categories', 'brands'])
<style>
    .dropdown-menu-custom {
        position: absolute;
        top: 100%;
        right: 0;
        background: #1a1a1a;
        border: 1px solid #d4af37;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        z-index: 9999;
        display: none;
        min-width: 200px;
        margin-top: 5px;
        overflow: hidden;
    }
    .dropdown-menu-custom.show {
        display: block;
    }
    .export-option {
        display: block;
        padding: 12px 16px;
        color: #fff;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .export-option:hover {
        background: #d4af37;
        color: #000;
    }
</style>
<div class="table-card" style="margin-bottom: 30px; overflow: visible !important;">
    <div class="table-card__header">
        <div class="title-with-icon">
            <i class="fas fa-filter" style="color: var(--color-gold)"></i>
            <h3>Filter Comprehensive Reports</h3>
        </div>
    </div>
    <div class="p-4">
        <form id="reportFilterForm" action="{{ route('admin.reports.index') }}" method="GET" class="form-row">
            <x-report.filter-date name="start_date" label="From Date" :value="request('start_date', now()->subDays(30)->toDateString())" />
            <x-report.filter-date name="end_date" label="To Date" :value="request('end_date', now()->toDateString())" />
            
            <div class="form-group">
                <label>Report Type</label>
                <select name="report_type" id="report_type" class="form-control report-filter">
                    <option value="daily_sales">Daily Sales Report</option>
                    <option value="orders_status">Orders Status Report</option>
                    <option value="inventory">Inventory Status Report</option>
                    <option value="top_products">Top Selling Products Report</option>
                    <option value="profit">Profit & Margin Report</option>
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control report-filter">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Brand</label>
                <select name="brand_id" class="form-control report-filter">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Order Status</label>
                <select name="status" class="form-control report-filter">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Processing">Processing</option>
                    <option value="Shipped">Shipped</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Canceled">Canceled</option>
                </select>
            </div>

            <div class="form-group" style="display: flex; align-items: flex-end; gap: 10px;">
                <button type="submit" class="btn btn-gold" style="flex: 1">Update Results</button>
                <div class="export-dropdown" style="position: relative;">
                    <button type="button" id="exportBtnMain" class="btn btn-outline">
                        <i class="fas fa-download"></i> Export <i class="fas fa-chevron-down" style="font-size: 0.7rem; margin-right: 5px"></i>
                    </button>
                    <div id="exportMenu" class="dropdown-menu-custom">
                        <a href="#" class="export-option" data-format="csv"><i class="fas fa-file-csv"></i> CSV (Excel)</a>
                        <a href="#" class="export-option" data-format="pdf"><i class="fas fa-file-pdf"></i> PDF File</a>
                    </div>
                </div>
                <button type="button" id="resetFilters" class="btn btn-outline">Reset</button>
            </div>
        </form>
    </div>
</div>
