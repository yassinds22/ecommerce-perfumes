<!-- ===== ORDERS SECTION ===== -->
<section class="dashboard-section" id="orders">
    <div class="stats-grid" style="grid-template-columns: repeat(4,1fr); margin-bottom:24px">
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon orders"><i class="fas fa-clock"></i></div>
            </div>
            <div class="stat-card__value" style="font-size:1.4rem">3</div>
            <div class="stat-card__label">قيد الانتظار</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon"
                    style="background:rgba(96,165,250,0.15);color:var(--color-info)"><i
                        class="fas fa-shipping-fast"></i></div>
            </div>
            <div class="stat-card__value" style="font-size:1.4rem">2</div>
            <div class="stat-card__label">تم الشحن</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="stat-card__value" style="font-size:1.4rem">384</div>
            <div class="stat-card__label">مكتمل</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon customers"><i class="fas fa-times-circle"></i></div>
            </div>
            <div class="stat-card__value" style="font-size:1.4rem">5</div>
            <div class="stat-card__label">ملغي</div>
        </div>
    </div>
    <div class="table-card">
        <div class="table-card__header">
            <h3>جميع الطلبات</h3>
            <div class="table-card__actions">
                <button class="btn btn-outline btn-sm"><i class="fas fa-filter"></i> فلتر</button>
                <button class="btn btn-outline btn-sm"><i class="fas fa-download"></i> تصدير</button>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>المنتجات</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="ordersTableBody"></tbody>
        </table>
        <div class="table-footer">
            <span>عرض 7 من 384 نتيجة</span>
            <div class="pagination">
                <button class="active">1</button>
                <button>2</button>
                <button>3</button>
                <button>...</button>
            </div>
        </div>
    </div>
</section>
