@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Master Produk')
@section('page-subtitle', 'Kelola daftar produk POS')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]" x-data="productPage()">
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Daftar Produk</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2">SKU</th>
                            <th class="py-2">Nama</th>
                            <th class="py-2">Kategori</th>
                            <th class="py-2">Stok</th>
                            <th class="py-2">Harga Jual</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="border-b {{ !$product->is_active ? 'opacity-50' : '' }}">
                                <td class="py-2">{{ $product->sku }}</td>
                                <td class="py-2">{{ $product->name }}</td>
                                <td class="py-2">{{ $product->category?->name ?? '-' }}</td>
                                <td class="py-2">{{ $product->stock }}</td>
                                <td class="py-2">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td class="py-2">
                                    @if ($product->is_active)
                                        <span
                                            class="rounded-full bg-green-100 px-2 py-0.5 text-xs text-green-700">Aktif</span>
                                    @else
                                        <span
                                            class="rounded-full bg-slate-200 px-2 py-0.5 text-xs text-slate-600">Nonaktif</span>
                                    @endif
                                </td>

                                @php
                                    $productData = [
                                        'id' => $product->id,
                                        'category_id' => $product->category_id,
                                        'sku' => $product->sku,
                                        'name' => $product->name,
                                        'purchase_price' => $product->purchase_price,
                                        'selling_price' => $product->selling_price,
                                        'stock' => $product->stock,
                                    ];
                                @endphp

                                <td class="py-2">
                                    <div class="flex gap-2">
                                        <a href="{{ route('products.price-history', $product) }}"
                                            class="text-slate-600 hover:underline" title="Riwayat Harga">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
                                                <path
                                                    d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022zm2.004.45a7 7 0 0 0-.988-.605l.448-.894a8 8 0 0 1 1.152.706zm1.766 1.517a8 8 0 0 0-.837-.74l.552-.833c.328.216.63.480.907.766zm1.29 1.87a7 7 0 0 0-.636-.857l.775-.55c.211.298.398.611.564.937zm.803 2.15a8 8 0 0 0-.421-1.017l.848-.484q.198.398.36.815zM16 8a7 7 0 0 0-.02-.539l.998-.09q.02.32.022.629zm-4.783 6.703q.238-.176.463-.373l.648.752q-.148.129-.31.264zm2.15-2.023a7 7 0 0 0 .53-.752l.925.427q-.238.418-.53.815zm.926-2.008a7 7 0 0 0 .291-.9l.977.244a8 8 0 0 1-.348 1.07zm.312-1.023a7 7 0 0 0 .096-.943h1a8 8 0 0 1-.115 1.135z" />
                                                <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0z" />
                                                <path
                                                    d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5" />
                                            </svg>
                                        </a>
                                        <button type="button" @click="edit({{ json_encode($productData) }})"
                                            class="text-blue-600 hover:underline">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                <path fill-rule="evenodd"
                                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('products.toggle', $product) }}"
                                            onsubmit="return confirm('{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} produk ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="{{ $product->is_active ? 'text-red-600' : 'text-green-600' }} hover:underline">
                                                @if ($product->is_active)
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-check-circle-fill"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold" x-text="mode === 'edit' ? 'Edit Produk' : 'Tambah Produk'"></h2>
            <form method="POST"
                :action="mode === 'edit' ? '{{ url('products') }}/' + form.id : '{{ route('products.store') }}'"
                class="space-y-3">
                @csrf
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <div>
                    <label class="block text-sm">Kategori</label>
                    <select name="category_id" x-model="form.category_id" class="w-full rounded border px-3 py-2">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm">SKU</label>
                    <input type="text" name="sku" x-model="form.sku" class="w-full rounded border px-3 py-2"
                        required>
                </div>
                <div>
                    <label class="block text-sm">Nama</label>
                    <input type="text" name="name" x-model="form.name" class="w-full rounded border px-3 py-2"
                        required>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="block text-sm">Harga Beli</label>
                        <input type="number" name="purchase_price" x-model="form.purchase_price"
                            class="w-full rounded border px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm">Harga Jual</label>
                        <input type="number" name="selling_price" x-model="form.selling_price"
                            class="w-full rounded border px-3 py-2" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm">Stok</label>
                    <input type="number" name="stock" x-model="form.stock" class="w-full rounded border px-3 py-2"
                        required>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="w-full rounded bg-blue-600 px-4 py-2 text-white"
                        x-text="mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Produk'"></button>
                    <button type="button" x-show="mode === 'edit'" @click="reset"
                        class="rounded border px-4 py-2 text-slate-600">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function productPage() {
            return {
                mode: 'create',
                form: {
                    id: null,
                    category_id: '',
                    sku: '',
                    name: '',
                    purchase_price: '',
                    selling_price: '',
                    stock: '',
                },
                edit(product) {
                    this.mode = 'edit';
                    this.form = {
                        ...product
                    };
                },
                reset() {
                    this.mode = 'create';
                    this.form = {
                        id: null,
                        category_id: '',
                        sku: '',
                        name: '',
                        purchase_price: '',
                        selling_price: '',
                        stock: '',
                    };
                },
            };
        }
    </script>
@endsection
