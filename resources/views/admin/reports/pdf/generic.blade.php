<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            direction: ltr;
            text-align: left;
            font-size: 12px;
            color: #333;
        }
        .header { 
            text-align: left; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #d4af37;
            padding-bottom: 10px;
        }
        .header h1 { color: #d4af37; margin-bottom: 5px; }
        .meta { margin-bottom: 20px; color: #666; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #eee; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f9f9f9; 
            color: #d4af37;
            font-weight: bold;
        }
        tr:nth-child(even) { background-color: #fafafa; }
        .footer { 
            position: fixed; 
            bottom: 0; 
            width: 100%; 
            text-align: center; 
            font-size: 10px; 
            color: #999; 
            padding: 10px 0;
            border-top: 1px solid #eee;
        }
        .summary-box {
            background: #fff9e6;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ffeeba;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Luxe Parfum</h1>
        <h2>{{ $title }}</h2>
    </div>

    <div class="meta">
        Export Date: {{ now()->format('Y-m-d H:i') }}<br>
        Period: From {{ $startDate }} To {{ $endDate }}
    </div>

    @if(isset($summary))
    <div class="summary-box">
        @foreach($summary as $label => $value)
            <strong>{{ $label }}:</strong> {{ $value }} &nbsp;&nbsp;&nbsp;
        @endforeach
    </div>
    @endif

    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                @foreach($row as $cell)
                <td>{{ $cell }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        This report was automatically generated from the Luxe Parfum system
    </div>
</body>
</html>
