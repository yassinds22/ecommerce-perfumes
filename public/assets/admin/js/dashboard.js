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
    settings: { title: 'الإعدادات', desc: 'إدارة إعدادات وتكوين الموقع' },
  };

  sidebarLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const sectionId = link.dataset.section;
      const target = document.getElementById(sectionId);

      if (target) {
        e.preventDefault();

        sidebarLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');

        target.classList.add('active');

        if (sectionTitles[sectionId]) {
          headerTitle.textContent = sectionTitles[sectionId].title;
          headerDesc.textContent = sectionTitles[sectionId].desc;
        }

        // Close mobile sidebar
        document.querySelector('.sidebar').classList.remove('active');
        document.querySelector('.sidebar-overlay').classList.remove('active');
      }
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
  // renderProductsTable(); // Now dynamic
  // renderOrdersTable(); // Now dynamic
  // renderCustomersTable(); // Now dynamic
  // renderReviewsTable(); // Now dynamic
  // renderOffersTable(); // Now dynamic
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
  const container = document.getElementById('salesChartContainer');
  if (!container) return;
  const salesData = JSON.parse(container.dataset.sales);
  const maxSales = Math.max(...salesData.map(d => d.value), 100) || 100;

  const bars = document.querySelectorAll('.chart-bar');
  bars.forEach((bar, i) => {
    const val = salesData[i] ? salesData[i].value : 0;
    const height = (val / maxSales) * 100;
    setTimeout(() => {
      bar.style.height = (height < 5 && val > 0 ? 5 : height) + '%';
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
// ===== ORDER MANAGEMENT =====
function editShipping(id, company, tracking) {
  document.getElementById('shippingOrderId').value = id;
  document.getElementById('shippingCompany').value = company || '';
  document.getElementById('trackingNumber').value = tracking || '';
  openModal('editShippingModal');
}

function editOrderStatus(id, status) {
  document.getElementById('statusOrderId').value = id;
  document.getElementById('orderStatusSelect').value = status;
  openModal('editStatusModal');
}

function viewOrder(id) {
  fetch(`/admin/orders/${id}`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
    .then(resp => resp.json())
    .then(order => {
      // For now, let's just show a simple alert or log. 
      // In a real app, we'd open a detailed view modal.
      console.log('Order Details:', order);
      alert(`تفاصيل الطلب #ORD-${order.id}\nالعميل: ${order.user.name}\nالمبلغ: $${order.total}\nالحالة: ${order.status}`);
    });
}

// Form listeners for Orders
document.addEventListener('DOMContentLoaded', () => {
  const shippingForm = document.getElementById('shippingForm');
  if (shippingForm) {
    shippingForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const id = document.getElementById('shippingOrderId').value;
      const formData = new FormData(shippingForm);

      fetch(`/admin/orders/${id}/shipping`, {
        method: 'POST', // We use POST with _method PUT in Blade
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
        .then(resp => resp.json())
        .then(data => {
          if (data.success) {
            showDashToast(data.message);
            closeModal('editShippingModal');
            setTimeout(() => location.reload(), 1500); // Reload to see changes
          }
        });
    });
  }

  const statusForm = document.getElementById('statusForm');
  if (statusForm) {
    statusForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const id = document.getElementById('statusOrderId').value;
      const formData = new FormData(statusForm);

      fetch(`/admin/orders/${id}/status`, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      })
        .then(resp => resp.json())
        .then(data => {
          if (data.success) {
            showDashToast(data.message);
            closeModal('editStatusModal');
            setTimeout(() => location.reload(), 1500);
          }
        });
    });
  }
});

// ===== REVIEW MODERATION =====
function approveReview(id) {
  if (!confirm('هل تريد اعتماد هذا التقييم ليظهر في الموقع؟')) return;

  fetch(`/admin/reviews/${id}/approve`, {
    method: 'PATCH',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(resp => resp.json())
    .then(data => {
      if (data.success) {
        showDashToast(data.message);
        setTimeout(() => location.reload(), 1000);
      }
    });
}

function rejectReview(id) {
  if (!confirm('هل تريد إلغاء اعتماد هذا التقييم؟ سيختفي من الموقع.')) return;

  fetch(`/admin/reviews/${id}/reject`, {
    method: 'PATCH',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(resp => resp.json())
    .then(data => {
      if (data.success) {
        showDashToast(data.message);
        setTimeout(() => location.reload(), 1000);
      }
    });
}

function toggleVerifiedReview(id) {
  fetch(`/admin/reviews/${id}/toggle-verified`, {
    method: 'PATCH',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(resp => resp.json())
    .then(data => {
      if (data.success) {
        showDashToast(data.message);
        setTimeout(() => location.reload(), 1000);
      }
    });
}

function deleteReview(id) {
  if (!confirm('هل أنت متأكد من حذف هذا التقييم نهائياً؟')) return;

  fetch(`/admin/reviews/${id}`, {
    method: 'DELETE',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
  })
    .then(resp => resp.json())
    .then(data => {
      if (data.success) {
        showDashToast(data.message);
        setTimeout(() => location.reload(), 1000);
      }
    });
}

function filterReviews() {
  const rating = document.getElementById('filterReviewRating').value;
  const status = document.getElementById('filterReviewStatus').value;

  const params = new URLSearchParams();
  if (rating) params.append('rating', rating);
  if (status) params.append('status', status);

  const url = `/admin/reviews?${params.toString()}`;

  fetch(url, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
    .then(resp => resp.text())
    .then(html => {
      document.getElementById('reviews').innerHTML = html;
      // Re-bind events if necessary or reload part
      // Since we are replacing the whole section, it should work 
      // but usually it's better to just reload the table body.
      // For simplicity in this layout, we reload or replace.
      window.location.href = url; // Full reload for now to keep it simple and clean with the existing logic
    });
}

// ===== Notifications =====
const notifBtn = document.getElementById('notifBtn');
const notifMenu = document.getElementById('notifMenu');
const notifList = document.getElementById('notifList');

if (notifBtn) {
  notifBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    notifMenu.classList.toggle('active');
    if (notifMenu.classList.contains('active')) {
      fetchNotifications();
    }
  });
}

document.addEventListener('click', (e) => {
  if (notifMenu && !notifMenu.contains(e.target) && !notifBtn.contains(e.target)) {
    notifMenu.classList.remove('active');
  }
});

function fetchNotifications() {
  fetch('/admin/notifications')
    .then(res => res.json())
    .then(data => {
      if (data.length === 0) {
        notifList.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--color-text-dim)">لا توجد تنبيهات جديدة</div>';
        return;
      }

      notifList.innerHTML = '';
      data.forEach(notif => {
        const isUnread = !notif.read_at;
        const item = document.createElement('div');
        item.className = `notif-item ${isUnread ? 'unread' : ''}`;
        item.innerHTML = `
                    <div class="notif-icon" style="background: rgba(201, 168, 76, 0.1); color: var(--color-gold)">
                        <i class="${notif.data.icon || 'fas fa-info-circle'}"></i>
                    </div>
                    <div class="notif-content">
                        <h4>${notif.data.title}</h4>
                        <p>${notif.data.message}</p>
                        <span class="notif-time">${formatTime(notif.created_at)}</span>
                    </div>
                `;
        item.onclick = (e) => {
          e.stopPropagation();
          markAsRead(notif.id, item);
        };
        notifList.appendChild(item);
      });
    });
}

function markAsRead(id, element) {
  if (!element.classList.contains('unread')) return;

  fetch(`/admin/notifications/${id}/read`, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
      'Content-Type': 'application/json'
    }
  }).then(() => {
    element.classList.remove('unread');
    updateNotifBadge();
  });
}

function markAllAsRead(e) {
  if (e) e.preventDefault();
  fetch('/admin/notifications/read-all', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
    }
  }).then(() => {
    document.querySelectorAll('.notif-item').forEach(el => el.classList.remove('unread'));
    updateNotifBadge();
  });
}

function updateNotifBadge() {
  const dot = document.getElementById('notifDot');
  const hasUnread = document.querySelector('.notif-item.unread');
  if (dot && !hasUnread) {
    dot.style.display = 'none';
  }
}

function formatTime(dateStr) {
  const date = new Date(dateStr);
  const now = new Date();
  const diff = Math.floor((now - date) / 1000);

  if (diff < 60) return 'الآن';
  if (diff < 3600) return `منذ ${Math.floor(diff / 60)} دقيقة`;
  if (diff < 86400) return `منذ ${Math.floor(diff / 3600)} ساعة`;
  return date.toLocaleDateString('ar-EG');
}

// ===== STAT CARDS GLOW EFFECT =====
document.addEventListener('mousemove', e => {
  document.querySelectorAll('.stat-card').forEach(card => {
    const rect = card.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    card.style.setProperty('--mouse-x', `${x}px`);
    card.style.setProperty('--mouse-y', `${y}px`);
  });
});
