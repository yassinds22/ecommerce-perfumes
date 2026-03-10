<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>لوكس بارفيوم — اكتشف عطرك المميز</title>
  <meta name="description"
    content="اكتشف العطور الفاخرة المصنوعة من أجود المكونات. تسوق عطور فاخرة للرجال والنساء في لوكس بارفيوم.">
  <link rel="stylesheet" href="{{asset('assets/clints/css/style.css')}}">
  <link rel="stylesheet" href="{{asset('assets/clints/css/homepage.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

  <!-- ========== شريط التنقل ========== -->
  @include('clints.layout.nav')

 

  <!-- ========== قسم البطل ========== -->
  <section class="hero" id="hero">
    <div class="hero__bg">
      <img src="{{asset('assets/clints/images/hero-perfume.png')}}" alt="عطر فاخر" class="hero__image" id="heroImage">
    </div>
    <div class="hero__overlay"></div>
    <div class="hero__content container">
      <span class="hero__label">المجموعة الحصرية 2026</span>
      <h1 class="hero__title">اكتشف عطرك<br><em>المميز</em></h1>
      <p class="hero__subtitle">انغمس في عالم العطور الفاخرة المصنوعة من أندر المكونات حول العالم. كل زجاجة تروي قصة من
        الأناقة والرقي.</p>
      <div class="hero__cta">
        <a href="{{ route('shop') }}" class="btn btn-primary">تسوق الآن <i class="fas fa-arrow-left"></i></a>
        <a href="#categories" class="btn btn-secondary">استكشف المجموعات</a>
      </div>
    </div>
    <div class="hero__scroll">
      <span>مرر</span>
      <div class="hero__scroll-line"></div>
    </div>
  </section>

  <!-- ========== التصنيفات ========== -->
  <section class="section categories" id="categories">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">تصفح</span>
        <h2>مجموعاتنا</h2>
        <p class="section-subtitle">استكشف عائلات العطور المختارة بعناية</p>
        <div class="divider"></div>
      </div>
      <div class="categories__grid reveal">
        @foreach($categories as $category)
        <a href="{{ route('shop', ['cat' => $category->id]) }}" class="category-card">
          <div class="category-card__circle">
            <img src="{{ $category->getFirstMediaUrl('images') ?: asset('assets/clints/images/perfume-collection.png') }}" alt="{{ $category->name }}">
          </div>
          <h4 class="category-card__name">{{ $category->name }}</h4>
          <span class="category-card__count">{{ $category->products_count }} منتج</span>
        </a>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ========== العروض الحصرية ========== -->
  <section class="section offers" id="offers">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">وفر أكثر</span>
        <h2>عروض حصرية</h2>
        <p class="section-subtitle">خصومات لا تفوت على مجموعاتنا الأكثر تميزاً</p>
        <div class="divider"></div>
      </div>
      <div class="offers__grid reveal">
        <div class="offer-card offer-card--gold">
          <div class="offer-card__content">
            <span class="offer-card__tag">عرض الصيف</span>
            <h3>خصم 20%</h3>
            <p>على جميع العطور الزهرية</p>
            <a href="{{ route('shop', ['cat' => 'women']) }}" class="btn btn-primary">استخدم الكود: SUMMER20</a>
          </div>
          <div class="offer-card__image">
            <img src="{{asset('assets/clints/images/womens-perfume.png')}}" alt="عرض خاص">
          </div>
        </div>
        <div class="offer-card offer-card--dark">
          <div class="offer-card__content">
            <span class="offer-card__tag">باقة فاخرة</span>
            <h3>اشتري 1 واحصل على 1 مجاناً</h3>
            <p>على عطور العود والمجموعات الشرقية</p>
            <a href="{{ route('shop', ['cat' => 'arabic']) }}" class="btn btn-primary">تسوق المجموعة</a>
          </div>
          <div class="offer-card__image">
            <img src="{{asset('assets/clints/images/arabic-perfume.png')}}" alt="عرض العود">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== العطور المميزة ========== -->
  <section class="section section--alt featured" id="featured">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">مختارات لك</span>
        <h2>العطور المميزة</h2>
        <p class="section-subtitle">تشكيلات مختارة بعناية من خبراء العطور لدينا</p>
        <div class="divider"></div>
      </div>
      <div class="featured__grid reveal" id="featuredGrid">
        @foreach($featuredProducts as $product)
        <div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}"
          data-img="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}">
          <div class="product-card__image">
            <img src="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}" alt="{{ $product->name }}">
            @if($product->created_at->gt(now()->subDays(7)))
              <span class="product-card__badge">جديد</span>
            @endif
            <div class="product-card__actions">
              <button aria-label="أضف للمفضلة"><i class="far fa-heart"></i></button>
              <button aria-label="عرض سريع" onclick="window.location.href='{{ route('product', $product->id) }}'"><i
                  class="far fa-eye"></i></button>
            </div>
          </div>
          <div class="product-card__info">
            <p class="product-card__brand">{{ $product->brand->name ?? 'لوكس بارفيوم' }}</p>
            <h4 class="product-card__name">{{ $product->name }}</h4>
            <div class="product-card__rating">
              <i class="fas fa-star star"></i><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                class="fas fa-star star"></i><i class="fas fa-star-half-alt star"></i>
              <span>(128)</span>
            </div>
            @if($product->sale_price)
              <p class="product-card__price"><span style="text-decoration: line-through; opacity: 0.6; font-size: 0.8em; margin-left: 8px">${{ $product->price }}</span> ${{ $product->sale_price }}</p>
            @else
              <p class="product-card__price">${{ $product->price }}</p>
            @endif
            <button class="product-card__btn add-to-cart-btn">أضف للسلة</button>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ========== الأكثر مبيعاً ========== -->
  @if($bestsellerProducts->count() > 0)
  <section class="section bestsellers" id="bestsellers">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">الأكثر طلباً</span>
        <h2>الأكثر مبيعاً</h2>
        <p class="section-subtitle">عطورنا الأكثر طلباً والمحبوبة من آلاف العملاء</p>
        <div class="divider"></div>
      </div>
      <div class="bestsellers__slider reveal">
        <div class="bestsellers__track" id="bestsellersTrack">
          @foreach($bestsellerProducts as $product)
          <div class="bestseller-card">
            <div class="bestseller-card__image">
              <img src="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/arabic-perfume.png') }}" alt="{{ $product->name }}">
            </div>
            <div class="bestseller-card__info">
              <span class="section-label">{{ $product->category->name ?? 'المجموعة المميزة' }}</span>
              <h3>{{ $product->name }}</h3>
              <p>{{ $product->short_description }}</p>
              <div class="bestseller-card__meta">
                <span class="bestseller-card__price">${{ $product->sale_price ?: $product->price }}</span>
                <div class="bestseller-card__rating">
                  <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                    class="fas fa-star"></i><i class="fas fa-star"></i>
                  <span>5.0</span>
                </div>
              </div>
              <a href="{{ route('product', $product->id) }}" class="btn btn-outline-gold">اكتشف <i class="fas fa-arrow-left"></i></a>
            </div>
          </div>
          @endforeach
        </div>
        <div class="bestsellers__nav">
          <button class="bestsellers__btn" id="bsPrev"><i class="fas fa-arrow-right"></i></button>
          <div class="bestsellers__dots" id="bsDots">
            @foreach($bestsellerProducts as $index => $product)
            <span class="dot {{ $index === 0 ? 'active' : '' }}"></span>
            @endforeach
          </div>
          <button class="bestsellers__btn" id="bsNext"><i class="fas fa-arrow-left"></i></button>
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- ========== المنتجات المفضلة ========== -->
  <section class="section favorites" id="favorites">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">اختيار العملاء</span>
        <h2>منتجات نالت إعجابكم</h2>
        <p class="section-subtitle">اكتشف المنتجات الأكثر تقييماً وتفضيلاً من قبل عملائنا</p>
        <div class="divider"></div>
      </div>
      <div class="favorites__grid reveal">
        @foreach($favoriteProducts as $product)
        <div class="product-card" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}"
          data-img="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}">
          <div class="product-card__image">
            <img src="{{ $product->getFirstMediaUrl('images') ?: asset('assets/clints/images/mens-perfume.png') }}" alt="{{ $product->name }}">
            <span class="product-card__badge">موصى به</span>
            <div class="product-card__actions">
              <button aria-label="أضف للمفضلة"><i class="far fa-heart"></i></button>
              <button aria-label="عرض سريع" onclick="window.location.href='{{ route('product', $product->id) }}'"><i
                  class="far fa-eye"></i></button>
            </div>
          </div>
          <div class="product-card__info">
            <p class="product-card__brand">{{ $product->brand->name ?? 'لوكس بارفيوم' }}</p>
            <h4 class="product-card__name">{{ $product->name }}</h4>
            <div class="product-card__rating">
              <i class="fas fa-star star"></i><i class="fas fa-star star"></i><i class="fas fa-star star"></i><i
                class="fas fa-star star"></i><i class="fas fa-star star"></i>
              <span>(182)</span>
            </div>
            @if($product->sale_price)
              <p class="product-card__price"><span style="text-decoration: line-through; opacity: 0.6; font-size: 0.8em; margin-left: 8px">${{ $product->price }}</span> ${{ $product->sale_price }}</p>
            @else
              <p class="product-card__price">${{ $product->price }}</p>
            @endif
            <button class="product-card__btn add-to-cart-btn">أضف للسلة</button>
          </div>
        </div>
        @endforeach
      </div>
      <div class="section-footer reveal" style="text-align: center; margin-top: 50px;">
        <a href="{{ route('shop') }}" class="btn btn-outline-gold">عرض الكل</a>
      </div>
    </div>
  </section>

  <!-- ========== قصة العلامة ========== -->
  <section class="section section--alt brand-story" id="brandStory">
    <div class="container">
      <div class="brand-story__wrapper reveal">
        <div class="brand-story__image">
          <img src="{{asset('assets/clints/images/brand-story.png')}}" alt="حرفيتنا">
          <div class="brand-story__accent"></div>
        </div>
        <div class="brand-story__content">
          <span class="section-label">فلسفتنا</span>
          <h2>فن صناعة العطور</h2>
          <p class="brand-story__text">
            في لوكس بارفيوم، كل عطر هو تحفة فنية ولدت من أجيال من الخبرة في صناعة العطور. نستورد أندر المكونات من حول
            العالم — من حقول الورد البلغارية إلى غابات خشب الصندل الهندية — لنصنع كل تركيبة بعناية فائقة.
          </p>
          <div class="brand-story__pillars">
            <div class="pillar">
              <i class="fas fa-gem"></i>
              <h4>مكونات نادرة</h4>
              <p>مستوردة من أكثر من 30 دولة حول العالم</p>
            </div>
            <div class="pillar">
              <i class="fas fa-flask"></i>
              <h4>حرفية عالية</h4>
              <p>صُنعت بأيدي خبراء عطور عالميين</p>
            </div>
            <div class="pillar">
              <i class="fas fa-crown"></i>
              <h4>إرث من الفخامة</h4>
              <p>إرث يمتد لثلاثة عقود</p>
            </div>
          </div>
          <a href="#" class="btn btn-outline-gold">قصتنا <i class="fas fa-arrow-left"></i></a>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== آراء العملاء ========== -->
  <section class="section testimonials" id="testimonials">
    <div class="container">
      <div class="section-header reveal">
        <span class="section-label">ماذا يقولون</span>
        <h2>آراء عملائنا</h2>
        <p class="section-subtitle">اكتشف لماذا يعود عملاؤنا مراراً وتكراراً</p>
        <div class="divider"></div>
      </div>
      <div class="testimonials__grid reveal">
        <div class="testimonial-card">
          <div class="testimonial-card__stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
              class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="testimonial-card__text">"عنبر عود ملكي عطر رائع حقاً. ثباته مذهل وأتلقى إطراءات في كل مرة أرتديه
            فيها. هذا هو الفخامة الحقيقية في زجاجة."</p>
          <div class="testimonial-card__author">
            <div class="testimonial-card__avatar">سأ</div>
            <div>
              <h4>سارة أحمد</h4>
              <span>مشترية موثقة</span>
            </div>
          </div>
        </div>
        <div class="testimonial-card">
          <div class="testimonial-card__stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
              class="fas fa-star"></i><i class="fas fa-star"></i>
          </div>
          <p class="testimonial-card__text">"التغليف وحده يتحدث عن الجودة. روز ميستيك أصبح عطري المفضل — أنثوي، راقي،
            وأخاذ تماماً. يستحق كل قرش."</p>
          <div class="testimonial-card__author">
            <div class="testimonial-card__avatar">مخ</div>
            <div>
              <h4>مريم خالد</h4>
              <span>مشترية موثقة</span>
            </div>
          </div>
        </div>
        <div class="testimonial-card">
          <div class="testimonial-card__stars">
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
              class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
          </div>
          <p class="testimonial-card__text">"أستكشف العطور الفاخرة منذ سنوات، ولوكس بارفيوم من أرقى ما وجدت. نوار
            إيليغانس هو العطر المثالي للمساء — جريء، راقي، لا يُنسى."</p>
          <div class="testimonial-card__author">
            <div class="testimonial-card__avatar">عم</div>
            <div>
              <h4>عمر محمد</h4>
              <span>مشتري موثق</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== النشرة البريدية ========== -->
  <section class="section newsletter" id="newsletter">
    <div class="container">
      <div class="newsletter__wrapper reveal">
        <div class="newsletter__content">
          <span class="section-label">ابقَ على اتصال</span>
          <h2>انضم لعالم العطور</h2>
          <p class="section-subtitle">كن أول من يكتشف المجموعات الجديدة والعروض الحصرية ونصائح العطور من خبرائنا.</p>
          <form class="newsletter__form" id="newsletterForm">
            <div class="newsletter__input-group">
              <input type="email" placeholder="أدخل بريدك الإلكتروني" required aria-label="البريد الإلكتروني">
              <button type="submit" class="btn btn-primary">اشترك <i class="fas fa-paper-plane"></i></button>
            </div>
            <p class="newsletter__note">بالاشتراك، توافق على سياسة الخصوصية. يمكنك إلغاء الاشتراك في أي وقت.</p>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- ========== التذييل ========== -->
  @include('clints.layout.footer')
  <script src="{{asset('assets/clints/js/homepage.js')}}"></script>
</body>

</html>
