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
        $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
        
        $filters = $request->only(['category_id', 'brand_id', 'status', 'report_type']);
        $filters['start_date'] = $startDate;
        $filters['end_date'] = $endDate;

        $reportType = $request->get('report_type', 'daily_sales');

        $sales = $this->reportService->getSalesMetrics($startDate, $endDate, $filters);
        $inventory = $this->reportService->getInventoryMetrics($filters);
        $customers = $this->reportService->getCustomerMetrics($startDate, $endDate);
        
        $reportData = $this->reportService->getReportData($reportType, $filters);

        if ($request->ajax()) {
            return response()->json([
                'sales' => $sales,
                'inventory' => $inventory,
                'customers' => $customers,
                'reportData' => $reportData,
                'reportType' => $reportType
            ]);
        }

        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brand::all();

        return view('admin.reports.index', compact(
            'sales', 'inventory', 'customers', 'reportData', 
            'categories', 'brands', 'reportType'
        ));
    }

    /**
     * Export Report.
     */
    public function export(Request $request)
    {
        $startDate = $request->has('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->has('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
        
        $filters = $request->only(['category_id', 'brand_id', 'status', 'report_type']);
        $filters['start_date'] = $startDate;
        $filters['end_date'] = $endDate;

        $type = $request->get('report_type', 'daily_sales');
        $format = $request->get('format', 'csv');

        $data = $this->reportService->getReportData($type, $filters);
        $headers = $this->exportService->getReportHeaders($type);
        $exportData = $this->exportService->prepareReportExportData($type, $data);

        $filename = $type . '_report_' . now()->format('Ymd');

        if ($format === 'pdf') {
            $title = $this->exportService->getReportTitle($type);
            $viewData = [
                'title' => $title,
                'headers' => $headers,
                'rows' => $exportData,
                'startDate' => $startDate->toDateString(),
                'endDate' => $endDate->toDateString(),
                'summary' => [
                    'Total Results' => count($data),
                    'Export Date' => now()->format('Y-m-d')
                ]
            ];

            return $this->exportService->exportToPdf($filename, 'admin.reports.pdf.generic', $viewData);
        }

        return $this->exportService->exportToCsv($filename, $headers, $exportData);
    }
}
