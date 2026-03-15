<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Get user orders.
     */
    public function getUserOrders(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->orders()->with('items.product')->latest()->paginate($perPage);
    }

    /**
     * Get paginated orders for admin.
     */
    public function getPaginatedOrders(int $perPage = 10): LengthAwarePaginator
    {
        return Order::with(['user', 'items.product'])->latest()->paginate($perPage);
    }

    /**
     * Get order statistics for dashboard.
     */
    public function getOrderStats(): array
    {
        return [
            'total_orders' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::whereIn('status', ['cancelled', 'canceled'])->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
        ];
    }

    /**
     * Get single order details.
     */
    public function getOrderDetails(User $user, Order $order): Order
    {
        if ($order->user_id !== $user->id) {
            throw new \Exception('Unauthorized', 403);
        }

        return $order->load('items.product');
    }

    /**
     * Process checkout and create order.
     */
    public function checkout(User $user, array $data): array
    {
        return DB::transaction(function () use ($user, $data) {
            $total = 0;
            $orderItems = [];

            foreach ($data['items'] as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                $itemTotal = $product->price * $itemData['quantity'];
                $total += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $product->price,
                    'purchase_price' => $product->purchase_price,
                    'sale_price' => $product->sale_price,
                    'profit' => ($product->price - $product->purchase_price) * $itemData['quantity'],
                ];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'status' => 'pending',
                'address_details' => $data['address_details'],
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            $result = ['order' => $order];

            if ($data['payment_method'] === 'stripe') {
                $paymentIntent = $this->stripeService->createPaymentIntent($order);
                if ($paymentIntent) {
                    $result['client_secret'] = $paymentIntent->client_secret;
                } else {
                    throw new \Exception('Stripe PaymentIntent creation failed.');
                }
            }

            return $result;
        });
    }
}
