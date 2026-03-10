<!-- ===== REVIEWS SECTION ===== -->
<section class="dashboard-section {{ ($isActive ?? false) ? 'active' : '' }}" id="reviews">
    <div class="table-card">
        <div class="table-card__header">
            <h3>التقييمات <span style="color:var(--color-text-muted);font-weight:400">({{ $reviews->total() }})</span></h3>
            <div class="table-card__actions" style="display: flex; gap: 10px">
                <select id="filterReviewRating" class="form-input" style="padding: 4px 8px; font-size: 0.85rem" onchange="filterReviews()">
                    <option value="">جميع التقييمات</option>
                    <option value="5">5 نجوم</option>
                    <option value="4">4 نجوم</option>
                    <option value="3">3 نجوم</option>
                    <option value="1-2">نجمة - نجمتين</option>
                </select>
                <select id="filterReviewStatus" class="form-input" style="padding: 4px 8px; font-size: 0.85rem" onchange="filterReviews()">
                    <option value="">جميع الحالات</option>
                    <option value="approved">معتمد</option>
                    <option value="pending">قيد المراجعة</option>
                </select>
            </div>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>العميل</th>
                    <th>التقييم</th>
                    <th>العنوان والتعليق</th>
                    <th>مراجعة</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="reviewsTableBody">
                @foreach($reviews as $review)
                <tr>
                    <td style="font-weight:600">
                        {{ $review->product->name ?? 'منتج محذوف' }}
                        @if($review->is_verified_purchase)
                            <div class="badge badge--success" style="font-size: 0.65rem; padding: 2px 6px; margin-top: 4px">
                                <i class="fas fa-check-circle"></i> شراء مؤكد
                            </div>
                        @endif
                    </td>
                    <td>{{ $review->user->name ?? 'مجهول' }}</td>
                    <td>
                        <div class="stars">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'far' }}" style="{{ $i <= $review->rating ? '' : 'color:var(--color-text-dim)' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600">{{ $review->title }}</div>
                        <div style="font-size: 0.85rem; color: var(--color-text-muted); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $review->comment }}">
                            {{ $review->comment }}
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $review->is_approved ? 'badge--success' : 'badge--warning' }}">
                            {{ $review->is_approved ? 'معتمد' : 'معلق' }}
                        </span>
                    </td>
                    <td>{{ $review->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="table-actions">
                            @if(!$review->is_approved)
                                <button class="btn-icon" style="color: var(--color-success)" title="اعتماد" onclick="approveReview({{ $review->id }})">
                                    <i class="fas fa-check"></i>
                                </button>
                            @else
                                <button class="btn-icon" style="color: var(--color-warning)" title="تعليق" onclick="rejectReview({{ $review->id }})">
                                    <i class="fas fa-undo"></i>
                                </button>
                            @endif
                            <button class="btn-icon" style="color: var(--color-gold)" title="توثيق الشراء" onclick="toggleVerifiedReview({{ $review->id }})">
                                <i class="fas fa-certificate"></i>
                            </button>
                            <button class="btn-icon" style="color: var(--color-danger)" title="حذف" onclick="deleteReview({{ $review->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="table-footer">
            <span>عرض {{ $reviews->count() }} من {{ $reviews->total() }} نتيجة</span>
            <div class="pagination">
                {{ $reviews->links('admin.layout.pagination') }}
            </div>
        </div>
    </div>
</section>
