<!-- ===== PRODUCTS SECTION ===== -->
<section class="dashboard-section" id="products">
    <div class="table-card">
        <div class="table-card__header">
            <h3>جميع المنتجات <span style="color:var(--color-text-muted);font-weight:400">(9)</span></h3>
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
            <tbody id="productsTableBody"></tbody>
        </table>
        <div class="table-footer">
            <span>عرض 9 من 9 نتيجة</span>
            <div class="pagination">
                <button class="active">1</button>
            </div>
        </div>
    </div>
</section>
