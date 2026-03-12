<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateInvoiceJob implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct(\App\Models\Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\InvoiceService $invoiceService): void
    {
        $this->order = $invoiceService->generateInvoice($this->order);
        
        // Notify the user (Auth or Guest)
        if ($this->order->user) {
            $this->order->user->notify(new \App\Notifications\OrderInvoiceNotification($this->order));
            \Illuminate\Support\Facades\Log::info("Invoice notification sent to User #{$this->order->user->id}");
        } elseif (isset($this->order->address_details['email'])) {
            // For Guest Checkout
            \Illuminate\Support\Facades\Notification::route('mail', $this->order->address_details['email'])
                ->notify(new \App\Notifications\OrderInvoiceNotification($this->order));
            \Illuminate\Support\Facades\Log::info("Invoice notification sent to Guest: " . $this->order->address_details['email']);
        }
    }

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public function backoff()
    {
        return [60, 300, 600]; // 1 min, 5 min, 10 min
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::error("GenerateInvoiceJob failed for Order #{$this->order->id}: " . $exception->getMessage());
    }
}
