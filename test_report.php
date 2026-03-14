<?php

$filters = ['start_date' => '2026-03-09', 'end_date' => '2026-03-13'];
$report = new \App\Reports\DailySalesReport();
try {
    $data = $report->handle($filters);
    echo "SUCCESS\n";
    print_r($data->toArray());
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
