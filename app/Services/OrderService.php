<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Traits\LogsActivity;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    use LogsActivity;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderService constructor.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Get paginated orders.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedOrders(int $perPage = 10): LengthAwarePaginator
    {
        return $this->orderRepository->getPaginatedOrders($perPage);
    }

    /**
     * Get order statistics.
     *
     * @return array
     */
    public function getOrderStats(): array
    {
        return [
            'pending' => $this->orderRepository->countByStatus('pending'),
            'shipped' => $this->orderRepository->countByStatus('shipped'),
            'completed' => $this->orderRepository->countByStatus('completed'),
            'cancelled' => $this->orderRepository->countByStatus('cancelled'),
        ];
    }

    /**
     * Get order by ID with details.
     *
     * @param int $id
     * @return Order
     */
    public function getOrderDetails(int $id): Order
    {
        return $this->orderRepository->getOrderWithDetails($id);
    }

    /**
     * Update order status.
     *
     * @param int $id
     * @param string $status
     * @return Order
     */
    public function updateOrderStatus(int $id, string $status): Order
    {
        $order = $this->orderRepository->findOrFail($id);
        $order->status = $status;

        if ($status === 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        } elseif ($status === 'delivered' && !$order->delivered_at) {
            $order->delivered_at = now();
        }

        $order->save();

        $this->logActivity('تحديث حالة الطلب', "تم تغيير حالة الطلب #{$order->id} إلى {$order->status}", $order);

        return $order;
    }

    /**
     * Update order shipping information.
     *
     * @param int $id
     * @param array $data
     * @return Order
     */
    public function updateOrderShipping(int $id, array $data): Order
    {
        $order = $this->orderRepository->findOrFail($id);
        $order->update([
            'shipping_company' => $data['shipping_company'],
            'tracking_number' => $data['tracking_number'],
        ]);

        if ($order->status === 'pending' || $order->status === 'processing') {
            $order->status = 'shipped';
            $order->shipped_at = $order->shipped_at ?: now();
            $order->save();
        }

        $this->logActivity('تحديث بيانات الشحن', "تم إضافة بيانات الشحن للطلب #{$order->id} ({$order->shipping_company})", $order);

        return $order;
    }

    /**
     * Delete an order.
     *
     * @param int $id
     * @return bool
     */
    public function deleteOrder(int $id): bool
    {
        return $this->orderRepository->delete($id);
    }
}
