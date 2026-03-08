/* ==========================================
   app.js — Global: Navigation, Cart, Utils
   ========================================== */

// ===== CART STATE =====
const CartManager = {
  items: JSON.parse(localStorage.getItem('luxeCart') || '[]'),

  save() {
    localStorage.setItem('luxeCart', JSON.stringify(this.items));
    this.updateUI();
  },

  addItem(product) {
    const existing = this.items.find(i => i.id === product.id);
    if (existing) {
      existing.qty += 1;
    } else {
      this.items.push({ ...product, qty: 1 });
    }
    this.save();
    showToast(`تم إضافة ${product.name} إلى السلة`);
  },

  removeItem(id) {
    this.items = this.items.filter(i => i.id !== id);
    this.save();
  },

  updateQty(id, qty) {
    const item = this.items.find(i => i.id === id);
    if (item) {
      item.qty = Math.max(1, qty);
      this.save();
    }
  },

  getTotal() {
    return this.items.reduce((sum, i) => sum + (i.price * i.qty), 0);
  },

  getCount() {
    return this.items.reduce((sum, i) => sum + i.qty, 0);
  },

  updateUI() {
    // Update all cart count badges
    document.querySelectorAll('#cartCount').forEach(el => {
      el.textContent = this.getCount();
    });

    // Update mini cart
    this.renderMiniCart();
  },

  renderMiniCart() {
    const container = document.getElementById('miniCartItems');
    const footer = document.getElementById('miniCartFooter');
    const empty = document.getElementById('miniCartEmpty');
    const total = document.getElementById('miniCartTotal');

    if (!container) return;

    if (this.items.length === 0) {
      if (empty) empty.style.display = 'flex';
      if (footer) footer.style.display = 'none';
      // Remove existing items
      container.querySelectorAll('.mini-cart__item').forEach(el => el.remove());
      return;
    }

    if (empty) empty.style.display = 'none';
    if (footer) footer.style.display = 'block';

    // Clear existing items
    container.querySelectorAll('.mini-cart__item').forEach(el => el.remove());

    this.items.forEach(item => {
      const div = document.createElement('div');
      div.className = 'mini-cart__item';
      div.innerHTML = `
        <div class="mini-cart__item-img">
          <img src="${item.img}" alt="${item.name}">
        </div>
        <div class="mini-cart__item-info">
          <h4 class="mini-cart__item-name">${item.name}</h4>
          <p class="mini-cart__item-price">$${item.price.toFixed(2)}</p>
          <div class="mini-cart__item-qty">
            <button onclick="CartManager.updateQty('${item.id}', ${item.qty - 1})">−</button>
            <span>${item.qty}</span>
            <button onclick="CartManager.updateQty('${item.id}', ${item.qty + 1})">+</button>
          </div>
          <button class="mini-cart__item-remove" onclick="CartManager.removeItem('${item.id}')">إزالة</button>
        </div>
      `;
      container.insertBefore(div, empty);
    });

    if (total) total.textContent = `$${this.getTotal().toFixed(2)}`;
  }
};

// Initialize cart UI
document.addEventListener('DOMContentLoaded', () => {
  CartManager.updateUI();
});

// ===== NAVIGATION =====
document.addEventListener('DOMContentLoaded', () => {
  const navbar = document.getElementById('navbar');
  const hamburger = document.getElementById('hamburger');
  const mobileNav = document.getElementById('mobileNav');
  const navOverlay = document.getElementById('navOverlay');

  // Sticky nav
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Hamburger toggle
  if (hamburger) {
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      mobileNav.classList.toggle('active');
      navOverlay.classList.toggle('active');
      document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
    });
  }

  if (navOverlay) {
    navOverlay.addEventListener('click', () => {
      hamburger.classList.remove('active');
      mobileNav.classList.remove('active');
      navOverlay.classList.remove('active');
      document.body.style.overflow = '';
    });
  }
});

// ===== MINI CART =====
document.addEventListener('DOMContentLoaded', () => {
  const cartToggle = document.getElementById('cartToggle');
  const miniCart = document.getElementById('miniCart');
  const miniCartClose = document.getElementById('miniCartClose');
  const cartOverlay = document.getElementById('cartOverlay');

  function openCart() {
    if (miniCart) miniCart.classList.add('active');
    if (cartOverlay) cartOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeCart() {
    if (miniCart) miniCart.classList.remove('active');
    if (cartOverlay) cartOverlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  if (cartToggle) cartToggle.addEventListener('click', openCart);
  if (miniCartClose) miniCartClose.addEventListener('click', closeCart);
  if (cartOverlay) cartOverlay.addEventListener('click', closeCart);

  // Global reference
  window.openMiniCart = openCart;
  window.closeMiniCart = closeCart;
});

// ===== USER DROPDOWN =====
document.addEventListener('DOMContentLoaded', () => {
  const userDropdown = document.getElementById('userDropdown');
  const userBtn = document.getElementById('userBtn');

  if (userDropdown && userBtn) {
    userBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      userDropdown.classList.toggle('active');
    });

    // Close on click outside
    document.addEventListener('click', (e) => {
      if (!userDropdown.contains(e.target)) {
        userDropdown.classList.remove('active');
      }
    });
  }
});

// ===== TOAST =====
function showToast(message) {
  const toast = document.getElementById('toast');
  if (!toast) return;
  toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3000);
}

// ===== SCROLL REVEAL =====
document.addEventListener('DOMContentLoaded', () => {
  const reveals = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });

  reveals.forEach(el => observer.observe(el));
});

// ===== BACK TO TOP =====
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('backToTop');
  if (!btn) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 500) {
      btn.classList.add('visible');
    } else {
      btn.classList.remove('visible');
    }
  });

  btn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
});

// ===== RIPPLE EFFECT =====
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.btn');
  if (!btn) return;
  const ripple = document.createElement('span');
  ripple.className = 'ripple';
  const rect = btn.getBoundingClientRect();
  const size = Math.max(rect.width, rect.height);
  ripple.style.width = ripple.style.height = size + 'px';
  ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
  ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
  btn.appendChild(ripple);
  setTimeout(() => ripple.remove(), 600);
});

// ===== FLY TO CART ANIMATION =====
function flyToCart(imgSrc, startEl) {
  const cartIcon = document.getElementById('cartToggle');
  if (!cartIcon || !startEl) return;

  const startRect = startEl.getBoundingClientRect();
  const endRect = cartIcon.getBoundingClientRect();

  const flyImg = document.createElement('img');
  flyImg.src = imgSrc;
  flyImg.className = 'fly-to-cart';
  flyImg.style.left = startRect.left + 'px';
  flyImg.style.top = startRect.top + 'px';
  flyImg.style.width = '60px';
  flyImg.style.height = '60px';
  document.body.appendChild(flyImg);

  requestAnimationFrame(() => {
    flyImg.style.left = endRect.left + 'px';
    flyImg.style.top = endRect.top + 'px';
    flyImg.style.width = '20px';
    flyImg.style.height = '20px';
    flyImg.style.opacity = '0.5';
  });

  setTimeout(() => {
    flyImg.remove();
    // Pulse cart icon
    cartIcon.style.transform = 'scale(1.3)';
    setTimeout(() => cartIcon.style.transform = '', 200);
  }, 700);
}

// ===== ADD TO CART BUTTONS =====
document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.add-to-cart-btn');
    if (!btn) return;

    const card = btn.closest('.product-card');
    if (!card) return;

    const product = {
      id: card.dataset.id,
      name: card.dataset.name,
      price: parseFloat(card.dataset.price),
      img: card.dataset.img
    };

    // Fly animation
    const img = card.querySelector('.product-card__image img');
    flyToCart(product.img, img);

    // Add to cart
    CartManager.addItem(product);

    // Open mini cart after fly animation
    setTimeout(() => {
      if (window.openMiniCart) window.openMiniCart();
    }, 800);
  });
});
