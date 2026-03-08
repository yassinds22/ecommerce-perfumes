<!-- ===== OVERVIEW SECTION ===== -->
<section class="dashboard-section active" id="overview">

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon revenue"><i class="fas fa-dollar-sign"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-arrow-up"></i> 12.5%</span>
            </div>
            <div class="stat-card__value" data-value="$48,560">0</div>
            <div class="stat-card__label">إجمالي الإيرادات</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon orders"><i class="fas fa-shopping-bag"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-arrow-up"></i> 8.2%</span>
            </div>
            <div class="stat-card__value" data-value="384">0</div>
            <div class="stat-card__label">إجمالي الطلبات</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon products"><i class="fas fa-box-open"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-arrow-up"></i> 3.1%</span>
            </div>
            <div class="stat-card__value" data-value="48">0</div>
            <div class="stat-card__label">المنتجات النشطة</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__header">
                <div class="stat-card__icon customers"><i class="fas fa-users"></i></div>
                <span class="stat-card__trend up"><i class="fas fa-arrow-up"></i> 15.3%</span>
            </div>
            <div class="stat-card__value" data-value="1,247">0</div>
            <div class="stat-card__label">العملاء المسجلين</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="charts-row">
        <!-- Revenue Chart -->
        <div class="chart-card">
            <div class="chart-card__header">
                <h3>المبيعات الشهرية</h3>
                <div class="chart-tabs">
                    <button class="chart-tab active">شهري</button>
                    <button class="chart-tab">أسبوعي</button>
                </div>
            </div>
            <div class="chart-placeholder">
                <div class="chart-bars">
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">يناير</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">فبراير</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">مارس</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">أبريل</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">مايو</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">يونيو</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">يوليو</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">أغسطس</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">سبتمبر</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">أكتوبر</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">نوفمبر</span>
                    </div>
                    <div class="chart-bar-group">
                        <div class="chart-bar" style="height:0%"></div><span
                            class="chart-bar-label">ديسمبر</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Donut -->
        <div class="chart-card">
            <div class="chart-card__header">
                <h3>التصنيفات</h3>
            </div>
            <div class="donut-chart">
                <div class="donut-visual"
                    style="background: conic-gradient(#c9a84c 0% 35%, #60a5fa 35% 55%, #34d399 55% 75%, #f87171 75% 90%, #8b8fa3 90% 100%)">
                    <div class="donut-center">
                        <strong>48</strong>
                        <span>منتج</span>
                    </div>
                </div>
                <div class="donut-legend">
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#c9a84c"></div>رجالي <span>35%</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#60a5fa"></div>نسائي <span>20%</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#34d399"></div>مشترك <span>20%</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#f87171"></div>عربي <span>15%</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:#8b8fa3"></div>هدايا <span>10%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="table-card">
        <div class="table-card__header">
            <h3>أحدث الطلبات</h3>
            <a href="#" class="btn btn-outline btn-sm"
                onclick="document.querySelector('[data-section=orders]').click()">عرض الكل <i
                    class="fas fa-arrow-left"></i></a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>المنتجات</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight:600;color:var(--color-gold)">#ORD-2061</td>
                    <td>سارة أحمد</td>
                    <td>3 منتج</td>
                    <td style="font-weight:600">$745</td>
                    <td><span class="status-badge completed">مكتمل</span></td>
                    <td>2026-03-07</td>
                </tr>
                <tr>
                    <td style="font-weight:600;color:var(--color-gold)">#ORD-2060</td>
                    <td>أحمد خالد</td>
                    <td>1 منتج</td>
                    <td style="font-weight:600">$340</td>
                    <td><span class="status-badge shipped">تم الشحن</span></td>
                    <td>2026-03-07</td>
                </tr>
                <tr>
                    <td style="font-weight:600;color:var(--color-gold)">#ORD-2059</td>
                    <td>مريم خالد</td>
                    <td>2 منتج</td>
                    <td style="font-weight:600">$495</td>
                    <td><span class="status-badge pending">قيد الانتظار</span></td>
                    <td>2026-03-06</td>
                </tr>
                <tr>
                    <td style="font-weight:600;color:var(--color-gold)">#ORD-2058</td>
                    <td>عمر محمد</td>
                    <td>1 منتج</td>
                    <td style="font-weight:600">$220</td>
                    <td><span class="status-badge completed">مكتمل</span></td>
                    <td>2026-03-06</td>
                </tr>
                <tr>
                    <td style="font-weight:600;color:var(--color-gold)">#ORD-2057</td>
                    <td>نورة العلي</td>
                    <td>4 منتج</td>
                    <td style="font-weight:600">$980</td>
                    <td><span class="status-badge shipped">تم الشحن</span></td>
                    <td>2026-03-05</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
