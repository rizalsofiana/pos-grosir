@extends('layouts.admin')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')
@section('page-subtitle', 'Nilai persediaan barang saat ini')

@section('content')
    @include('reports.partials.tabs')

    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-400">Total Nilai Stok</p>
            <p class="mt-1 text-xl font-bold text-slate-800">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-400">Jumlah Produk</p>
            <p class="mt-1 text-xl font-bold text-slate-800">{{ $totalProducts }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-slate-800">Detail Stok Produk</h2>
                <p class="text-xs text-slate-400">{{ $products->total() }} produk terdaftar</p>
            </div>
            <form method="GET" class="flex items-center gap-2 text-sm text-slate-500">
                <label for="per_page">Tampilkan</label>
                <select id="per_page" name="per_page" onchange="this.form.submit()"
                    class="rounded-lg border border-slate-200 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none">
                    @foreach ([10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                        <th class="px-5 py-3">SKU</th>
                        <th class="px-5 py-3">Nama Produk</th>
                        <th class="px-5 py-3">Kategori</th>
                        <th class="px-5 py-3 text-right">Stok</th>
                        <th class="px-5 py-3 text-right">Harga Beli</th>
                        <th class="px-5 py-3 text-right">Nilai Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($products as $product)
                        <tr class="transition-colors hover:bg-slate-50">
                            <td class="px-5 py-3 text-slate-500">{{ $product->sku }}</td>
                            <td class="px-5 py-3 font-medium text-slate-700">{{ $product->name }}</td>
                            <td class="px-5 py-3 text-slate-500">{{ $product->category?->name ?? '-' }}</td>
                            <td
                                class="px-5 py-3 text-right {{ $product->stock <= 0 ? 'font-semibold text-red-600' : 'text-slate-700' }}">
                                {{ $product->stock }}
                            </td>
                            <td class="px-5 py-3 text-right text-slate-500">Rp
                                {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-slate-800">Rp
                                {{ number_format($product->stock * $product->purchase_price, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M3 8h18M3 8l2-5h14l2 5M3 8v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8" />
                                        <path d="M9 12h6" />
                                    </svg>
                                    <p class="text-sm">Belum ada produk.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between border-t border-slate-100 px-5 py-3 text-sm text-slate-500">
            <span>
                Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                dari {{ $products->total() }} data
            </span>
            <div>
                {{ $products->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
