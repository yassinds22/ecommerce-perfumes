<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateMissingInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:generate-missing {--limit=100 : The number of orders to process}';

    protected $description = 'Generate missing invoices for paid/processing orders.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = $this->option('limit');
        
        $orders = \App\Models\Order::whereNull('invoice_path')
            ->whereIn('status', ['processing', 'shipped', 'delivered', 'completed'])
            ->limit($limit)
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No missing invoices found.');
            return 0;
        }

        $this->info("Found {$orders->count()} orders with missing invoices. Starting generation...");

        $bar = $this->output->createProgressBar($orders->count());

        foreach ($orders as $order) {
            \App\Jobs\GenerateInvoiceJob::dispatch($order);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Invoice generation jobs have been dispatched.');

        return 0;
    }
}
