<!-- ===== REVIEWS SECTION ===== -->
<section class="dashboard-section {{ ($isActive ?? false) ? 'active' : '' }}" id="reviews">
    <div class="table-card">
        <div class="table-card__header">
            <h3>التقييمات <span style="color:var(--color-text-muted);font-weight:400">({{ $reviews->total() }})</span></h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th>العميل</th>
                    <th>التقييم</th>
                    <th>العنوان</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="reviewsTableBody">
                @foreach($reviews as $review)
                <tr>
                    <td style="font-weight:600">{{ $review->product->name ?? 'منتج محذوف' }}</td>
                    <td>{{ $review->user->name ?? 'مجهول' }}</td>
                    <td>
                        <div class="stars">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'far' }}" style="{{ $i <= $review->rating ? '' : 'color:var(--color-text-dim)' }}"></i>
                            @endfor
                        </div>
                    </td>
                    <td>{{ $review->title }}</td>
                    <td>
                        @if($review->is_visible)
                            <span style="color:var(--color-success);font-size:0.75rem"><i class="fas fa-check-circle"></i> موثق</span>
                        @else
                            <span style="color:var(--color-text-dim);font-size:0.75rem">غير موثق</span>
                        @endif
                    </td>
                    <td>{{ $review->created_at->format('Y-m-d') }}</td>
                    <td>
                        <div class="table-actions">
                            <button class="delete" title="حذف" onclick="deleteReview({{ $review->id }})"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
