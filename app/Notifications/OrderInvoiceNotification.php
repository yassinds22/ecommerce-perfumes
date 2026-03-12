<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order; // Added this use statement

class OrderInvoiceNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $customerName = $notifiable->name ?? ($this->order->address_details['first_name'] ?? 'عميلنا العزيز');

        return (new MailMessage)
            ->subject('فاتورة طلبك #' . $this->order->invoice_number)
            ->greeting('مرحباً ' . $customerName)
            ->line('شكراً لتسوقكم من لوكس بارفيوم.')
            ->line('تجدون مرفقاً طيه فاتورة طلبكم رقم ' . $this->order->invoice_number)
            ->action('عرض الفاتورة', route('orders.invoice.view', $this->order))
            ->attach(\Illuminate\Mail\Attachment::fromStorageDisk(config('invoice.disk', 'local'), $this->order->invoice_path)
                ->as('invoice_' . $this->order->invoice_number . '.pdf')
                ->withMime('application/pdf'))
            ->line('نتمنى لكم تجربة تسوق ممتعة!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'invoice_ready',
            'title' => 'فاتورة جاهزة #' . $this->order->invoice_number,
            'message' => 'تم إصدار فاتورة طلبك رقم ' . $this->order->invoice_number,
            'order_id' => $this->order->id,
            'icon' => 'fas fa-file-invoice-dollar',
            'color' => 'gold'
        ];
    }
}
