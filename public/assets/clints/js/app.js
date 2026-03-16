/* ==========================================
   app.js — Global: Navigation, Cart, Wishlist, Utils
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
    document.querySelectorAll('#cartCount').forEach(el => {
      el.textContent = this.getCount();
    });
    
    // Update product card buttons
    document.querySelectorAll('.product-card').forEach(card => {
        const productId = card.dataset.id;
        const btn = card.querySelector('.add-to-cart-btn');
        if (btn && this.items.find(i => i.id == productId)) {
            btn.innerHTML = 'تم إضافة المنتج — عرض السلة';
            btn.classList.add('in-cart');
            btn.style.background = '#1a1a1a'; // Darker theme color
            btn.style.border = '1px solid var(--color-gold)';
            btn.style.color = 'var(--color-gold)';
        } else if (btn) {
            btn.innerHTML = 'أضف للسلة';
            btn.classList.remove('in-cart');
            btn.style.background = ''; // Revert to CSS default
            btn.style.color = '';
        }
    });

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
      container.querySelectorAll('.mini-cart__item').forEach(el => el.remove());
      return;
    }

    if (empty) empty.style.display = 'none';
    if (footer) footer.style.display = 'block';

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

// ===== WISHLIST MANAGER =====
// ===== UI UTILITIES =====
const BtnUtils = {
    setLoading(btn, isLoading) {
        if (!btn) return;
        if (isLoading) {
            btn.dataset.originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.classList.add('btn-loading');
            btn.innerHTML = `<span class="spinner"></span> ${btn.textContent}`;
        } else {
            btn.innerHTML = btn.dataset.originalHtml || btn.innerHTML;
            btn.disabled = false;
            btn.classList.remove('btn-loading');
        }
    },

    copyToClipboard(text) {
        const temp = document.createElement('input');
        temp.value = text;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        showToast({ message: 'تم نسخ الرابط إلى الحافظة!', type: 'success' });
    }
};

// ... (Rest of Managers as refactored previously, but ensuring consistency) ...
const WishlistManager = {
  async toggle(productId, btn) {
    BtnUtils.setLoading(btn, true);
    try {
      const response = await fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ product_id: productId })
      });

      if (response.status === 401) {
        showToast({ message: 'يرجى تسجيل الدخول أولاً', type: 'error' });
        setTimeout(() => window.location.href = '/login', 1500);
        return;
      }

      const data = await response.json();

      if (data.status === 'added') {
        btn.querySelectorAll('i').forEach(i => {
          i.classList.remove('far');
          i.classList.add('fas');
        });
        btn.classList.add('active');
      } else {
        btn.querySelectorAll('i').forEach(i => {
          i.classList.remove('fas');
          i.classList.add('far');
        });
        btn.classList.remove('active');
      }

      document.querySelectorAll('#wishlistCount').forEach(el => {
        el.textContent = data.count;
        if (data.count > 0) el.classList.remove('hide-badge');
        else el.classList.add('hide-badge');
      });

      showToast({ message: data.message, type: 'success' });
      return data;
    } catch (error) {
      console.error('Wishlist error:', error);
      showToast({ message: 'حدث خطأ ما، يرجى المحاولة لاحقاً', type: 'error' });
    } finally {
        BtnUtils.setLoading(btn, false);
    }
  },

  async moveToCart(productId, btn) {
      const card = btn.closest('.product-card');
      const product = {
          id: card.dataset.id,
          name: card.dataset.name,
          price: parseFloat(card.dataset.price),
          img: card.dataset.img
      };

      BtnUtils.setLoading(btn, true);
      CartManager.addItem(product, false); 

      const result = await this.toggle(productId, btn);
      if (result && result.status === 'removed') {
          showToast({ message: `تم نقل ${product.name} إلى السلة بنجاح`, type: 'success', image: product.img });
          if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.9)';
                card.style.transition = '0.4s ease';
                setTimeout(() => {
                    card.remove();
                    const grid = document.querySelector('.wishlist-grid');
                    if (grid && grid.querySelectorAll('.product-card').length === 0) location.reload();
                }, 400);
          }
      }
      BtnUtils.setLoading(btn, false);
  }
};

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', () => {
  CartManager.updateUI();

  // Sticky navbar
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) navbar.classList.add('scrolled');
    else navbar.classList.remove('scrolled');
  });

  // Mobile navigation
  const hamburger = document.getElementById('hamburger');
  const mobileNav = document.getElementById('mobileNav');
  const navOverlay = document.getElementById('navOverlay');

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

  // Mini cart toggle
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
  window.openMiniCart = openCart;
  window.closeMiniCart = closeCart;

  // Global click listeners
  document.addEventListener('click', (e) => {
    // Add to cart
    const cartBtn = e.target.closest('.add-to-cart-btn');
    if (cartBtn) {
      if (cartBtn.classList.contains('in-cart')) {
          window.location.href = '/cart';
          return;
      }
      const card = cartBtn.closest('.product-card');
      if (card) {
        const product = {
          id: card.dataset.id,
          name: card.dataset.name,
          price: parseFloat(card.dataset.price),
          img: card.dataset.img
        };
        const img = card.querySelector('.product-card__image img');
        if (typeof flyToCart === 'function') flyToCart(product.img, img);
        CartManager.addItem(product);
        setTimeout(() => openCart(), 800);
      }
    }

    // Wishlist toggle
    const wishlistBtn = e.target.closest('.wishlist-btn');
    if (wishlistBtn) {
      const card = wishlistBtn.closest('.product-card');
      const productId = card ? card.dataset.id : wishlistBtn.dataset.productId;
      if (productId) WishlistManager.toggle(productId, wishlistBtn);
    }

    // User dropdown toggle
    const userBtn = e.target.closest('#userBtn');
    const userDropdown = document.getElementById('userDropdown');
    if (userBtn && userDropdown) {
      e.stopPropagation();
      userDropdown.classList.toggle('active');
    } else if (userDropdown && !userDropdown.contains(e.target)) {
      userDropdown.classList.remove('active');
    }

    // AJAX Wishlist Removal (from wishlist page)
    const removeBtn = e.target.closest('.remove-wishlist-ajax');
    if (removeBtn) {
        const productId = removeBtn.dataset.id;
        const card = removeBtn.closest('.wishlist-item-card, .product-card');
        
        if (productId && card) {
            WishlistManager.toggle(productId, removeBtn).then(() => {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                card.style.transition = '0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                setTimeout(() => {
                    card.remove();
                    const grid = document.querySelector('.wishlist-grid');
                    if (grid && grid.querySelectorAll('.wishlist-item-card').length === 0) {
                        location.reload(); 
                    }
                }, 400);
            });
        }
    }
  });

  // Reveal animations
  const reveals = document.querySelectorAll('.reveal');
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });
  reveals.forEach(el => revealObserver.observe(el));

  // Back to top
  const backToTop = document.getElementById('backToTop');
  if (backToTop) {
    window.addEventListener('scroll', () => {
      if (window.scrollY > 500) backToTop.classList.add('visible');
      else backToTop.classList.remove('visible');
    });
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
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
    cartIcon.style.transform = 'scale(1.3)';
    setTimeout(() => cartIcon.style.transform = '', 200);
  }, 700);
}
