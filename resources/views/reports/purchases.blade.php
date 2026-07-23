@extends('layouts.admin')

@section('title', 'Laporan Pembelian')
@section('page-title', 'Laporan Pembelian')
@section('page-subtitle', 'Ringkasan pembelian barang per periode')

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

    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs text-slate-500">Total Pembelian</p>
            <p class="mt-1 text-xl font-semibold">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs text-slate-500">Jumlah Transaksi</p>
            <p class="mt-1 text-xl font-semibold">{{ $totalTransactions }}</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <h2 class="mb-4 font-semibold">Detail Transaksi</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">Tanggal</th>
                        <th class="py-2">Supplier</th>
                        <th class="py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchases as $purchase)
                        <tr class="border-b">
                            <td class="py-2">{{ $purchase->purchase_date->format('d/m/Y H:i') }}</td>
                            <td class="py-2">{{ $purchase->supplier?->name ?? '-' }}</td>
                            <td class="py-2 font-medium">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-500">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
