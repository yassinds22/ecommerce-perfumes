<?php

namespace App\Reports;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TopProductsReport
{
    public function handle($filters)
    {
        $start = $filters['start_date'] ?? Carbon::now()->subDays(30);
        $end = $filters['end_date'] ?? Carbon::now();

        if (!$start instanceof Carbon) $start = Carbon::parse($start);
        if (!$end instanceof Carbon) $end = Carbon::parse($end)->endOfDay();

        $query = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(quantity * price) as revenue'))
            ->whereHas('order', function($q) use ($start, $end) {
                $q->where('payment_status', 'paid')
                  ->whereBetween('created_at', [$start, $end]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with(['product.category', 'product.brand'])
            ->take($filters['limit'] ?? 10);

        if (!empty($filters['category_id'])) {
            $query->whereHas('product', function($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }

        return $query->get()->map(function($item) {
            return [
                'product_id' => $item->product_id,
                'name' => $item->product ? $item->product->getTranslation('name', 'ar') : 'N/A',
                'category' => $item->product && $item->product->category ? $item->product->category->getTranslation('name', 'ar') : 'N/A',
                'brand' => $item->product && $item->product->brand ? $item->product->brand->name : 'N/A',
                'total_sold' => (int) $item->total_sold,
                'revenue' => (float) $item->revenue
            ];
        });
    }
}
