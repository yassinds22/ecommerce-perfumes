<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Services\ReportExportService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;

class ReportController extends Controller
{
    protected $reportService;
    protected $exportService;

    public function __construct(ReportService $reportService, ReportExportService $exportService)
    {
        $this->reportService = $reportService;
        $this->exportService = $exportService;
    }

    /**
     * Display reports dashboard.
     */
    public function index(Request $request)
    {
        $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
        
        $filters = $request->only(['category_id', 'brand_id', 'customer_type']);

        $sales = $this->reportService->getSalesMetrics($startDate, $endDate, $filters);
        $inventory = $this->reportService->getInventoryMetrics($filters);
        $customers = $this->reportService->getCustomerMetrics($startDate, $endDate);
        $marketing = $this->reportService->getMarketingMetrics();
        $full_inventory = $this->reportService->getFullInventory($filters);

        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::all();

        return view('admin.reports.index', compact(
            'sales', 'inventory', 'customers', 'marketing', 'full_inventory', 
            'categories', 'brands'
        ));
    }

    /**
     * Export Sales Report.
     */
    public function exportSales(Request $request)
    {
        $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $orders = Order::with('user')
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        if ($request->get('format') === 'pdf') {
            return $this->exportService->exportToPdf('sales_report_' . now()->format('Ymd'), 'admin.reports.pdf.sales', [
                'orders' => $orders,
                'startDate' => $startDate->toDateString(),
                'endDate' => $endDate->toDateString(),
                'totalRevenue' => $orders->sum('total'),
                'ordersCount' => $orders->count(),
            ]);
        }

        $data = $this->exportService->prepareSalesExportData($orders);
        $headers = ['رقم الطلب', 'العميل', 'الإجمالي', 'الحالة', 'طريقة الدفع', 'التاريخ'];

        return $this->exportService->exportToCsv('sales_report_' . now()->format('Ymd'), $headers, $data);
    }

    /**
     * Export Inventory Report.
     */
    public function exportInventory()
    {
        $products = Product::with('category')->get();
        $data = $this->exportService->prepareInventoryExportData($products);
        $headers = ['SKU', 'الاسم', 'القسم', 'المخزون', 'السعر', 'الحالة'];

        return $this->exportService->exportToCsv('inventory_report_' . now()->format('Ymd'), $headers, $data);
    }
}
