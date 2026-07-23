@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.kasir')

@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Pantau dan sesuaikan stok produk')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 p-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid gap-6 @if (auth()->user()->isAdmin()) lg:grid-cols-[1.3fr_1fr] @endif">
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Daftar Stok Produk</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-2">SKU</th>
                            <th class="py-2">Nama Produk</th>
                            <th class="py-2">Kategori</th>
                            <th class="py-2 text-right">Stok</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-b">
                                <td class="py-2">{{ $product->sku }}</td>
                                <td class="py-2">{{ $product->name }}</td>
                                <td class="py-2">{{ $product->category?->name ?? '-' }}</td>
                                <td class="py-2 text-right font-medium {{ $product->stock <= 0 ? 'text-red-600' : '' }}">
                                    {{ $product->stock }}
                                </td>
                                <td class="py-2 text-right">
                                    <a href="{{ route('stock.history', $product) }}"
                                        class="text-blue-600 hover:underline">Riwayat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-slate-500">Belum ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if (auth()->user()->isAdmin())
            <div class="rounded-xl bg-white p-4 shadow">
                <h2 class="mb-4 font-semibold">Penyesuaian Stok Manual</h2>
                <form method="POST" action="{{ route('stock.adjust') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm">Produk</label>
                        <select name="product_id" class="w-full rounded border px-3 py-2" required>
                            <option value="">Pilih produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }}) -
                                    Stok: {{ $product->stock }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm">Jenis</label>
                            <select name="type" class="w-full rounded border px-3 py-2" required>
                                <option value="in">Tambah (Masuk)</option>
                                <option value="out">Kurangi (Keluar)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm">Jumlah</label>
                            <input type="number" name="quantity" min="1" class="w-full rounded border px-3 py-2"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm">Alasan</label>
                        <select name="reason" class="w-full rounded border px-3 py-2" required>
                            <option value="Koreksi Stok Fisik">Koreksi Stok Fisik</option>
                            <option value="Barang Rusak">Barang Rusak</option>
                            <option value="Barang Hilang">Barang Hilang</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm">Catatan (opsional)</label>
                        <textarea name="note" rows="2" class="w-full rounded border px-3 py-2"></textarea>
                    </div>

                    <button type="submit" class="w-full rounded bg-blue-600 px-4 py-2 text-white">
                        Simpan Penyesuaian
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
