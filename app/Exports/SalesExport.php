<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private string $startDate, private string $endDate)
    {
    }

    public function query()
    {
        return Sale::query()
            ->with('customer')
            ->whereDate('sale_date', '>=', $this->startDate)
            ->whereDate('sale_date', '<=', $this->endDate)
            ->orderByDesc('sale_date');
    }

    public function headings(): array
    {
        return ['Invoice', 'Tanggal', 'Metode Bayar', 'Diskon', 'Total'];
    }

    public function map($sale): array
    {
        return [
            $sale->invoice_number,
            $sale->sale_date->format('d/m/Y H:i'),
            // $sale->customer?->name ?? '-',
            $sale->payment_method,
            $sale->discount,
            $sale->grand_amount,
        ];
    }
}
