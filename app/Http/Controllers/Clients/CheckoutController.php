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
        // 1. Initial Stock Verification (Prevent starting transaction if stock is missing)
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $product = \App\Models\Product::find($item['id']);
                if (!$product || $product->stock_quantity < $item['qty']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'عذراً، المنتج "' . ($product ? $product->name : 'غير موجود') . '" غير متوفر بالكمية المطلوبة.'
                    ], 422);
                }
            }
        }

        return DB::transaction(function () use ($request) {
            $order = Order::create([
                'user_id' => auth()->id() ?? 1,
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
                'payment_method' => $request->payment_method ?? 'card',
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
                        'price_at_purchase' => $item['price'],
                    ]);

                    // Decrease Stock (This also logs the movement)
                    $product = \App\Models\Product::find($item['id']);
                    if ($product) {
                        $stockService->decrease($product, $item['qty'], "طلب رقم #{$order->order_number}");
                    }
                }
            }

            // Notify all Admins
            $admins = User::where('role', 'Admin')->get();
            try {
                Notification::send($admins, new NewOrderNotification($order));
            } catch (\Exception $e) {
                // Log error but don't break the order process
                \Log::error("Notification failed: " . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'message' => 'تم استلام طلبك بنجاح'
            ]);
        });
    }
}
