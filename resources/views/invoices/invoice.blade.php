<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>فاتورة رقم {{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        /* ... simplified layout ... */
        .invoice-box {
            padding: 20px;
            font-size: 12px;
        }
        .header { width: 100%; margin-bottom: 20px; }
        .logo { font-size: 24px; color: #c9a84c; font-weight: bold; }
        .invoice-title { font-size: 20px; font-weight: bold; }
        .section-title { font-weight: bold; color: #c9a84c; border-bottom: 1px solid #c9a84c; margin-bottom: 10px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th { background: #f8f9fa; padding: 8px; border: 1px solid #ddd; }
        .items-table td { padding: 8px; border: 1px solid #ddd; }
        .summary-table { width: 250px; margin-right: auto; margin-top: 20px; }
        .total-row { font-weight: bold; color: #c9a84c; font-size: 16px; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="header-left">
                <div class="logo">{{ $store_name }}</div>
                <div>{{ $store_slogan }}</div>
            </div>
            <div class="header-right">
                <div class="invoice-title">{{ $arabic->utf8Glyphs('فاتورة ضريبية') }}</div>
                <div>{{ $arabic->utf8Glyphs('رقم الفاتورة:') }} <strong>{{ $order->invoice_number }}</strong></div>
                <div>{{ $arabic->utf8Glyphs('التاريخ:') }} {{ $date }}</div>
                <div>{{ $arabic->utf8Glyphs('رقم الطلب:') }} #{{ $order->order_number }}</div>
            </div>
        </div>

        <table class="details-table">
            <tr>
                <td width="50%">
                    <div class="section-title">{{ $arabic->utf8Glyphs('بيانات العميل') }}</div>
                    <div>{{ $customer_name }}</div>
                    <div>{{ $order->address_details['email'] }}</div>
                    <div>{{ $order->address_details['phone'] }}</div>
                </td>
                <td width="50%">
                    <div class="section-title">{{ $arabic->utf8Glyphs('عنوان الشحن') }}</div>
                    <div>{{ $shipping_location }}</div>
                    <div>{{ $shipping_address }}</div>
                    <div>{{ $arabic->utf8Glyphs('الرمز البريدي:') }} {{ $order->address_details['zip'] }}</div>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ $arabic->utf8Glyphs('المنتج') }}</th>
                    <th>{{ $arabic->utf8Glyphs('الكمية') }}</th>
                    <th>{{ $arabic->utf8Glyphs('سعر الوحدة') }}</th>
                    <th>{{ $arabic->utf8Glyphs('الإجمالي') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->reshaped_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="summary-table">
            <tr>
                <td>{{ $arabic->utf8Glyphs('المجموع الفرعي:') }}</td>
                <td align="left">${{ number_format($order->total - $order->shipping_cost, 2) }}</td>
            </tr>
            <tr>
                <td>{{ $arabic->utf8Glyphs('الشحن:') }}</td>
                <td align="left">${{ number_format($order->shipping_cost, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>{{ $arabic->utf8Glyphs('الإجمالي الكلي:') }}</td>
                <td align="left">${{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>{{ $arabic->utf8Glyphs('شكراً لشرائكم من لوكس بارفيوم.') }}</p>
            <p>{{ $arabic->utf8Glyphs('جميع الحقوق محفوظة &copy;') }} {{ date('Y') }} Luxe Parfum</p>
            <p>{{ $arabic->utf8Glyphs('هذه الفاتورة تم إنشاؤها آلياً ولا تتطلب توقيعاً.') }}</p>
        </div>
    </div>
</body>
</html>
