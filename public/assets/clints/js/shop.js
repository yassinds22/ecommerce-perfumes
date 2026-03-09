/* ==========================================
   shop.js — Filter toggle, price range, sort
   ========================================== */

document.addEventListener('DOMContentLoaded', () => {
    // Filter panel toggle (mobile)
    const filterToggle = document.getElementById('filterToggle');
    const filtersPanel = document.getElementById('filtersPanel');
    const filtersClose = document.getElementById('filtersClose');
    const filtersOverlay = document.getElementById('filtersOverlay');

    function openFilters() {
        if (filtersPanel) filtersPanel.classList.add('active');
        if (filtersOverlay) filtersOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeFilters() {
        if (filtersPanel) filtersPanel.classList.remove('active');
        if (filtersOverlay) filtersOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (filterToggle) filterToggle.addEventListener('click', openFilters);
    if (filtersClose) filtersClose.addEventListener('click', closeFilters);
    if (filtersOverlay) filtersOverlay.addEventListener('click', closeFilters);

    // Price range slider
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    if (priceRange && priceValue) {
        priceRange.addEventListener('input', () => {
            priceValue.textContent = `$${priceRange.value}`;
        });
    }

    // Filter group toggle
    document.querySelectorAll('.filter-group__title').forEach(title => {
        title.addEventListener('click', () => {
            const content = title.nextElementSibling;
            const icon = title.querySelector('i');
            if (content.style.display === 'none') {
                content.style.display = 'flex';
                if (icon) icon.style.transform = 'rotate(0deg)';
            } else {
                content.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(-90deg)';
            }
        });
    });

    // View toggle (grid/list)
    const viewBtns = document.querySelectorAll('.view-btn');
    const productGrid = document.getElementById('productGrid');
    viewBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            viewBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            if (productGrid) {
                if (btn.dataset.view === 'list') {
                    productGrid.style.gridTemplateColumns = '1fr';
                } else {
                    productGrid.style.gridTemplateColumns = '';
                }
            }
        });
    });
});

function applyFilters() {
    const filters = {
        cat: Array.from(document.querySelectorAll('input[name="cat"]:checked')).map(el => el.value),
        brand: Array.from(document.querySelectorAll('input[name="brand"]:checked')).map(el => el.value),
        gender: Array.from(document.querySelectorAll('input[name="gender"]:checked')).map(el => el.value),
        min_price: 0,
        max_price: document.getElementById('priceRange') ? document.getElementById('priceRange').value : 500,
        sort: document.getElementById('sortSelect') ? document.getElementById('sortSelect').value : 'latest'
    };

    let url = new URL(window.location.href);
    url.searchParams.delete('cat');
    url.searchParams.delete('brand');
    url.searchParams.delete('gender');
    url.searchParams.delete('max_price');
    url.searchParams.delete('sort');
    url.searchParams.delete('page'); // Reset to first page on filter

    if (filters.cat.length) url.searchParams.set('cat', filters.cat.join(','));
    if (filters.brand.length) url.searchParams.set('brand', filters.brand.join(','));
    if (filters.gender.length) url.searchParams.set('gender', filters.gender.join(','));
    if (filters.max_price < 500) url.searchParams.set('max_price', filters.max_price);
    if (filters.sort !== 'latest') url.searchParams.set('sort', filters.sort);

    window.location.href = url.pathname + url.search;
}
