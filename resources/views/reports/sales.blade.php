@extends('layouts.admin')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Ringkasan penjualan & laba kotor per periode')

@section('content')
    @include('reports.partials.tabs')

    <form method="GET"
        class="mb-6 flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate }}"
                class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate }}"
                class="rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
        </div>
        <input type="hidden" name="per_page" value="{{ $perPage }}">
        <button type="submit"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Filter</button>
        <a href="{{ route('reports.sales.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
            class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">Export
            Excel</a>
    </form>


    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-400">Total Omzet</p>
            <p class="mt-1 text-xl font-bold text-slate-800">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-400">Total Diskon</p>
            <p class="mt-1 text-xl font-bold text-slate-800">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-400">Jumlah Transaksi</p>
            <p class="mt-1 text-xl font-bold text-slate-800">{{ $totalTransactions }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-400">Laba Kotor</p>
            <p class="mt-1 text-xl font-bold text-emerald-600">Rp {{ number_format($grossProfit, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-slate-800">Detail Transaksi</h2>
                <p class="text-xs text-slate-400">{{ $sales->total() }} transaksi pada periode ini</p>
            </div>
            <form method="GET" class="flex items-center gap-2 text-sm text-slate-500">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <label for="per_page">Tampilkan</label>
                <select id="per_page" name="per_page" onchange="this.form.submit()"
                    class="rounded-lg border border-slate-200 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none">
                    @foreach ([10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                            {{ $option }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                        <th class="px-5 py-3">Invoice</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Customer</th>
                        <th class="px-5 py-3">Metode Bayar</th>
                        <th class="px-5 py-3 text-right">Diskon</th>
                        <th class="px-5 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($sales as $sale)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3 font-medium text-slate-700">{{ $sale->invoice_number }}</td>
                            <td class="px-5 py-3 text-slate-500">{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3 text-slate-500">{{ $sale->customer?->name ?? '-' }}</td>
                            <td class="px-5 py-3 capitalize text-slate-500">{{ $sale->payment_method }}</td>
                            <td class="px-5 py-3 text-right text-slate-500">Rp
                                {{ number_format($sale->discount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-slate-800">Rp
                                {{ number_format($sale->grand_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="16" rx="2" />
                                        <path d="M8 2v4M16 2v4M3 10h18" />
                                    </svg>
                                    <p class="text-sm">Tidak ada transaksi pada periode ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between border-t border-slate-100 px-5 py-3 text-sm text-slate-500">
            <span>
                Menampilkan {{ $sales->firstItem() ?? 0 }}-{{ $sales->lastItem() ?? 0 }}
                dari {{ $sales->total() }} data
            </span>
            <div>
                {{ $sales->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
