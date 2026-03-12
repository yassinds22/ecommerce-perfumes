<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Services\LoyaltyService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AwardLoyaltyPointsListener implements ShouldQueue
{
    protected $loyaltyService;

    /**
     * Create the event listener.
     */
    public function __construct(LoyaltyService $loyaltyService)
    {
        $this->loyaltyService = $loyaltyService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCompleted $event): void
    {
        $order = $event->order;

        // Only award points to registered users
        if (!$order->user_id) {
            return;
        }

        $user = $order->user;
        if (!$user) {
            return;
        }

        $points = $this->loyaltyService->calculateEarnedPoints($order->total);

        if ($points > 0) {
            $this->loyaltyService->addPoints(
                $user, 
                $points, 
                "نقاط مكتسبة من الطلب رقم #{$order->order_number}", 
                $order
            );
        }
    }
}
