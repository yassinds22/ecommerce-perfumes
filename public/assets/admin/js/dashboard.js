/* ==========================================
   dashboard.js — Admin Dashboard Logic
   ========================================== */

// ===== SAMPLE DATA =====
const sampleProducts = [
    { id: 1, name: 'نوار إيليغانس', brand: 'لوكس بارفيوم', price: 185, category: 'رجالي', stock: 45, rating: 4.5, reviews: 128, badge: 'جديد', img: '../images/mens-perfume.png' },
    { id: 2, name: 'روز ميستيك', brand: 'لوكس بارفيوم', price: 220, category: 'نسائي', stock: 32, rating: 5.0, reviews: 256, badge: '', img: '../images/womens-perfume.png' },
    { id: 3, name: 'عنبر عود ملكي', brand: 'لوكس بارفيوم', price: 340, category: 'عربي', stock: 18, rating: 5.0, reviews: 312, badge: 'الأكثر مبيعاً', img: '../images/arabic-perfume.png' },
    { id: 4, name: 'فيلفيت سانتال', brand: 'لوكس بارفيوم', price: 195, category: 'مشترك', stock: 56, rating: 4.0, reviews: 89, badge: '', img: '../images/unisex-perfume.png' },
    { id: 5, name: 'ياسمين ذهبي', brand: 'لوكس بارفيوم', price: 275, category: 'نسائي', stock: 12, rating: 4.5, reviews: 167, badge: 'محدود', img: '../images/womens-perfume.png' },
    { id: 6, name: 'ميدنايت ليذر', brand: 'لوكس بارفيوم', price: 210, category: 'رجالي', stock: 38, rating: 4.0, reviews: 94, badge: '', img: '../images/mens-perfume.png' },
    { id: 7, name: 'إيريس أبسولو', brand: 'فلور دو لوكس', price: 290, category: 'نسائي', stock: 24, rating: 5.0, reviews: 201, badge: '', img: '../images/womens-perfume.png' },
    { id: 8, name: 'أرز ودخان', brand: 'نوار أتيلييه', price: 165, category: 'مشترك', stock: 60, rating: 4.0, reviews: 76, badge: 'تخفيض', img: '../images/unisex-perfume.png' },
    { id: 9, name: 'عود ملكي مكثف', brand: 'مجموعة العود', price: 420, category: 'عربي', stock: 8, rating: 5.0, reviews: 189, badge: '', img: '../images/arabic-perfume.png' },
];

const sampleOrders = [
    { id: '#ORD-2061', customer: 'سارة أحمد', email: 'sara@email.com', items: 3, total: 745, status: 'completed', date: '2026-03-07' },
    { id: '#ORD-2060', customer: 'أحمد خالد', email: 'ahmed@email.com', items: 1, total: 340, status: 'shipped', date: '2026-03-07' },
    { id: '#ORD-2059', customer: 'مريم خالد', email: 'mariam@email.com', items: 2, total: 495, status: 'pending', date: '2026-03-06' },
    { id: '#ORD-2058', customer: 'عمر محمد', email: 'omar@email.com', items: 1, total: 220, status: 'completed', date: '2026-03-06' },
    { id: '#ORD-2057', customer: 'نورة العلي', email: 'noura@email.com', items: 4, total: 980, status: 'shipped', date: '2026-03-05' },
    { id: '#ORD-2056', customer: 'ياسر حسن', email: 'yasser@email.com', items: 2, total: 550, status: 'completed', date: '2026-03-05' },
    { id: '#ORD-2055', customer: 'فاطمة علي', email: 'fatima@email.com', items: 1, total: 185, status: 'cancelled', date: '2026-03-04' },
];

const sampleCustomers = [
    { id: 1, name: 'سارة أحمد', email: 'sara@email.com', orders: 12, spent: 4280, joined: '2025-08-15' },
    { id: 2, name: 'أحمد خالد', email: 'ahmed@email.com', orders: 8, spent: 2950, joined: '2025-09-22' },
    { id: 3, name: 'مريم خالد', email: 'mariam@email.com', orders: 15, spent: 5120, joined: '2025-06-10' },
    { id: 4, name: 'عمر محمد', email: 'omar@email.com', orders: 5, spent: 1680, joined: '2025-11-03' },
    { id: 5, name: 'نورة العلي', email: 'noura@email.com', orders: 20, spent: 7350, joined: '2025-05-18' },
    { id: 6, name: 'ياسر حسن', email: 'yasser@email.com', orders: 3, spent: 890, joined: '2026-01-12' },
];

const sampleReviews = [
    { id: 1, product: 'عنبر عود ملكي', customer: 'أحمد خالد', rating: 5, title: 'عطر رائع بكل المقاييس', date: '2026-02-28', verified: true },
    { id: 2, product: 'روز ميستيك', customer: 'مريم خالد', rating: 5, title: 'أنثوي وراقي', date: '2026-02-20', verified: true },
    { id: 3, product: 'عنبر عود ملكي', customer: 'نورة العلي', rating: 5, title: 'هدية مثالية', date: '2026-02-15', verified: true },
    { id: 4, product: 'نوار إيليغانس', customer: 'عمر محمد', rating: 4, title: 'جريء وأنيق', date: '2026-02-10', verified: true },
    { id: 5, product: 'فيلفيت سانتال', customer: 'ياسر حسن', rating: 4, title: 'فخامة في زجاجة', date: '2026-01-20', verified: false },
];

const sampleOffers = [
    { id: 1, tag: 'عرض الصيف', title: 'خصم 20%', desc: 'على جميع العطور الزهرية', code: 'SUMMER20', expiry: '2026-06-30' },
    { id: 2, tag: 'باقة فاخرة', title: 'اشتري 1 واحصل على 1 مجاناً', desc: 'على عطور العود والمجموعات الشرقية', code: 'BOGO', expiry: '2026-04-15' },
];

// ===== NAVIGATION =====
document.addEventListener('DOMContentLoaded', () => {
    const sidebarLinks = document.querySelectorAll('.sidebar__link[data-section]');
    const sections = document.querySelectorAll('.dashboard-section');
    const headerTitle = document.querySelector('.header__title h1');
    const headerDesc = document.querySelector('.header__title p');

    const sectionTitles = {
        overview: { title: 'نظرة عامة', desc: 'مرحباً بك في لوحة التحكم' },
        products: { title: 'المنتجات', desc: 'إدارة منتجات المتجر' },
        orders: { title: 'الطلبات', desc: 'تتبع وإدارة طلبات العملاء' },
        customers: { title: 'العملاء', desc: 'قاعدة بيانات العملاء' },
        reviews: { title: 'التقييمات', desc: 'تقييمات وآراء العملاء' },
        offers: { title: 'العروض', desc: 'إدارة العروض والخصومات' },
    };

    sidebarLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const sectionId = link.dataset.section;

            sidebarLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');

            sections.forEach(s => s.classList.remove('active'));
            const target = document.getElementById(sectionId);
            if (target) target.classList.add('active');

            if (sectionTitles[sectionId]) {
                headerTitle.textContent = sectionTitles[sectionId].title;
                headerDesc.textContent = sectionTitles[sectionId].desc;
            }

            // Close mobile sidebar
            document.querySelector('.sidebar').classList.remove('active');
            document.querySelector('.sidebar-overlay').classList.remove('active');
        });
    });

    // Mobile toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');

    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });
    }

    // Initialize
    renderOverviewCharts();
    renderProductsTable();
    renderOrdersTable();
    renderCustomersTable();
    renderReviewsTable();
    renderOffersTable();
    animateCounters();
});

// ===== ANIMATE COUNTERS =====
function animateCounters() {
    document.querySelectorAll('.stat-card__value').forEach(el => {
        const target = el.dataset.value;
        if (!target) return;
        const isPrice = target.startsWith('$');
        const numTarget = parseFloat(target.replace(/[$,]/g, ''));
        let current = 0;
        const increment = numTarget / 40;
        const timer = setInterval(() => {
            current += increment;
            if (current >= numTarget) {
                current = numTarget;
                clearInterval(timer);
            }
            if (isPrice) {
                el.textContent = '$' + Math.floor(current).toLocaleString();
            } else {
                el.textContent = Math.floor(current).toLocaleString();
            }
        }, 30);
    });
}

// ===== OVERVIEW CHARTS =====
function renderOverviewCharts() {
    // Bar chart
    const bars = document.querySelectorAll('.chart-bar');
    const heights = [65, 45, 80, 55, 70, 90, 60, 50, 75, 85, 95, 72];
    bars.forEach((bar, i) => {
        setTimeout(() => {
            bar.style.height = (heights[i] || 50) + '%';
        }, i * 80);
    });
}

// ===== PRODUCTS TABLE =====
function renderProductsTable() {
    const tbody = document.getElementById('productsTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    sampleProducts.forEach(p => {
        const badgeHtml = p.badge ? `<span class="status-badge shipped">${p.badge}</span>` : '—';
        const starsHtml = getStarsHtml(p.rating);
        tbody.innerHTML += `
      <tr>
        <td>
          <div class="table-product">
            <div class="table-product__img"><img src="${p.img}" alt="${p.name}"></div>
            <div>
              <div class="table-product__name">${p.name}</div>
              <div class="table-product__brand">${p.brand}</div>
            </div>
          </div>
        </td>
        <td>${p.category}</td>
        <td>$${p.price}</td>
        <td>${p.stock}</td>
        <td><div class="stars">${starsHtml}</div><span style="font-size:0.7rem;color:var(--color-text-muted)">(${p.reviews})</span></td>
        <td>${badgeHtml}</td>
        <td>
          <div class="table-actions">
            <button title="تعديل"><i class="fas fa-pen"></i></button>
            <button class="delete" title="حذف"><i class="fas fa-trash"></i></button>
          </div>
        </td>
      </tr>
    `;
    });
}

// ===== ORDERS TABLE =====
function renderOrdersTable() {
    const tbody = document.getElementById('ordersTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    const statusLabels = { completed: 'مكتمل', pending: 'قيد الانتظار', shipped: 'تم الشحن', cancelled: 'ملغي' };
    sampleOrders.forEach(o => {
        tbody.innerHTML += `
      <tr>
        <td style="font-weight:600;color:var(--color-gold)">${o.id}</td>
        <td>
          <div class="table-product__name">${o.customer}</div>
          <div class="table-product__brand">${o.email}</div>
        </td>
        <td>${o.items} منتج</td>
        <td style="font-weight:600">$${o.total}</td>
        <td><span class="status-badge ${o.status}">${statusLabels[o.status]}</span></td>
        <td>${o.date}</td>
        <td>
          <div class="table-actions">
            <button title="عرض"><i class="fas fa-eye"></i></button>
            <button title="تعديل"><i class="fas fa-pen"></i></button>
          </div>
        </td>
      </tr>
    `;
    });
}

// ===== CUSTOMERS TABLE =====
function renderCustomersTable() {
    const tbody = document.getElementById('customersTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    sampleCustomers.forEach(c => {
        const initials = c.name.substring(0, 2);
        tbody.innerHTML += `
      <tr>
        <td>
          <div class="table-product">
            <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--color-gold),var(--color-gold-dark));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;color:#fff;flex-shrink:0">${initials}</div>
            <div>
              <div class="table-product__name">${c.name}</div>
              <div class="table-product__brand">${c.email}</div>
            </div>
          </div>
        </td>
        <td>${c.orders}</td>
        <td style="font-weight:600">$${c.spent.toLocaleString()}</td>
        <td>${c.joined}</td>
        <td>
          <div class="table-actions">
            <button title="عرض"><i class="fas fa-eye"></i></button>
          </div>
        </td>
      </tr>
    `;
    });
}

// ===== REVIEWS TABLE =====
function renderReviewsTable() {
    const tbody = document.getElementById('reviewsTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    sampleReviews.forEach(r => {
        const starsHtml = getStarsHtml(r.rating);
        const verifiedHtml = r.verified
            ? '<span style="color:var(--color-success);font-size:0.75rem"><i class="fas fa-check-circle"></i> موثق</span>'
            : '<span style="color:var(--color-text-dim);font-size:0.75rem">غير موثق</span>';
        tbody.innerHTML += `
      <tr>
        <td style="font-weight:600">${r.product}</td>
        <td>${r.customer}</td>
        <td><div class="stars">${starsHtml}</div></td>
        <td>${r.title}</td>
        <td>${verifiedHtml}</td>
        <td>${r.date}</td>
        <td>
          <div class="table-actions">
            <button class="delete" title="حذف"><i class="fas fa-trash"></i></button>
          </div>
        </td>
      </tr>
    `;
    });
}

// ===== OFFERS TABLE =====
function renderOffersTable() {
    const tbody = document.getElementById('offersTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    sampleOffers.forEach(o => {
        tbody.innerHTML += `
      <tr>
        <td><span class="status-badge shipped">${o.tag}</span></td>
        <td style="font-weight:600">${o.title}</td>
        <td>${o.desc}</td>
        <td style="font-weight:600;color:var(--color-gold)">${o.code}</td>
        <td>${o.expiry}</td>
        <td>
          <div class="table-actions">
            <button title="تعديل"><i class="fas fa-pen"></i></button>
            <button class="delete" title="حذف"><i class="fas fa-trash"></i></button>
          </div>
        </td>
      </tr>
    `;
    });
}

// ===== HELPERS =====
function getStarsHtml(rating) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= Math.floor(rating)) html += '<i class="fas fa-star"></i>';
        else if (i - 0.5 <= rating) html += '<i class="fas fa-star-half-alt"></i>';
        else html += '<i class="far fa-star" style="color:var(--color-text-dim)"></i>';
    }
    return html;
}

// ===== MODAL =====
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('active');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('active');
}

// Close modal on overlay click
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('active');
    }
});

// ===== TOAST =====
function showDashToast(message) {
    const toast = document.getElementById('dashToast');
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('visible');
    setTimeout(() => toast.classList.remove('visible'), 3000);
}
