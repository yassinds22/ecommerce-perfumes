<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe PaymentIntent for an order.
     */
    public function createPaymentIntent(Order $order)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($order->total * 100), // Amount in cents
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);

            // Save the payment intent ID to the order
            $order->update([
                'stripe_payment_intent' => $paymentIntent->id,
                'payment_method' => 'stripe'
            ]);

            return $paymentIntent;
        } catch (\Exception $e) {
            Log::error('Stripe PaymentIntent Creation Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verify a Stripe Webhook signature.
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        $secret = config('services.stripe.webhook_secret');

        try {
            return Webhook::constructEvent($payload, $signature, $secret);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Stripe Webhook Signature Verification Failed: ' . $e->getMessage());
            return null;
        } catch (\UnexpectedValueException $e) {
            Log::error('Stripe Webhook Invalid Payload: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle a successful payment.
     */
    public function handlePaymentSuccess($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'Paid',
                    'status' => 'Processing' // Move to processing after payment
                ]);
                Log::info("Order #{$order->order_number} marked as PAID via Stripe.");
            }
        }
    }

    /**
     * Handle a failed payment.
     */
    public function handlePaymentFailure($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update(['payment_status' => 'failed']);
                Log::warning("Order #{$order->order_number} payment FAILED via Stripe.");
            }
        }
    }
}
