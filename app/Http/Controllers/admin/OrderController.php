<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
        $order->save();

        return response()->json(['success' => true, 'message' => 'تم تحديث حالة الطلب بنجاح']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف الطلب بنجاح']);
    }
}
