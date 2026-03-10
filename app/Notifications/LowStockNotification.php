<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'low_stock',
            'title' => 'تنبيه مخزون منخفض',
            'message' => "المنتج '{$this->product->name}' وصل إلى الحد الأدنى ({$this->product->stock_quantity} متبقي)",
            'product_id' => $this->product->id,
            'icon' => 'fas fa-exclamation-triangle',
            'color' => 'warning'
        ];
    }
}
