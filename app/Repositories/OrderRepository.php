<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository 
{
    protected $order;
    /**
     * OrderRepository constructor.
     *
     * @param Order $model
     */
    public function __construct(Order $order)
    {
        $this->order=$order;;
    }
     public function all()
    {
        return $this->order->all();
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return order
     */
    public function create(array $data): order
    {
        return $this->order->create($data);
    }

    /**
     * Update an existing record.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $record = $this->order->findOrFail($id);
        return $record->update($data);
    }

    /**
     * Delete a record.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->order->destroy($id);
    }

    /**
     * Find a record by ID.
     *
     * @param int $id
     * @return order|null
     */
    public function find(int $id): order
    {
        return $this->order->find($id);
    }

    /**
     * Find a record by ID or fail.
     *
     * @param int $id
     * @return order
     */
    public function findOrFail(int $id): order
    {
        return $this->order->findOrFail($id);
    }

    /**
     * Get the total count of records.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->order->count();
    }


    /**
     * Get paginated orders with user relation.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedOrders(int $perPage = 10): LengthAwarePaginator
    {
        return $this->order->with(['user', 'items.product'])->latest()->paginate($perPage);
    }

    /**
     * Get paginated orders for a specific user.
     *
     * @param int $userId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedOrdersForUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->order->where('user_id', $userId)->with('items.product')->latest()->paginate($perPage);
    }

    /**
     * Count orders by status.
     *
     * @param string $status
     * @return int
     */
    public function countByStatus(string $status): int
    {
        return $this->order->where('status', $status)->count();
    }

    /**
     * Count orders in specific statuses.
     *
     * @param array $statuses
     * @return int
     */
    public function countInStatuses(array $statuses): int
    {
        return $this->order->whereIn('status', $statuses)->count();
    }

    /**
     * Get order with user and orders relations.
     *
     * @param int $id
     * @return Order
     */
    public function getOrderWithDetails(int $id): Order
    {
        return $this->order->with(['user', 'items.order'])->findOrFail($id);
    }

    /**
     * Get total revenue from completed orders.
     *
     * @return float
     */
    public function getTotalRevenue(): float
    {
        return (float) $this->order->where('status', 'completed')->sum('total');
    }

    /**
     * Get revenue within a date range.
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface|null $end
     * @return float
     */
    public function getRevenueBetween(\DateTimeInterface $start, \DateTimeInterface $end = null): float
    {
        $query = $this->order->where('status', 'completed')->where('created_at', '>=', $start);
        if ($end) {
            $query->where('created_at', '<', $end);
        }
        return (float) $query->sum('total');
    }

    /**
     * Count orders within a date range.
     *
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface|null $end
     * @return int
     */
    public function countOrdersBetween(\DateTimeInterface $start, \DateTimeInterface $end = null): int
    {
        $query = $this->order->where('created_at', '>=', $start);
        if ($end) {
            $query->where('created_at', '<', $end);
        }
        return $query->count();
    }

    /**
     * Get recent orders with user relation.
     *
     * @param int $count
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentOrders(int $count = 5)
    {
        return $this->order->with('user')->latest()->take($count)->get();
    }
}
