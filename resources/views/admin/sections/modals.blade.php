<!-- ===== ADD PRODUCT MODAL ===== -->
<div class="modal-overlay" id="addProductModal">
    <div class="modal">
        <div class="modal__header">
            <h3>إضافة منتج جديد</h3>
            <button class="modal__close" onclick="closeModal('addProductModal')"><i
                    class="fas fa-times"></i></button>
        </div>
        <div class="modal__body">
            <div class="form-row">
                <div class="form-group">
                    <label>اسم المنتج</label>
                    <input type="text" placeholder="مثال: عنبر عود ملكي">
                </div>
                <div class="form-group">
                    <label>العلامة التجارية</label>
                    <input type="text" placeholder="مثال: لوكس بارفيوم">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>السعر ($)</label>
                    <input type="number" placeholder="340">
                </div>
                <div class="form-group">
                    <label>التصنيف</label>
                    <select>
                        <option>رجالي</option>
                        <option>نسائي</option>
                        <option>مشترك</option>
                        <option>عربي</option>
                        <option>هدايا</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>المخزون</label>
                    <input type="number" placeholder="50">
                </div>
                <div class="form-group">
                    <label>الشارة</label>
                    <select>
                        <option value="">بدون</option>
                        <option>جديد</option>
                        <option>الأكثر مبيعاً</option>
                        <option>محدود</option>
                        <option>تخفيض</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>الوصف</label>
                <textarea placeholder="وصف المنتج..."></textarea>
            </div>
        </div>
        <div class="modal__footer">
            <button class="btn btn-gold"
                onclick="showDashToast('تم إضافة المنتج بنجاح!'); closeModal('addProductModal')"><i
                    class="fas fa-plus"></i> إضافة</button>
            <button class="btn btn-outline" onclick="closeModal('addProductModal')">إلغاء</button>
        </div>
    </div>
</div>

<!-- ===== ADD OFFER MODAL ===== -->
<div class="modal-overlay" id="addOfferModal">
    <div class="modal">
        <div class="modal__header">
            <h3>إضافة عرض جديد</h3>
            <button class="modal__close" onclick="closeModal('addOfferModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal__body">
            <div class="form-row">
                <div class="form-group">
                    <label>عنوان العرض</label>
                    <input type="text" placeholder="مثال: خصم 20%">
                </div>
                <div class="form-group">
                    <label>النوع</label>
                    <input type="text" placeholder="مثال: عرض الصيف">
                </div>
            </div>
            <div class="form-group">
                <label>الوصف</label>
                <input type="text" placeholder="مثال: على جميع العطور الزهرية">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>كود الخصم</label>
                    <input type="text" placeholder="SUMMER20">
                </div>
                <div class="form-group">
                    <label>تاريخ الانتهاء</label>
                    <input type="date">
                </div>
            </div>
        </div>
        <div class="modal__footer">
            <button class="btn btn-gold"
                onclick="showDashToast('تم إضافة العرض بنجاح!'); closeModal('addOfferModal')"><i
                    class="fas fa-plus"></i> إضافة</button>
            <button class="btn btn-outline" onclick="closeModal('addOfferModal')">إلغاء</button>
        </div>
    </div>
</div>
