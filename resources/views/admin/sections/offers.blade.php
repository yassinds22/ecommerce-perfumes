<!-- ===== OFFERS SECTION ===== -->
<section class="dashboard-section" id="offers">
    <div class="table-card">
        <div class="table-card__header">
            <h3>العروض الحالية</h3>
            <div class="table-card__actions">
                <button class="btn btn-gold" onclick="openModal('addOfferModal')"><i
                        class="fas fa-plus"></i> إضافة عرض</button>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>الحالة</th>
                    <th>العنوان</th>
                    <th>القيمة</th>
                    <th>الكود</th>
                    <th>الانتهاء</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="offersTableBody">
                @foreach($offers as $offer)
                <tr>
                    <td>
                        @if($offer->is_active)
                            <span class="status-badge shipped">نشط</span>
                        @else
                            <span class="status-badge cancelled">غير نشط</span>
                        @endif
                    </td>
                    <td style="font-weight:600">{{ $offer->name }}</td>
                    <td>
                        {{ $offer->type == 'fixed' ? '$' : '%' }}{{ $offer->value }}
                        @if($offer->is_global) <br><small>(عام)</small> @endif
                    </td>
                    <td style="font-weight:600;color:var(--color-gold)">{{ $offer->code }}</td>
                    <td>{{ $offer->expires_at ? $offer->expires_at->format('Y-m-d') : 'دائم' }}</td>
                    <td>
                        <div class="table-actions">
                            <button title="تعديل" onclick="window.location='{{ route('admin.coupons.edit', $offer->id) }}'"><i class="fas fa-pen"></i></button>
                            <form action="{{ route('admin.coupons.destroy', $offer->id) }}" method="POST" style="display:inline" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="delete" title="حذف"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
