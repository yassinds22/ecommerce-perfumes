<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إتمام الشراء — لوكس بارفيوم</title>
    <meta name="description" content="أكمل طلبك بأمان في لوكس بارفيوم.">
    <link rel="stylesheet" href="{{ asset('assets/clints/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/clints/css/checkout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        window.stripeKey = "{{ config('services.stripe.key') }}";
    </script>
</head>

<body>
    <!-- التنقل المبسط -->
    @include('clints.layout.nav')

    <div class="page-spacer"></div>

    <!-- إتمام الشراء -->
    <section class="section checkout-section">
        <div class="container">
            <div class="checkout-layout">
                <!-- النماذج -->
                <div class="checkout-forms">
                    <!-- الخطوة 1: الشحن -->
                    <div class="checkout-step active" id="step1">
                        <h2>معلومات الشحن</h2>
                        <p class="checkout-step__desc">أدخل عنوان التوصيل الخاص بك</p>

                        <form class="checkout-form" id="shippingForm">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">الاسم الأول *</label>
                                    <input type="text" id="firstName" placeholder="أحمد" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">اسم العائلة *</label>
                                    <input type="text" id="lastName" placeholder="محمد" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">البريد الإلكتروني *</label>
                                <input type="email" id="email" placeholder="example@mail.com" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">رقم الهاتف *</label>
                                <input type="tel" id="phone" placeholder="+966 50 000 0000" required>
                            </div>
                            <div class="form-group">
                                <label for="address">العنوان *</label>
                                <input type="text" id="address" placeholder="اسم الشارع، رقم المبنى" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">المدينة *</label>
                                    <input type="text" id="city" placeholder="الرياض" required>
                                </div>
                                <div class="form-group">
                                    <label for="state">المنطقة / المقاطعة</label>
                                    <input type="text" id="state" placeholder="منطقة الرياض">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="zip">الرمز البريدي *</label>
                                    <input type="text" id="zip" placeholder="12345" required>
                                </div>
                                <div class="form-group">
                                    <label for="country">الدولة *</label>
                                    <select id="country" required>
                                        <option value="">اختر الدولة</option>
                                        <option value="SA" selected>المملكة العربية السعودية</option>
                                        <option value="AE">الإمارات العربية المتحدة</option>
                                        <option value="KW">الكويت</option>
                                        <option value="QA">قطر</option>
                                        <option value="OM">عمان</option>
                                        <option value="BH">البحرين</option>
                                        <option value="EG">مصر</option>
                                        <option value="JO">الأردن</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary checkout-next" onclick="goToStep(2)">المتابعة
                                للدفع <i class="fas fa-arrow-left"></i></button>
                        </form>
                    </div>

                    <!-- الخطوة 2: الدفع -->
                    <div class="checkout-step" id="step2">
                        <h2>طريقة الدفع</h2>
                        <p class="checkout-step__desc">اختر طريقة الدفع المفضلة لديك</p>

                        <div class="payment-methods">
                            <label class="payment-method active">
                                <input type="radio" name="payment" value="card" checked>
                                <div class="payment-method__content">
                                    <i class="far fa-credit-card"></i>
                                    <span>بطاقة ائتمان / مدى</span>
                                </div>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment" value="paypal">
                                <div class="payment-method__content">
                                    <i class="fab fa-paypal"></i>
                                    <span>بايبال (PayPal)</span>
                                </div>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment" value="stripe">
                                <div class="payment-method__content">
                                    <i class="fab fa-stripe-s"></i>
                                    <span>سترايب (Stripe)</span>
                                </div>
                            </label>
                        </div>

                        <!-- نموذج البطاقة -->
                        <div class="card-form" id="cardForm">
                            <div id="payment-element">
                                <!-- سيتم وضع عناصر Stripe هنا تلقائياً عبر JS -->
                            </div>
                            <div id="card-errors" role="alert" style="color: var(--color-danger); margin-top: 10px; font-size: 0.9rem;"></div>
                        </div>

                        <div class="checkout-actions">
                            <button type="button" class="btn btn-secondary" onclick="goToStep(1)"><i
                                    class="fas fa-arrow-right"></i> الرجوع</button>
                            <button type="button" class="btn btn-primary" onclick="goToStep(3)">مراجعة الطلب <i
                                    class="fas fa-arrow-left"></i></button>
                        </div>
                    </div>

                    <!-- الخطوة 3: المراجعة -->
                    <div class="checkout-step" id="step3">
                        <h2>مراجعة الطلب</h2>
                        <p class="checkout-step__desc">راجع تفاصيل طلبك قبل التأكيد</p>

                        <div class="review-section">
                            <div class="review-block">
                                <div class="review-block__header">
                                    <h4><i class="fas fa-map-marker-alt"></i> عنوان الشحن</h4>
                                    <button class="review-edit" onclick="goToStep(1)">تعديل</button>
                                </div>
                                <p id="reviewAddress">محمد أحمد<br>طريق الملك فهد<br>الرياض، 12345</p>
                            </div>
                            <div class="review-block">
                                <div class="review-block__header">
                                    <h4><i class="fas fa-credit-card"></i> طريقة الدفع</h4>
                                    <button class="review-edit" onclick="goToStep(2)">تعديل</button>
                                </div>
                                <p id="reviewPayment">بطاقة ائتمان تنتهي بـ ****</p>
                            </div>
                        </div>

                        <div class="review-items" id="reviewItems">
                            <!-- يتم تعبئتها بواسطة JS -->
                        </div>

                        <div class="checkout-actions">
                            <button type="button" class="btn btn-secondary" onclick="goToStep(2)"><i
                                    class="fas fa-arrow-right"></i> الرجوع</button>
                            <button type="button" class="btn btn-primary place-order-btn" onclick="placeOrder()"><i
                                    class="fas fa-lock"></i> تأكيد الطلب</button>
                        </div>
                    </div>

                    <!-- نجاح الطلب -->
                    <div class="checkout-step" id="stepSuccess" style="display:none;">
                        <div class="order-success">
                            <div class="success-icon"><i class="fas fa-check"></i></div>
                            <h2>تم تأكيد الطلب بنجاح!</h2>
                            <p>شكراً لشرائك. رقم طلبك هو <strong>#LP-2026-4281</strong> تم تأكيده.</p>
                            <p class="success-note">تم إرسال رسالة تأكيد إلكترونية إلى بريدك.</p>
                            <div id="successActions" style="display: flex; gap: 15px; justify-content: center; margin-top: 20px;">
                                <a href="{{ route('home') }}" class="btn btn-primary">متابعة التسوق</a>
                                <a href="#" id="downloadInvoiceBtn" class="btn btn-outline" style="display:none; border: 1px solid var(--color-gold); color: var(--color-gold);">
                                    <i class="fas fa-file-pdf"></i> تحميل الفاتورة
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ملخص الطلب -->
                <aside class="checkout-summary">
                    <h3>ملخص الطلب</h3>
                    <div class="checkout-summary__items" id="checkoutItems">
                        <!-- يتم تعبئتها بواسطة JS -->
                    </div>
                    <div class="checkout-summary__rows">
                        <div class="checkout-summary__row"><span>المجموع الفرعي</span><span
                                id="checkoutSubtotal">$0.00</span>
                        </div>
                        <div class="checkout-summary__row"><span>الشحن</span><span>مجاني</span></div>
                        <div class="checkout-summary__row"><span>الضريبة</span><span id="checkoutTax">$0.00</span></div>
                    </div>
                    <div class="checkout-summary__total">
                        <span>الإجمالي</span>
                        <span id="checkoutTotal">$0.00</span>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <div class="toast" id="toast"></div>
    <script src="{{ asset('assets/clints/js/app.js') }}"></script>
    <script src="{{ asset('assets/clints/js/checkout.js') }}"></script>
</body>

</html>