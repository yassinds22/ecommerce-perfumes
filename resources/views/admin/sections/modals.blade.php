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
<!-- ===== EDIT SHIPPING MODAL ===== -->
<div class="modal-overlay" id="editShippingModal">
    <div class="modal">
        <div class="modal__header">
            <h3>تحديث بيانات الشحن</h3>
            <button class="modal__close" onclick="closeModal('editShippingModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="shippingForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="order_id" id="shippingOrderId">
            <div class="modal__body">
                <div class="form-group">
                    <label>شركة الشحن</label>
                    <input type="text" name="shipping_company" id="shippingCompany" placeholder="مثال: Aramex, DHL" required>
                </div>
                <div class="form-group">
                    <label>رقم التتبع</label>
                    <input type="text" name="tracking_number" id="trackingNumber" placeholder="مثال: AWB123456789" required>
                </div>
            </div>
            <div class="modal__footer">
                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ والتحويل لمشحون</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('editShippingModal')">إلغاء</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== EDIT STATUS MODAL ===== -->
<div class="modal-overlay" id="editStatusModal">
    <div class="modal">
        <div class="modal__header">
            <h3>تغيير حالة الطلب</h3>
            <button class="modal__close" onclick="closeModal('editStatusModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="statusForm">
            @csrf
            @method('PATCH')
            <input type="hidden" name="order_id" id="statusOrderId">
            <div class="modal__body">
                <div class="form-group">
                    <label>الحالة الجديدة</label>
                    <select name="status" id="orderStatusSelect">
                        <option value="pending">قيد الانتظار</option>
                        <option value="processing">قيد المعالجة</option>
                        <option value="shipped">تم الشحن</option>
                        <option value="delivered">تم التسليم</option>
                        <option value="cancelled">ملغي</option>
                    </select>
                </div>
            </div>
            <div class="modal__footer">
                <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> تحديث</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('editStatusModal')">إلغاء</button>
            </div>
        </form>
    </div>
</div>
