@extends('layouts.admin')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')
@section('page-subtitle', 'Nilai persediaan barang saat ini')

@section('content')
    @include('reports.partials.tabs')

    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs text-slate-500">Total Nilai Stok (harga beli)</p>
            <p class="mt-1 text-xl font-semibold">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <p class="text-xs text-slate-500">Jumlah Produk</p>
            <p class="mt-1 text-xl font-semibold">{{ $products->count() }}</p>
        </div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow">
        <h2 class="mb-4 font-semibold">Daftar Stok Produk</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-2">SKU</th>
                        <th class="py-2">Produk</th>
                        <th class="py-2">Kategori</th>
                        <th class="py-2">Stok</th>
                        <th class="py-2">Harga Beli</th>
                        <th class="py-2">Nilai Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="border-b {{ $product->stock <= 0 ? 'bg-red-50' : '' }}">
                            <td class="py-2">{{ $product->sku }}</td>
                            <td class="py-2">{{ $product->name }}</td>
                            <td class="py-2">{{ $product->category?->name ?? '-' }}</td>
                            <td class="py-2">{{ $product->stock }}</td>
                            <td class="py-2">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td class="py-2 font-medium">Rp {{ number_format($product->stock * $product->purchase_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-slate-500">Belum ada produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
