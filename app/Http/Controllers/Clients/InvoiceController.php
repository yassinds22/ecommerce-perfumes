<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Preview the invoice in the browser.
     */
    public function view(Order $order)
    {
        Gate::authorize('view', $order);

        if (!$order->invoice_path) {
            $this->invoiceService->generateInvoice($order);
            $order->refresh();
        }

        if (!$order->invoice_path) {
            abort(404, 'Invoice could not be generated.');
        }

        return Storage::disk(config('invoice.disk', 'local'))->response($order->invoice_path, 'invoice_' . $order->order_number . '.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice_' . $order->order_number . '.pdf"'
        ]);
    }

    /**
     * Download the invoice PDF.
     */
    public function download(Order $order)
    {
        Gate::authorize('view', $order);

        if (!$order->invoice_path) {
            $this->invoiceService->generateInvoice($order);
            $order->refresh();
        }

        if (!$order->invoice_path) {
            abort(404, 'Invoice could not be generated.');
        }

        return Storage::disk(config('invoice.disk', 'local'))->download($order->invoice_path, 'invoice_' . $order->order_number . '.pdf');
    }
}
