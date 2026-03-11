<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Create a Stripe PaymentIntent.
     */
    public function createPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Security check: Only the owner of the order can pay
        if (auth()->check() && $order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Order already paid'], 400);
        }

        $paymentIntent = $this->stripeService->createPaymentIntent($order);

        if (!$paymentIntent) {
            return response()->json(['error' => 'Could not create payment intent'], 500);
        }

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    /**
     * Handle payment success redirect.
     */
    public function success(Request $request)
    {
        // Stripe usually redirects back with payment_intent and payment_intent_client_secret
        // We can verify the status here if needed, but the Webhook is the source of truth.
        return view('clints.payment.success');
    }

    /**
     * Handle payment cancellation.
     */
    public function cancel()
    {
        return view('clints.payment.cancel');
    }

    /**
     * Handle Stripe Webhooks.
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $event = $this->stripeService->verifyWebhookSignature($payload, $sigHeader);

        if (!$event) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->stripeService->handlePaymentSuccess($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->stripeService->handlePaymentFailure($event->data->object);
                break;
            default:
                Log::info('Received unknown event type ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }
}
