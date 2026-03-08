<!-- ===== OFFERS SECTION ===== -->
<section class="dashboard-section" id="offers">
    <div class="table-card">
        <div class="table-card__header">
            <h3>العروض الحالية</h3>
            <div class="table-card__actions">
                <button class="btn btn-gold" onclick="openModal('addOfferModal')"><i
                        class="fas fa-plus"></i> إضافة عرض</button>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>النوع</th>
                    <th>العنوان</th>
                    <th>الوصف</th>
                    <th>الكود</th>
                    <th>الانتهاء</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="offersTableBody"></tbody>
        </table>
    </div>
</section>
