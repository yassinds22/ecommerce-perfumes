/* ==========================================
   homepage.js — Hero Parallax, Bestsellers Slider
   ========================================== */

// ===== HERO PARALLAX =====
document.addEventListener('DOMContentLoaded', () => {
    const heroImage = document.getElementById('heroImage');
    if (!heroImage) return;

    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        if (scrollY < window.innerHeight) {
            heroImage.style.transform = `translateY(${scrollY * 0.15}px)`;
        }
    });
});

// ===== BESTSELLERS SLIDER =====
document.addEventListener('DOMContentLoaded', () => {
    const track = document.getElementById('bestsellersTrack');
    const prevBtn = document.getElementById('bsPrev');
    const nextBtn = document.getElementById('bsNext');
    const dotsContainer = document.getElementById('bsDots');

    if (!track || !prevBtn || !nextBtn) return;

    let current = 0;
    const cards = track.querySelectorAll('.bestseller-card');
    const total = cards.length;

    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = `translateX(-${current * 100}%)`;

        // Update dots
        if (dotsContainer) {
            dotsContainer.querySelectorAll('.dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === current);
            });
        }
    }

    prevBtn.addEventListener('click', () => goTo(current - 1));
    nextBtn.addEventListener('click', () => goTo(current + 1));

    // Dot clicks
    if (dotsContainer) {
        dotsContainer.querySelectorAll('.dot').forEach((dot, i) => {
            dot.addEventListener('click', () => goTo(i));
        });
    }

    // Auto-play
    let autoplay = setInterval(() => goTo(current + 1), 6000);

    // Pause on hover
    track.addEventListener('mouseenter', () => clearInterval(autoplay));
    track.addEventListener('mouseleave', () => {
        autoplay = setInterval(() => goTo(current + 1), 6000);
    });
});

// ===== NEWSLETTER FORM =====
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('newsletterForm');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = form.querySelector('input[type="email"]');
        if (email && email.value) {
            showToast('Welcome to LUXE PARFUM! Check your inbox.');
            email.value = '';
        }
    });
});

// ===== HERO ANIMATION ON LOAD =====
document.addEventListener('DOMContentLoaded', () => {
    const label = document.querySelector('.hero__label');
    const title = document.querySelector('.hero__title');
    const subtitle = document.querySelector('.hero__subtitle');
    const cta = document.querySelector('.hero__cta');

    const elements = [label, title, subtitle, cta].filter(Boolean);

    elements.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `opacity 0.8s ease ${0.2 + i * 0.2}s, transform 0.8s ease ${0.2 + i * 0.2}s`;

        requestAnimationFrame(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        });
    });
});
