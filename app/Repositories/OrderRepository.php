<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository
{
    /**
     * OrderRepository constructor.
     *
     * @param Order $model
     */
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated orders with user relation.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedOrders(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with('user')->withCount('items')->latest()->paginate($perPage);
    }

    /**
     * Count orders by status.
     *
     * @param string $status
     * @return int
     */
    public function countByStatus(string $status): int
    {
        return $this->model->where('status', $status)->count();
    }

    /**
     * Get order with user and products relations.
     *
     * @param int $id
     * @return Order
     */
    public function getOrderWithDetails(int $id): Order
    {
        return $this->model->with(['user', 'items.product'])->findOrFail($id);
    }

    /**
     * Get total revenue from completed orders.
     *
     * @return float
     */
    public function getTotalRevenue(): float
    {
        return (float) $this->model->where('status', 'completed')->sum('total');
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
        $query = $this->model->where('status', 'completed')->where('created_at', '>=', $start);
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
        $query = $this->model->where('created_at', '>=', $start);
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
        return $this->model->with('user')->latest()->take($count)->get();
    }
}
