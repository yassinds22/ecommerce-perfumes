<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $review;

    public function __construct($review)
    {
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_review',
            'title' => 'تقييم جديد بانتظار المراجعة',
            'message' => "قام " . ($this->review->user->name ?? 'عميل') . " بإضافة تقييم على " . ($this->review->product->name ?? 'منتج'),
            'review_id' => $this->review->id,
            'icon' => 'fas fa-star',
            'color' => 'info'
        ];
    }
}
