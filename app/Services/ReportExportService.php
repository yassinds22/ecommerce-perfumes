<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Response;

class ReportExportService
{
    /**
     * Export collection to CSV.
     */
    public function exportToCsv(string $filename, array $headers, Collection $data)
    {
        $handle = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel Arabic support
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($handle, $headers);

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return Response::make('', 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ]);
    }

    /**
     * Export to PDF.
     */
    public function exportToPdf(string $filename, string $view, array $data)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, $data);
        return $pdf->download($filename . '.pdf');
    }

    /**
     * Prepare Dynamic Report Data for Export.
     */
    public function prepareReportExportData(string $type, Collection|array $data)
    {
        $collection = collect($data);

        return $collection->map(function ($item) use ($type) {
            switch($type) {
                case 'daily_sales':
                    return [
                        data_get($item, 'date'),
                        data_get($item, 'orders_count'),
                        data_get($item, 'revenue'),
                        data_get($item, 'items_sold')
                    ];
                case 'orders_status':
                    return [
                        data_get($item, 'status'),
                        data_get($item, 'count'),
                        data_get($item, 'total_value')
                    ];
                case 'inventory':
                    return [
                        data_get($item, 'sku'),
                        data_get($item, 'name'),
                        data_get($item, 'category'),
                        data_get($item, 'stock'),
                        data_get($item, 'price'),
                        data_get($item, 'status')
                    ];
                case 'top_products':
                    return [
                        data_get($item, 'name'),
                        data_get($item, 'category'),
                        data_get($item, 'brand'),
                        data_get($item, 'total_sold'),
                        data_get($item, 'revenue')
                    ];
                case 'profit':
                    return [
                        data_get($item, 'date'),
                        data_get($item, 'revenue'),
                        data_get($item, 'estimated_cost'),
                        data_get($item, 'profit'),
                        round(data_get($item, 'profit_margin', 0), 2) . '%'
                    ];
                default:
                    return (array) $item;
            }
        });
    }

    public function getReportHeaders(string $type): array
    {
        return match($type) {
            'daily_sales' => ['Date', 'Orders Count', 'Revenue', 'Items Sold'],
            'orders_status' => ['Status', 'Orders Count', 'Total Value'],
            'inventory' => ['SKU', 'Name', 'Category', 'Stock', 'Price', 'Status'],
            'top_products' => ['Product', 'Category', 'Brand', 'Total Sold', 'Revenue'],
            'profit' => ['Date', 'Revenue', 'Purchase Cost', 'Net Profit', 'Profit Margin %'],
            default => [],
        };
    }

    public function getReportTitle(string $type): string
    {
        return match($type) {
            'daily_sales' => 'Daily Sales Report',
            'orders_status' => 'Orders Status Report',
            'inventory' => 'Inventory Status Report',
            'top_products' => 'Top Selling Products Report',
            'profit' => 'Profit & Margin Report',
            default => 'System Report',
        };
    }
}
