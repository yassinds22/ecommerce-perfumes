<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @var OrderService
     */
    protected $orderService;

    /**
     * OrderController constructor.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->getPaginatedOrders(10);
        $orderStats = $this->orderService->getOrderStats();
        
        if ($request->ajax()) {
            return view('admin.sections.orders', compact('orders', 'orderStats'))->render();
        }
        
        return view('admin.orders.index', compact('orders', 'orderStats'));
    }

    public function show($id)
    {
        $order = $this->orderService->getOrderDetails($id);
        return response()->json($order);
    }

    public function updateStatus(Request $request, $id)
    {
        $this->orderService->updateOrderStatus($id, $request->status);
        return response()->json(['success' => true, 'message' => 'تم تحديث حالة الطلب بنجاح']);
    }

    public function updateShipping(Request $request, $id)
    {
        $request->validate([
            'shipping_company' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
        ]);

        $this->orderService->updateOrderShipping($id, $request->only(['shipping_company', 'tracking_number']));

        return response()->json(['success' => true, 'message' => 'تم تحديث بيانات الشحن بنجاح']);
    }

    public function destroy($id)
    {
        $this->orderService->deleteOrder($id);
        return response()->json(['success' => true, 'message' => 'تم حذف الطلب بنجاح']);
    }
}
