@extends('layouts.admin')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')
@section('page-subtitle', 'Nilai persediaan barang saat ini')

@section('content')
    @include('reports.partials.tabs')

    <div class="mb-6 grid gap-4 grid-cols-2">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-400">Total Nilai Stok</p>
            <p class="mt-1 text-sm sm:text-xl font-bold text-slate-800 wrap-break-word">Rp
                {{ number_format($totalStockValue, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-medium text-slate-400">Jumlah Produk</p>
            <p class="mt-1 text-sm sm:text-xl font-bold text-slate-800 wrap-break-word">{{ $totalProducts }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-800">Detail Stok Produk</h2>
                <p class="text-xs text-slate-400">{{ $products->total() }} produk terdaftar</p>
            </div>
            <form method="GET" class="flex items-center justify-between sm:justify-end gap-3 text-sm text-slate-500">
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

        {{-- Desktop Table View --}}
        <div class="hidden md:block overflow-x-auto">
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

        {{-- Mobile Card List View --}}
        <div class="block md:hidden divide-y divide-slate-100">
            @forelse ($products as $product)
                <div class="p-4 transition-colors hover:bg-slate-50">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div>
                            <span class="text-xs font-mono text-slate-400">{{ $product->sku }}</span>
                            <p class="text-sm font-semibold text-slate-800">{{ $product->name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-400">Nilai Stok</span>
                            <p class="text-sm font-bold text-slate-800">
                                Rp{{ number_format($product->stock * $product->purchase_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-y-1 text-xs text-slate-500">
                        <span>Kategori</span>
                        <span class="text-right text-slate-700 font-medium">{{ $product->category?->name ?? '-' }}</span>
                        <span>Stok</span>
                        <span
                            class="text-right font-semibold {{ $product->stock <= 0 ? 'text-red-600' : 'text-slate-700' }}">{{ $product->stock }}</span>
                        <span>Harga Beli</span>
                        <span
                            class="text-right text-slate-700 font-medium">Rp{{ number_format($product->purchase_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center gap-2 px-5 py-14 text-center text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 8h18M3 8l2-5h14l2 5M3 8v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8" />
                        <path d="M9 12h6" />
                    </svg>
                    <p class="text-sm">Belum ada produk.</p>
                </div>
            @endforelse
        </div>

        <div
            class="flex flex-col items-center gap-3 border-t border-slate-100 px-5 py-3 text-sm text-slate-500 sm:flex-row sm:justify-between mt-auto">
            <span class="text-center sm:text-left">
                Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                dari {{ $products->total() }} data
            </span>
            <div>
                {{ $products->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
