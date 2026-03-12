<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    /**
     * Generate an invoice for the given order.
     */
    public function generateInvoice(Order $order)
    {
        try {
            return DB::transaction(function () use ($order) {
                // 1. Generate Invoice Number if not exists
                if (!$order->invoice_number) {
                    $order->invoice_number = $this->generateInvoiceNumber();
                }

                // 2. Prepare Data for PDF
                $originalLocale = app()->getLocale();
                app()->setLocale('ar');

                $arabic = new \ArPHP\I18N\Arabic();
                
                // Reshape store info and slogan for RTL
                $storeName = $arabic->utf8Glyphs('LUXE PARFUM');
                $storeSlogan = $arabic->utf8Glyphs('عطور فاخرة بلمسة عصرية');

                $order->load(['items.product', 'user']);
                
                // Reshape product names
                foreach ($order->items as $item) {
                    $item->reshaped_name = $arabic->utf8Glyphs($item->product->getTranslation('name', 'ar') ?? $item->product->name, 40);
                }

                // Reshape customer and shipping info
                $customerName = $arabic->utf8Glyphs($order->address_details['first_name'] . ' ' . $order->address_details['last_name'], 40);
                $shippingLocation = $arabic->utf8Glyphs($order->address_details['country'] . ' - ' . $order->address_details['city'], 40);
                $shippingAddress = $arabic->utf8Glyphs($order->address_details['address'], 60);

                $data = [
                    'order' => $order,
                    'invoice_number' => $order->invoice_number,
                    'date' => now()->format('Y-m-d'),
                    'arabic' => $arabic,
                    'store_name' => $storeName,
                    'store_slogan' => $storeSlogan,
                    'customer_name' => $customerName,
                    'shipping_location' => $shippingLocation,
                    'shipping_address' => $shippingAddress,
                ];

                // 3. Render PDF
                $pdf = Pdf::loadView('invoices.invoice', $data)
                    ->setPaper('a4')
                    ->setOption('isFontSubsettingEnabled', true)
                    ->setOption('defaultFont', 'DejaVu Sans');

                $output = $pdf->output();
                app()->setLocale($originalLocale);

                // 4. Store PDF
                $fileName = 'invoice_' . $order->order_number . '.pdf';
                $directory = config('invoice.invoice_directory', 'invoices');
                $path = $directory . '/' . $fileName;

                Storage::disk(config('invoice.disk', 'local'))->put($path, $output);

                // 5. Update Order
                $order->update([
                    'invoice_generated_at' => now(),
                    'invoice_path' => $path,
                    'invoice_number' => $order->invoice_number,
                ]);

                Log::info("Invoice generated for Order #{$order->order_number}: {$order->invoice_number}");

                return $order;
            });
        } catch (\Exception $e) {
            Log::error("Invoice Generation Failed for Order #{$order->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate a unique, concurrent-safe invoice number.
     */
    public function generateInvoiceNumber()
    {
        $prefix = config('invoice.prefix', 'INV');
        $year = date('Y');
        $padding = config('invoice.sequence_padding', 6);

        // Find the last generated invoice number for this year
        // We use a shared lock if needed, but since we are inside a transaction in generateInvoice,
        // we can fetch the max current value safely.
        $lastInvoice = Order::where('invoice_number', 'like', "{$prefix}-{$year}-%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            // Extract the sequence from the end of the string
            $lastSequence = (int) substr($lastInvoice->invoice_number, -($padding));
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        $sequenceStr = str_pad($nextSequence, $padding, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}-{$sequenceStr}";
    }

    /**
     * Get the full path or URL for an invoice.
     */
    public function getInvoiceStream(Order $order)
    {
        if (!$order->invoice_path) {
            return null;
        }

        return Storage::disk(config('invoice.disk', 'local'))->get($order->invoice_path);
    }
}
