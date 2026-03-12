<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Invoice Prefix
    |--------------------------------------------------------------------------
    |
    | This value will be prepended to the invoice number.
    |
    */
    'prefix' => 'INV',

    /*
    |--------------------------------------------------------------------------
    | Storage Settings
    |--------------------------------------------------------------------------
    |
    | The disk and directory where invoices will be stored.
    |
    */
    'disk' => 'local',
    'invoice_directory' => 'invoices',

    /*
    |--------------------------------------------------------------------------
    | QR Code Settings
    |--------------------------------------------------------------------------
    |
    | Enable or disable QR code generation. Requires PHP GD extension.
    |
    */
    'qr_enabled' => true,
    'qr_size' => 120,

    /*
    |--------------------------------------------------------------------------
    | Sequence Settings
    |--------------------------------------------------------------------------
    |
    | Controls how the invoice number sequence is generated.
    |
    */
    'year_reset' => true,
    'sequence_padding' => 6,
    
    /*
    |--------------------------------------------------------------------------
    | Font Settings
    |--------------------------------------------------------------------------
    |
    | The default font used for PDF generation.
    |
    */
    'font' => 'Cairo',
];
