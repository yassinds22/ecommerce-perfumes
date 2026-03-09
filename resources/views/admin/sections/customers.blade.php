<!-- ===== CUSTOMERS SECTION ===== -->
<section class="dashboard-section {{ ($isActive ?? false) ? 'active' : '' }}" id="customers">
    <div class="table-card">
        <div class="table-card__header">
            <h3>العملاء المسجلين <span style="color:var(--color-text-muted);font-weight:400">({{ $customersCount ?? $stats['customers_count'] ?? 0 }})</span></h3>
            <div class="table-card__actions">
                <button class="btn btn-outline btn-sm"><i class="fas fa-download"></i> تصدير</button>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>العميل</th>
                    <th>الطلبات</th>
                    <th>إجمالي الإنفاق</th>
                    <th>تاريخ الانضمام</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="customersTableBody">
                @foreach($customers as $customer)
                <tr>
                    <td>
                        <div class="table-product">
                            <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--color-gold),var(--color-gold-dark));display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;color:#fff;flex-shrink:0">
                                {{ substr($customer->name, 0, 2) }}
                            </div>
                            <div>
                                <div class="table-product__name">{{ $customer->name }}</div>
                                <div class="table-product__brand">{{ $customer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $customer->orders_count }}</td>
                    <td style="font-weight:600">${{ number_format($customer->orders_sum_total ?? 0) }}</td>
                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="table-actions">
                            <button title="عرض" onclick="viewCustomer({{ $customer->id }})"><i class="fas fa-eye"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="table-footer">
            <span>عرض {{ $customers->count() }} من {{ $customers->total() }} نتيجة</span>
            <div class="pagination">
                {{ $customers->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    </div>
</section>
