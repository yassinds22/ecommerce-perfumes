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
     * Prepare Sales Report Data for Export.
     */
    public function prepareSalesExportData(Collection $orders)
    {
        return $orders->map(function ($order) {
            return [
                $order->order_number,
                $order->user->name ?? 'Guest',
                $order->total,
                $order->status,
                $order->payment_method,
                $order->created_at->format('Y-m-d H:i')
            ];
        });
    }

    /**
     * Prepare Inventory Report Data for Export.
     */
    public function prepareInventoryExportData(Collection $products)
    {
        return $products->map(function ($product) {
            return [
                $product->sku,
                $product->getTranslation('name', 'ar'),
                $product->category->getTranslation('name', 'ar') ?? '-',
                $product->stock_quantity,
                $product->price,
                $product->status ? 'Active' : 'Inactive'
            ];
        });
    }
}
