/* ==========================================
   checkout.js — Multi-step form, order summary
   ========================================== */

let currentStep = 1;

function goToStep(step) {
    // Validate current step
    if (step > currentStep) {
        // Moving forward - basic validation
        if (currentStep === 1) {
            const requiredFields = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'zip'];
            for (const field of requiredFields) {
                const el = document.getElementById(field);
                if (el && !el.value.trim()) {
                    el.style.borderColor = '#C62828';
                    el.focus();
                    showToast('يرجى ملء جميع الحقول المطلوبة');
                    setTimeout(() => el.style.borderColor = '', 2000);
                    return;
                }
            }
        }
    }

    currentStep = step;

    // Update step visibility
    document.querySelectorAll('.checkout-step').forEach(s => s.classList.remove('active'));
    const activeStep = document.getElementById(`step${step}`);
    if (activeStep) activeStep.classList.add('active');

    // Update step indicator
    document.querySelectorAll('.step-dot').forEach(dot => {
        const dotStep = parseInt(dot.dataset.step);
        dot.classList.remove('active', 'completed');
        if (dotStep === step) dot.classList.add('active');
        if (dotStep < step) dot.classList.add('completed');
    });

    document.querySelectorAll('.step-line').forEach((line, i) => {
        line.classList.toggle('active', i < step - 1);
    });

    // If step 3, populate review
    if (step === 3) {
        populateReview();
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function populateReview() {
    // Address
    const firstName = document.getElementById('firstName')?.value || 'John';
    const lastName = document.getElementById('lastName')?.value || 'Doe';
    const address = document.getElementById('address')?.value || '123 Main St';
    const city = document.getElementById('city')?.value || 'New York';
    const state = document.getElementById('state')?.value || 'NY';
    const zip = document.getElementById('zip')?.value || '10001';

    const reviewAddress = document.getElementById('reviewAddress');
    if (reviewAddress) {
        reviewAddress.innerHTML = `${firstName} ${lastName}<br>${address}<br>${city}, ${state} ${zip}`;
    }

    // Payment
    const paymentMethod = document.querySelector('input[name="payment"]:checked')?.value;
    const reviewPayment = document.getElementById('reviewPayment');
    if (reviewPayment) {
        if (paymentMethod === 'card') {
            const cardNum = document.getElementById('cardNumber')?.value || '';
            const last4 = cardNum.slice(-4) || '****';
            reviewPayment.textContent = `بطاقة ائتمان تنتهي بـ ${last4}`;
        } else if (paymentMethod === 'paypal') {
            reviewPayment.textContent = 'بايبال (PayPal)';
        } else {
            reviewPayment.textContent = 'سترايب (Stripe)';
        }
    }

    // Items
    const reviewItems = document.getElementById('reviewItems');
    if (reviewItems) {
        reviewItems.innerHTML = '';
        CartManager.items.forEach(item => {
            reviewItems.innerHTML += `
        <div class="review-item">
          <div class="review-item__img"><img src="${item.img}" alt="${item.name}"></div>
          <div class="review-item__info">
            <h4>${item.name}</h4>
            <span>الكمية: ${item.qty}</span>
          </div>
          <span class="review-item__price">$${(item.price * item.qty).toFixed(2)}</span>
        </div>
      `;
        });
    }
}

function placeOrder() {
    // Hide all steps
    document.querySelectorAll('.checkout-step').forEach(s => s.classList.remove('active'));

    // Show success
    const success = document.getElementById('stepSuccess');
    if (success) {
        success.style.display = 'block';
        success.classList.add('active');
    }

    // Update indicators
    document.querySelectorAll('.step-dot').forEach(dot => dot.classList.add('completed'));
    document.querySelectorAll('.step-line').forEach(line => line.classList.add('active'));

    // Clear cart
    CartManager.items = [];
    CartManager.save();

    showToast('تم إرسال الطلب بنجاح!');
}

// ===== Initialize =====
document.addEventListener('DOMContentLoaded', () => {
    // Render order summary sidebar
    const checkoutItems = document.getElementById('checkoutItems');
    const checkoutSubtotal = document.getElementById('checkoutSubtotal');
    const checkoutTax = document.getElementById('checkoutTax');
    const checkoutTotal = document.getElementById('checkoutTotal');

    if (checkoutItems) {
        CartManager.items.forEach(item => {
            checkoutItems.innerHTML += `
        <div class="checkout-summary__item">
          <div class="checkout-summary__item-img"><img src="${item.img}" alt="${item.name}"></div>
          <div class="checkout-summary__item-info"><h4>${item.name}</h4><span>الكمية: ${item.qty}</span></div>
          <span class="checkout-summary__item-price">$${(item.price * item.qty).toFixed(2)}</span>
        </div>
      `;
        });
    }

    const subtotal = CartManager.getTotal();
    const tax = subtotal * 0.08;
    const total = subtotal + tax;

    if (checkoutSubtotal) checkoutSubtotal.textContent = `$${subtotal.toFixed(2)}`;
    if (checkoutTax) checkoutTax.textContent = `$${tax.toFixed(2)}`;
    if (checkoutTotal) checkoutTotal.textContent = `$${total.toFixed(2)}`;

    // Payment method toggle
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', () => {
            document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
            method.classList.add('active');
        });
    });

    // Card number formatting
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/g, '');
            if (v.length > 16) v = v.slice(0, 16);
            e.target.value = v.match(/.{1,4}/g)?.join(' ') || '';
        });
    }

    // Expiry formatting
    const expiryInput = document.getElementById('cardExpiry');
    if (expiryInput) {
        expiryInput.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length >= 2) v = v.slice(0, 2) + '/' + v.slice(2, 4);
            e.target.value = v;
        });
    }
});
