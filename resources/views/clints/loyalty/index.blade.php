@extends('clints.layout.master')

@section('content')
<div class="page-spacer"></div>

<section class="section loyalty-dashboard">
    <div class="container">
        <div class="loyalty-header text-center mb-5">
            <h2 class="section-title">برنامج الولاء</h2>
            <p class="section-subtitle">جمع النقاط واحصل على مكافآت حصرية</p>
        </div>

        <div class="row">
            <!-- Points Balance Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 text-center p-4 h-100">
                    <div class="loyalty-icon mb-3" style="font-size: 3rem; color: var(--color-gold);">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h4 class="text-muted mb-2">رصيد نقاطك</h4>
                    <h2 class="mb-3" style="font-weight: 700;">{{ $pointsBalance }}</h2>
                    <p class="small text-muted">تساوي تقريباً ${{ number_format($pointsBalance / 10, 2) }} كخصم</p>
                </div>
            </div>

            <!-- How it Works -->
            <div class="col-md-8 mb-4">
                <div class="card shadow-sm border-0 p-4 h-100">
                    <h4 class="mb-4">كيف تعمل النقاط؟</h4>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-center">
                            <span class="badge bg-gold me-3" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">1</span>
                            <span>اكسب 5 نقاط مقابل كل $100 تنفقها (5% كاش باك).</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <span class="badge bg-gold me-3" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">2</span>
                            <span>كل 10 نقاط تساوي $1 خصم على مبيعاتك القادمة.</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <span class="badge bg-gold me-3" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">3</span>
                            <span>يمكنك استخدام النقاط مباشرة في صفحة إتمام الشراء.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Transactions History -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white p-3">
                        <h5 class="mb-0">سجل النقاط</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>العملية</th>
                                        <th>النقاط</th>
                                        <th>الوصف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('Y/m/d H:i') }}</td>
                                        <td>
                                            @if($transaction->type == 'earn')
                                                <span class="badge bg-success">اكتساب</span>
                                            @elseif($transaction->type == 'redeem')
                                                <span class="badge bg-danger">استبدال</span>
                                            @else
                                                <span class="badge bg-info">تعديل</span>
                                            @endif
                                        </td>
                                        <td class="{{ $transaction->points > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $transaction->points > 0 ? '+' : '' }}{{ $transaction->points }}
                                        </td>
                                        <td>{{ $transaction->description }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">ليس لديك أي عمليات سابقة.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .bg-gold { background-color: var(--color-gold); color: white; }
    .loyalty-dashboard .card { transition: transform var(--transition-fast); }
    .loyalty-dashboard .card:hover { transform: translateY(-5px); }
</style>
@endsection
