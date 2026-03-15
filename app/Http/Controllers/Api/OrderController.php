<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Http\Resources\Api\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Checkout & Orders
 *
 * API endpoints for placing orders, managing checkout, and viewing purchase history.
 */
class OrderController extends BaseApiController
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * View order history.
     * 
     * **Use Case**: Enables customers to track their previous purchases and re-order their favorite fragrances.
     * 
     * Retrieve a paginated list of all orders placed by the authenticated user.
     * 
     * @authenticated
     * @queryParam per_page int Results per page. Example: 10
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderService->getUserOrders($request->user(), $request->get('per_page', 10));
        return $this->success(OrderResource::collection($orders)->response()->getData(true));
    }

    /**
     * View order details.
     * 
     * Retrieve comprehensive information about a specific order, including its items and payment status.
     * 
     * @authenticated
     * @urlParam order int required The ID of the order. Example: 1
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        $order = $this->orderService->getOrderDetails($request->user(), $order);
        return $this->success(new OrderResource($order));
    }

    /**
     * Place an order (Checkout).
     * 
     * **Use Case**: Final audit and execution of the purchase. Orchestrates inventory checks, payment processing, and order lifecycle initialization.
     * 
     * Process a new purchase, calculate totals, and initialize payment (Stripe or COD).
     * 
     * @authenticated
     * @bodyParam items array required The list of products to purchase.
     * @bodyParam items.*.product_id int required Product ID. Example: 5
     * @bodyParam items.*.quantity int required Number of units. Example: 2
     * @bodyParam payment_method string required Payment gateway choice (`stripe` or `cod`). Example: stripe
     * @bodyParam address_details object required shipping address information.
     * @bodyParam address_details.city string required Shipping city. Example: Dubai
     * @bodyParam address_details.address_line string required Full street address. Example: Downtown 123
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:stripe,cod',
            'address_details' => 'required|array',
            'address_details.city' => 'required|string',
            'address_details.address_line' => 'required|string',
        ]);

        $result = $this->orderService->checkout($request->user(), $request->all());

        $responseData = [
            'order' => new OrderResource($result['order']->load('items.product')),
        ];

        if (isset($result['client_secret'])) {
            $responseData['client_secret'] = $result['client_secret'];
        }

        return $this->success($responseData, 'Order placed successfully', 201);
    }
}
