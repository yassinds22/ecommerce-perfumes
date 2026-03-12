<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        $kpis = $this->analyticsService->getKPIMetrics();
        $revenueTrends = $this->analyticsService->getRevenueTrends();
        $topProducts = $this->analyticsService->getTopProducts();
        $topCustomers = $this->analyticsService->getTopCustomers();

        return view('admin.analytics.index', compact(
            'kpis', 
            'revenueTrends', 
            'topProducts', 
            'topCustomers'
        ));
    }
}
