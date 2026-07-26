<?php

namespace App\Exports;

use App\Models\Purchase;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PurchasesExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private string $startDate, private string $endDate)
    {
    }

    public function query()
    {
        return Purchase::query()
            ->with('supplier')
            ->whereDate('purchase_date', '>=', $this->startDate)
            ->whereDate('purchase_date', '<=', $this->endDate)
            ->orderByDesc('purchase_date');
    }

    public function headings(): array
    {
        return ['Tanggal', 'Supplier', 'Total'];
    }

    public function map($purchase): array
    {
        return [
            $purchase->purchase_date->format('d/m/Y H:i'),
            $purchase->supplier?->name ?? '-',
            $purchase->total_amount,
        ];
    }
}
