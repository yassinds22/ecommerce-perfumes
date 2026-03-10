<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        $orders = Order::with('user')->latest()->paginate(10);
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        if ($request->ajax()) {
            return view('admin.sections.orders', compact('orders', 'orderStats'))->render();
        }
        
        return view('admin.orders.index', compact('orders', 'orderStats'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'products'])->findOrFail($id);
        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;

        // Auto-set timestamps based on status
        if ($request->status === 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        } elseif ($request->status === 'delivered' && !$order->delivered_at) {
            $order->delivered_at = now();
        }

        $order->save();

        $this->logActivity('تحديث حالة الطلب', "تم تغيير حالة الطلب #{$order->id} إلى {$order->status}", $order);

        return response()->json(['success' => true, 'message' => 'تم تحديث حالة الطلب بنجاح']);
    }

    public function updateShipping(Request $request, $id)
    {
        $request->validate([
            'shipping_company' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'shipping_company' => $request->shipping_company,
            'tracking_number' => $request->tracking_number,
        ]);

        // If not already shipped, set it to shipped
        if ($order->status === 'pending' || $order->status === 'processing') {
            $order->status = 'shipped';
            $order->shipped_at = $order->shipped_at ?: now();
            $order->save();
        }

        $this->logActivity('تحديث بيانات الشحن', "تم إضافة بيانات الشحن للطلب #{$order->id} ({$order->shipping_company})", $order);

        return response()->json(['success' => true, 'message' => 'تم تحديث بيانات الشحن بنجاح']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف الطلب بنجاح']);
    }
}
