<?php

namespace App\Reports;

use App\Models\Order;
use Carbon\Carbon;

class OrdersStatusReport
{
    public function handle($filters)
    {
        $start = $filters['start_date'] ?? Carbon::now()->subDays(30);
        $end = $filters['end_date'] ?? Carbon::now();

        if (!$start instanceof Carbon) $start = Carbon::parse($start);
        if (!$end instanceof Carbon) $end = Carbon::parse($end)->endOfDay();

        $query = Order::query()
            ->whereBetween('created_at', [$start, $end]);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->select('status', \DB::raw('count(*) as count'), \DB::raw('SUM(total) as total_value'))
            ->groupBy('status')
            ->get();
    }
}
