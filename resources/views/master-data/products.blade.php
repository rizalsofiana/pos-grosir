@extends('layouts.admin')

@section('title', 'Produk')
@section('page-title', 'Master Produk')
@section('page-subtitle', 'Kelola daftar produk POS')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr class="border-b">
                                <td class="py-2">{{ $product->sku }}</td>
                                <td class="py-2">{{ $product->name }}</td>
                                <td class="py-2">{{ $product->category?->name ?? '-' }}</td>
                                <td class="py-2">{{ $product->stock }}</td>
                                <td class="py-2">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Tambah Produk</h2>
            <form method="POST" action="{{ route('products.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm">Kategori</label>
                    <select name="category_id" class="w-full rounded border px-3 py-2">
                        @foreach (App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm">SKU</label>
                    <input type="text" name="sku" class="w-full rounded border px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm">Nama</label>
                    <input type="text" name="name" class="w-full rounded border px-3 py-2" required>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="block text-sm">Harga Beli</label>
                        <input type="number" name="purchase_price" class="w-full rounded border px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block text-sm">Harga Jual</label>
                        <input type="number" name="selling_price" class="w-full rounded border px-3 py-2" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm">Stok</label>
                    <input type="number" name="stock" class="w-full rounded border px-3 py-2" required>
                </div>
                <button class="w-full rounded bg-blue-600 px-4 py-2 text-white">Simpan Produk</button>
            </form>
        </div>
    </div>
@endsection
