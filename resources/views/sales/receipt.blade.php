<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Struk {{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            width: 280px;
            margin: 0 auto;
            padding: 12px;
            font-size: 12px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 2px 0;
        }

        .right {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="center">
        <strong>POS GROSIR</strong><br>
        Struk Penjualan
    </div>
    <div class="line"></div>
    <div>
        No: {{ $sale->invoice_number }}<br>
        Tanggal: {{ $sale->sale_date->format('d/m/Y H:i') }}<br>
        Kasir: {{ $sale->user?->name ?? '-' }}<br>
        Customer: {{ $sale->customer?->name ?? '-' }}
    </div>
    <div class="line"></div>
    <table>
        @foreach ($sale->saleDetails as $detail)
            <tr>
                <td colspan="2">{{ $detail->product?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td class="right">{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
    <div class="line"></div>
    <table>
        <tr>
            <td>Sub Total</td>
            <td class="right">{{ number_format($sale->sub_total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="right">{{ number_format($sale->discount, 0, ',', '.') }}</td>
        </tr>
        <tr class="total-row">
            <td>Total</td>
            <td class="right">{{ number_format($sale->grand_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="right capitalize">{{ $sale->payment_method }}</td>
        </tr>
    </table>
    <div class="line"></div>
    <div class="center">Terima kasih atas kunjungan Anda</div>

    <div class="no-print center" style="margin-top: 12px;">
        <button onclick="window.print()">Cetak Ulang</button>
    </div>
</body>

</html>
