<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سلة التسوق — لوكس بارفيوم</title>
    <meta name="description" content="راجع سلة تسوقك وتابع لإتمام الشراء في لوكس بارفيوم.">
    <link rel="stylesheet" href="{{asset('assets/clints/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/clints/css/cart.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <!-- ========== شريط التنقل ========== -->
    @include('clints.layout.nav')

 

    <!-- رأس الصفحة -->
    <div class="page-spacer"></div>
    <section class="cart-header">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">الرئيسية</a><span class="separator">/</span><span class="current">حقيبة
                    التسوق</span>
            </div>
            <h1>حقيبة التسوق</h1>
        </div>
    </section>

    <!-- محتوى السلة -->
    <section class="section cart-section">
        <div class="container">
            <!-- حالة فارغة -->
            <div class="cart-empty" id="cartEmpty" style="display:none;">
                <i class="fas fa-shopping-bag"></i>
                <h2>سلة التسوق فارغة</h2>
                <p>اكتشف عطورنا الفاخرة وابحث عن عطرك المثالي</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">ابدأ التسوق</a>
            </div>

            <!-- عناصر السلة -->
            <div class="cart-layout" id="cartLayout">
                <div class="cart-table-wrapper">
                    <table class="cart-table" id="cartTable">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>المجموع</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                            <!-- يتم تعبئتها بواسطة JS -->
                        </tbody>
                    </table>
                </div>

                <!-- ملخص السلة -->
                <aside class="cart-summary">
                    <h3>ملخص الطلب</h3>
                    <div class="cart-summary__rows">
                        <div class="cart-summary__row"><span>المجموع الفرعي</span><span
                                id="summarySubtotal">$0.00</span>
                        </div>
                        <div class="cart-summary__row"><span>الشحن</span><span id="summaryShipping">يتم حسابه عند
                                الدفع</span></div>
                        <div class="cart-summary__row"><span>الضريبة التقديرية</span><span id="summaryTax">$0.00</span>
                        </div>
                    </div>
                    <div class="cart-summary__promo">
                        <input type="text" placeholder="كود الخصم" id="promoInput">
                        <button class="btn btn-outline-gold" onclick="showToast('تم تطبيق كود الخصم!')">تطبيق</button>
                    </div>
                    <div class="cart-summary__total">
                        <span>المجموع الكلي</span>
                        <span id="summaryTotal">$0.00</span>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-primary cart-checkout-btn">
                        المتابعة لإتمام الشراء <i class="fas fa-arrow-left"></i>
                    </a>
                    <div class="cart-summary__guarantees">
                        <div><i class="fas fa-lock"></i> دفع آمن 100%</div>
                        <div><i class="fas fa-truck"></i> شحن مجاني للطلبات فوق $200</div>
                        <div><i class="fas fa-undo"></i> سياسة إرجاع لمدة 30 يوم</div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <!-- ========== التذييل ========== -->
    @include('clints.layout.footer')

    <script src="{{asset('assets/clints/js/cart.js')}}"></script>
</body>

</html>