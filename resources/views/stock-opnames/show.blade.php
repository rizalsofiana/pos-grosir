@extends('layouts.admin')

@section('title', 'Detail Stok Opname')
@section('page-title', 'Detail Stok Opname')
@section('page-subtitle', $stockOpname->code)

@section('content')
    <div class="mb-4">
        <a href="{{ route('stock-opnames') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <div class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-3">
                <div>
                    <p class="text-xs text-slate-400">Tanggal</p>
                    <p class="font-medium text-slate-700">
                        {{ \Illuminate\Support\Carbon::parse($stockOpname->opname_date)->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Petugas</p>
                    <p class="font-medium text-slate-700">{{ $stockOpname->user?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400">Catatan</p>
                    <p class="font-medium text-slate-700">{{ $stockOpname->notes ?? '-' }}</p>
                </div>
            </div>
        </div>

        <table class="min-w-full text-sm">
            <thead class="bg-slate-50/90">
                <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                    <th class="px-5 py-3">Produk</th>
                    <th class="px-5 py-3 text-right">Stok Sistem</th>
                    <th class="px-5 py-3 text-right">Stok Fisik</th>
                    <th class="px-5 py-3 text-right">Selisih</th>
                    <th class="px-5 py-3">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($stockOpname->stockOpnameDetails as $detail)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-700">{{ $detail->product?->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-right text-slate-600">{{ $detail->system_stock }}</td>
                        <td class="px-5 py-3 text-right text-slate-600">{{ $detail->physical_stock }}</td>
                        <td class="px-5 py-3 text-right font-semibold {{ $detail->difference > 0 ? 'text-emerald-600' : ($detail->difference < 0 ? 'text-red-600' : 'text-slate-500') }}">
                            {{ $detail->difference > 0 ? '+' : '' }}{{ $detail->difference }}
                        </td>
                        <td class="px-5 py-3 text-slate-500">{{ $detail->note ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
