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
