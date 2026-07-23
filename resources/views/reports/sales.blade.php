@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Ringkasan penjualan & laba kotor per periode')

@section('content')
    @include('reports.partials.tabs')

    <form method="GET" class="mb-6 flex flex-wrap items-end gap-3 rounded-xl bg-white p-4 shadow">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-600">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}" class="rounded border px-3 py-2 text-sm">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-600">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}" class="rounded border px-3 py-2 text-sm">
        </div>
        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">Filter</button>
    </form>

    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs text-slate-500">Total Omzet</p>
            <p class="mt-1 text-xl font-semibold">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs text-slate-500">Total Diskon</p>
            <p class="mt-1 text-xl font-semibold">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs text-slate-500">Jumlah Transaksi</p>
            <p class="mt-1 text-xl font-semibold">{{ $totalTransactions }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs text-slate-500">Laba Kotor</p>
            <p class="mt-1 text-xl font-semibold text-green-600">Rp {{ number_format($grossProfit, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <h2 class="mb-4 font-semibold">Detail Transaksi</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Invoice</th>
                        <th class="py-2">Tanggal</th>
                        <th class="py-2">Customer</th>
                        <th class="py-2">Metode Bayar</th>
                        <th class="py-2">Diskon</th>
                        <th class="py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr class="border-b">
                            <td class="py-2">{{ $sale->invoice_number }}</td>
                            <td class="py-2">{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                            <td class="py-2">{{ $sale->customer?->name ?? '-' }}</td>
                            <td class="py-2 capitalize">{{ $sale->payment_method }}</td>
                            <td class="py-2">Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                            <td class="py-2 font-medium">Rp {{ number_format($sale->grand_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-500">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
