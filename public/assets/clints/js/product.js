/* ==========================================
   product.js — Gallery, Qty, Add to Cart, Sizes
   ========================================== */

// ===== IMAGE GALLERY =====
function changeImage(thumbEl, src) {
    const main = document.getElementById('galleryMain');
    if (main) {
        main.src = src;
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        thumbEl.classList.add('active');
    }
}

// Image zoom on mouse move
document.addEventListener('DOMContentLoaded', () => {
    const mainContainer = document.getElementById('mainImage');
    const mainImg = document.getElementById('galleryMain');

    if (mainContainer && mainImg) {
        mainContainer.addEventListener('mousemove', (e) => {
            const rect = mainContainer.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            mainImg.style.transformOrigin = `${x}% ${y}%`;
        });

        mainContainer.addEventListener('mouseleave', () => {
            mainImg.style.transformOrigin = 'center center';
            mainImg.style.transform = 'scale(1)';
        });

        mainContainer.addEventListener('mouseenter', () => {
            mainImg.style.transform = 'scale(1.5)';
        });
    }
});

// ===== QUANTITY SELECTOR =====
document.addEventListener('DOMContentLoaded', () => {
    const qtyMinus = document.getElementById('qtyMinus');
    const qtyPlus = document.getElementById('qtyPlus');
    const qtyInput = document.getElementById('qtyInput');

    if (qtyMinus && qtyPlus && qtyInput) {
        qtyMinus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val > 1) qtyInput.value = val - 1;
        });

        qtyPlus.addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val < 10) qtyInput.value = val + 1;
        });
    }
});

// ===== SIZE SELECTOR =====
document.addEventListener('DOMContentLoaded', () => {
    const sizeBtns = document.querySelectorAll('.size-btn');
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            sizeBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
});

// ===== ADD TO CART =====
document.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('addToCartBtn');
    if (!addBtn) return;

    addBtn.addEventListener('click', () => {
        const qty = parseInt(document.getElementById('qtyInput')?.value || 1);
        const name = document.querySelector('.product-info__name')?.textContent || 'عطر فاخر';
        const price = parseFloat(document.querySelector('.price-current')?.textContent.replace('$', '') || 0);
        const img = document.getElementById('galleryMain')?.src || '';

        const product = {
            id: '3', // This should ideally come from a data-id attribute on the body or a hidden input
            name: name,
            price: price,
            img: img
        };

        for (let i = 0; i < qty; i++) {
            CartManager.addItem(product);
        }

        // Fly animation
        const flyImg = document.getElementById('galleryMain');
        flyToCart(product.img, flyImg);

        setTimeout(() => {
            if (window.openMiniCart) window.openMiniCart();
        }, 800);
    });
});

// ===== WISHLIST =====
document.addEventListener('DOMContentLoaded', () => {
    const wishBtn = document.getElementById('wishlistBtn');
    if (!wishBtn) return;

    wishBtn.addEventListener('click', () => {
        wishBtn.classList.toggle('active');
        const icon = wishBtn.querySelector('i');
        if (wishBtn.classList.contains('active')) {
            icon.className = 'fas fa-heart';
            showToast('تمت الإضافة للمفضلة');
        } else {
            icon.className = 'far fa-heart';
            showToast('تمت الإزالة من المفضلة');
        }
    });
});

// ===== REVIEW SUBMISSION =====
document.addEventListener('DOMContentLoaded', () => {
    const reviewForm = document.getElementById('reviewForm');
    if (!reviewForm) return;

    reviewForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const btn = document.getElementById('submitReviewBtn');
        const productId = reviewForm.dataset.productId;
        const rating = reviewForm.querySelector('input[name="rating"]:checked')?.value;
        const comment = document.getElementById('reviewComment').value;

        if (!rating) {
            showToast('يرجى اختيار التقييم بالنجوم');
            return;
        }

        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';

        fetch('/reviews', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({
                product_id: productId,
                rating: rating,
                comment: comment
            })
        })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    showToast(res.message);
                    reviewForm.reset();
                } else if (res.message) {
                    showToast(res.message);
                } else {
                    showToast('حدث خطأ، يرجى المحاولة لاحقاً');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('يرجى تسجيل الدخول للتمكن من إضافة تقييم');
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    });
});
