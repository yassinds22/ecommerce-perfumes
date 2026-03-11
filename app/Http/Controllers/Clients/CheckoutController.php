<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    public function placeOrder(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                // 1. Pessimistic Locking & Stock Verification (Inside Transaction)
                if ($request->has('items')) {
                    foreach ($request->items as $item) {
                        $product = \App\Models\Product::lockForUpdate()->find($item['id']);
                        if (!$product || $product->stock_quantity < $item['qty']) {
                            throw new \Exception('Product "' . ($product ? $product->name : 'Unknown') . '" is out of stock.', 422);
                        }
                    }
                }

                $order = Order::create([
                    'user_id' => auth()->id(), // Allow guest checkout since migration is nullable
                    'order_number' => 'LP-' . date('Ymd') . '-' . rand(1000, 9999),
                    'total' => $request->total,
                    'status' => 'pending',
                    'address_details' => [
                        'first_name' => $request->firstName,
                        'last_name' => $request->lastName,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'city' => $request->city,
                        'zip' => $request->zip,
                        'country' => $request->country,
                    ],
                    'payment_method' => $request->payment_method ?? 'stripe',
                    'payment_status' => 'pending'
                ]);

                // Create Order Items and Update Stock
                if ($request->has('items')) {
                    $stockService = app(\App\Services\StockService::class);
                    foreach ($request->items as $item) {
                        \App\Models\OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item['id'],
                            'quantity' => $item['qty'],
                            'price' => $item['price'], // Fixed field name
                        ]);

                        $product = \App\Models\Product::find($item['id']);
                        if ($product) {
                            $stockService->decrease($product, $item['qty'], "طلب رقم #{$order->order_number}");
                        }
                    }
                }

                $admins = User::where('role', 'Admin')->get();
                if ($admins->isNotEmpty()) {
                    Notification::send($admins, new NewOrderNotification($order));
                }

                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'message' => 'تم استلام طلبك بنجاح'
                ]);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order Creation Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
