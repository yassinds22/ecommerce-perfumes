/* ==========================================
   checkout.js — Multi-step form, order summary, and Stripe
   ========================================== */

let currentStep = 1;
let stripe, elements, cardElement;

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

    // Update step indicator (if UI has them)
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
    const firstName = document.getElementById('firstName')?.value || '';
    const lastName = document.getElementById('lastName')?.value || '';
    const address = document.getElementById('address')?.value || '';
    const city = document.getElementById('city')?.value || '';
    const state = document.getElementById('state')?.value || '';
    const zip = document.getElementById('zip')?.value || '';

    const reviewAddress = document.getElementById('reviewAddress');
    if (reviewAddress) {
        reviewAddress.innerHTML = `${firstName} ${lastName}<br>${address}<br>${city}, ${state} ${zip}`;
    }

    // Payment
    const paymentMethod = document.querySelector('input[name="payment"]:checked')?.value;
    const reviewPayment = document.getElementById('reviewPayment');
    if (reviewPayment) {
        if (paymentMethod === 'card' || paymentMethod === 'stripe') {
            reviewPayment.textContent = 'بطاقة ائتمان (Stripe)';
        } else if (paymentMethod === 'paypal') {
            reviewPayment.textContent = 'بايبال (PayPal)';
        } else {
            reviewPayment.textContent = 'الدفع عند الاستلام';
        }
    }

    // Items
    const reviewItems = document.getElementById('reviewItems');
    if (reviewItems) {
        reviewItems.innerHTML = '';
        CartManager.items.forEach(item => {
            reviewItems.innerHTML += `
        <div class="review-item">
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

// ===== Initialize Stripe =====
function initStripe() {
    if (!window.stripeKey || stripe) return;

    stripe = Stripe(window.stripeKey);
    // Set locale to Arabic for better UI/UX and native error messages
    elements = stripe.elements({ locale: 'ar' });

    const style = {
        base: {
            color: '#2C2C2C', // Using dark text for visibility on white background
            fontFamily: '"Outfit", sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '17px',
            '::placeholder': {
                color: 'rgba(0, 0, 0, 0.4)' // Darker placeholder
            }
        },
        invalid: {
            color: '#ff4444',
            iconColor: '#ff4444'
        }
    };

    cardElement = elements.create('card', {
        style: style,
        hidePostalCode: true
    });

    const mountPoint = document.getElementById('payment-element');
    if (mountPoint) {
        cardElement.mount('#payment-element');
    }

    cardElement.on('change', function (event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }

        // Track completeness locally if needed
        window.isCardValid = event.complete;
    });
}

function placeOrder() {
    const btn = document.querySelector('.place-order-btn');
    const originalText = btn.innerHTML;
    const paymentMethod = document.querySelector('input[name="payment"]:checked')?.value || 'card';

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري معالجة الطلب...';

    const subtotal = CartManager.getTotal();
    const tax = subtotal * 0.08;
    const total = subtotal + tax;

    const orderData = {
        total: total,
        items: CartManager.items,
        firstName: document.getElementById('firstName')?.value,
        lastName: document.getElementById('lastName')?.value,
        email: document.getElementById('email')?.value,
        phone: document.getElementById('phone')?.value,
        address: document.getElementById('address')?.value,
        city: document.getElementById('city')?.value,
        zip: document.getElementById('zip')?.value,
        country: document.getElementById('country')?.value,
        payment_method: paymentMethod,
        _token: document.querySelector('meta[name="csrf-token"]')?.content
    };

    // 1. Create the Order
    fetch('/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(orderData)
    })
        .then(res => res.json())
        .then(async res => {
            if (!res.success) {
                throw new Error(res.error || 'فشل إنشاء الطلب');
            }

            // 2. If Stripe, handle payment
            if (paymentMethod === 'stripe' || paymentMethod === 'card') {
                return handleStripePayment(res.order_id, res.order_number);
            } else {
                showSuccess(res.order_number, res.order_id);
            }
        })
        .catch(err => {
            console.error(err);
            showToast(err.message || 'حدث خطأ فني، يرجى المحاولة لاحقاً');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
}

async function handleStripePayment(orderId, orderNumber) {
    const btn = document.querySelector('.place-order-btn');

    try {
        const createPaymentRes = await fetch('/payment/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({ order_id: orderId })
        });

        const paymentData = await createPaymentRes.json();
        if (paymentData.error) throw new Error(paymentData.error);

        const result = await stripe.confirmCardPayment(paymentData.clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: document.getElementById('firstName').value + ' ' + document.getElementById('lastName').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                }
            }
        });

        if (result.error) {
            throw new Error(result.error.message);
        } else if (result.paymentIntent.status === 'succeeded') {
            showSuccess(orderNumber, orderId);
        }
    } catch (err) {
        showToast(err.message);
        btn.disabled = false;
        btn.innerHTML = 'تأكيد الطلب';
    }
}

function showSuccess(orderNumber, orderId) {
    document.querySelectorAll('.checkout-step').forEach(s => s.classList.remove('active'));
    const success = document.getElementById('stepSuccess');
    if (success) {
        const orderNumEl = success.querySelector('strong');
        if (orderNumEl) orderNumEl.textContent = '#' + orderNumber;

        // Setup Invoice Download Button
        const downloadBtn = document.getElementById('downloadInvoiceBtn');
        if (downloadBtn && orderId) {
            downloadBtn.href = `/orders/${orderId}/invoice/download`;
            downloadBtn.style.display = 'inline-flex';
            downloadBtn.style.alignItems = 'center';
            downloadBtn.style.justifyContent = 'center';
            downloadBtn.style.gap = '8px';
        }

        success.style.display = 'block';
        success.classList.add('active');
    }
    CartManager.items = [];
    CartManager.save();
    showToast('تم إرسال الطلب بنجاح!');
}

// ===== Initialize =====
document.addEventListener('DOMContentLoaded', () => {
    // Check if cart is empty
    if (CartManager.items.length === 0) {
        // If we're not seeing the success step, redirect to shop
        const successStep = document.getElementById('stepSuccess');
        if (!successStep || (successStep && !successStep.classList.contains('active'))) {
            window.location.href = '/shop';
            return;
        }
    }

    initStripe();

    const checkoutItems = document.getElementById('checkoutItems');
    const checkoutSubtotal = document.getElementById('checkoutSubtotal');
    const checkoutTax = document.getElementById('checkoutTax');
    const checkoutTotal = document.getElementById('checkoutTotal');

    if (checkoutItems) {
        CartManager.items.forEach(item => {
            checkoutItems.innerHTML += `
        <div class="checkout-summary__item">
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

    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', () => {
            document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
            method.classList.add('active');
            const cardForm = document.getElementById('cardForm');
            const val = method.querySelector('input').value;
            if (cardForm) cardForm.style.display = (val === 'card' || val === 'stripe') ? 'block' : 'none';
        });
    });
});
