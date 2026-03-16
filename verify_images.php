<?php
$files = [
    'd:\\all-project\\ecomm-perfumes\\screenshots\\home.png',
    'd:\\all-project\\ecomm-perfumes\\screenshots\\product.png',
    'd:\\all-project\\ecomm-perfumes\\screenshots\\dashboard.png'
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "$file: File does not exist\n";
        continue;
    }
    $handle = fopen($file, "rb");
    $sig = fread($handle, 8);
    fclose($handle);
    
    $expected = "\x89PNG\r\n\x1a\n";
    if ($sig === $expected) {
        echo "$file: Valid PNG Signature\n";
    } else {
        echo "$file: Invalid Signature (" . bin2hex($sig) . ")\n";
    }
}
