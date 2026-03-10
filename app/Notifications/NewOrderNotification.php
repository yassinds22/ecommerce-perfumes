<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_order',
            'title' => 'طلب جديد #ORD-' . $this->order->id,
            'message' => "تم استلام طلب جديد بقيمة " . number_format($this->order->total) . " ر.س من " . ($this->order->user->name ?? 'عميل'),
            'order_id' => $this->order->id,
            'icon' => 'fas fa-shopping-cart',
            'color' => 'success'
        ];
    }
}
