  <footer class="footer" id="footer">
    <div class="container">
      <div class="footer__grid">
        <div class="footer__brand">
          <a href="{{ route('home') }}" class="nav-logo">لوكس <span>بارفيوم</span></a>
          <p>نصنع عطوراً استثنائية تروي قصتك الفريدة. كل رائحة هي رحلة من الأناقة والاكتشاف.</p>
          <div class="footer__social">
            <a href="#" aria-label="إنستغرام"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="فيسبوك"><i class="fab fa-facebook-f"></i></a>
            <a href="#" aria-label="تويتر"><i class="fab fa-twitter"></i></a>
            <a href="#" aria-label="بنترست"><i class="fab fa-pinterest-p"></i></a>
          </div>
        </div>
        <div class="footer__col">
          <h4>المتجر</h4>
          <a href="{{ route('shop') }}">جميع العطور</a>
          <a href="{{ route('shop', ['cat' => 'men']) }}">مجموعة الرجال</a>
          <a href="{{ route('shop', ['cat' => 'women']) }}">مجموعة النساء</a>
          <a href="{{ route('shop', ['cat' => 'new']) }}">وصل حديثاً</a>
          <a href="{{ route('shop', ['cat' => 'gifts']) }}">أطقم هدايا</a>
        </div>
        <div class="footer__col">
          <h4>خدمة العملاء</h4>
          <a href="#">اتصل بنا</a>
          <a href="#">الشحن والإرجاع</a>
          <a href="#">الأسئلة الشائعة</a>
          <a href="#">تتبع الطلب</a>
          <a href="#">دليل المقاسات</a>
        </div>
        <div class="footer__col">
          <h4>من نحن</h4>
          <a href="#">قصتنا</a>
          <a href="#">الحرفية</a>
          <a href="#">الاستدامة</a>
          <a href="#">الوظائف</a>
          <a href="#">الصحافة</a>
        </div>
      </div>
      <div class="footer__bottom">
        <p>&copy; 2026 لوكس بارفيوم. جميع الحقوق محفوظة.</p>
        <div class="footer__payments">
          <i class="fab fa-cc-visa"></i>
          <i class="fab fa-cc-mastercard"></i>
          <i class="fab fa-cc-amex"></i>
          <i class="fab fa-cc-paypal"></i>
        </div>
      </div>
    </div>
  </footer>

  <!-- إشعار -->
  <div class="toast" id="toast"></div>

  <!-- العودة للأعلى -->
  <button class="back-to-top" id="backToTop" aria-label="العودة للأعلى"><i class="fas fa-chevron-up"></i></button>

  <script src="{{asset('assets/clints/js/app.js')}}"></script>
