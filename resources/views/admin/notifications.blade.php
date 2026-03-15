@extends('admin.layout.master')

@section('title', 'التنبيهات — لوكس بارفيوم')

@section('content')
<section class="dashboard-section active" id="notifications">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2>تنبيهات النظام</h2>
            <p>لديك {{ $unreadNotificationsCount }} تنبيه غير مقروء</p>
        </div>
        <button class="btn btn-outline btn-sm" onclick="markAllAsRead(event)">
            <i class="fas fa-check-double"></i> تحديد الكل كمقروء
        </button>
    </div>

    <div class="table-card glass-card">
        <div class="notifications-list-full">
            @forelse($notifications as $notification)
            <div class="notification-item-full {{ $notification->read_at ? '' : 'unread' }}" 
                 style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; gap: 20px; transition: var(--transition-base);">
                
                <div class="notif-icon-box" style="width: 50px; height: 50px; border-radius: 12px; background: rgba(var(--color-gold-rgb), 0.1); color: var(--color-gold); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    @if($notification->type == 'App\Notifications\NewOrderNotification')
                        <i class="fas fa-shopping-cart"></i>
                    @elseif($notification->type == 'App\Notifications\NewReviewNotification')
                        <i class="fas fa-star"></i>
                    @else
                        <i class="fas fa-bell"></i>
                    @endif
                </div>

                <div class="notif-content-full" style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 5px;">
                        <h4 style="margin: 0; font-size: 1.1rem; color: var(--color-text);">
                            {{ $notification->data['title'] ?? 'تنبيه جديد' }}
                        </h4>
                        <span class="text-dim" style="font-size: 0.85rem;">
                            <i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p style="margin: 0; color: var(--color-text-dim); line-height: 1.6;">
                        {{ $notification->data['message'] ?? 'لديك إشعار جديد في النظام.' }}
                    </p>
                    
                    <div style="margin-top: 15px; display: flex; gap: 15px;">
                        @if(!$notification->read_at)
                        <button class="btn-text" onclick="markAsRead('{{ $notification->id }}', this)" style="color: var(--color-gold); font-size: 0.9rem;">
                            <i class="fas fa-check"></i> تحديد كمقروء
                        </button>
                        @endif
                        
                        @if(isset($notification->data['action_url']))
                        <a href="{{ $notification->data['action_url'] }}" class="btn-text" style="color: var(--color-info); font-size: 0.9rem;">
                            <i class="fas fa-external-link-alt"></i> عرض التفاصيل
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-placeholder" style="padding: 100px 20px; text-align: center;">
                <i class="fas fa-bell-slash" style="font-size: 4rem; color: var(--color-text-dim); margin-bottom: 20px; display: block;"></i>
                <p style="color: var(--color-text-dim);">لا توجد تنبيهات حالياً.</p>
            </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
        <div class="table-footer" style="padding: 20px; border-top: 1px solid var(--border-color);">
            {{ $notifications->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</section>

<style>
    .notification-item-full.unread {
        background: rgba(var(--color-gold-rgb), 0.03);
        border-right: 4px solid var(--color-gold);
    }
    
    .notification-item-full:hover {
        background: rgba(255, 255, 255, 0.02);
    }
</style>

<script>
function markAsRead(id, btn) {
    fetch(`{{ url('/admin/notifications') }}/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = btn.closest('.notification-item-full');
            item.classList.remove('unread');
            item.style.borderRight = 'none';
            btn.remove();
            
            // Update counts if needed
            location.reload(); 
        }
    });
}
</script>
@endsection
