@extends('layouts.admin')

@section('title', 'Diskon')
@section('page-title', 'Manajemen Diskon')
@section('page-subtitle', 'Kelola aturan diskon kuantitas per produk atau kategori')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]" x-data="discountPage()">
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Daftar Aturan Diskon</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-slate-500">
                            <th class="py-2 pr-2">Nama</th>
                            <th class="py-2 pr-2">Target</th>
                            <th class="py-2 pr-2">Min Qty</th>
                            <th class="py-2 pr-2">Diskon</th>
                            <th class="py-2 pr-2">Status</th>
                            <th class="py-2 pr-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rules as $rule)
                            <tr class="border-b {{ !$rule->is_active ? 'opacity-50' : '' }}">
                                <td class="py-2 pr-2">{{ $rule->name }}</td>
                                <td class="py-2 pr-2">
                                    @if ($rule->scope === 'product')
                                        <span class="rounded-full bg-blue-100 px-2 py-0.5 text-xs text-blue-700">Produk</span>
                                        {{ $rule->product?->name }}
                                    @else
                                        <span class="rounded-full bg-purple-100 px-2 py-0.5 text-xs text-purple-700">Kategori</span>
                                        {{ $rule->category?->name }}
                                    @endif
                                </td>
                                <td class="py-2 pr-2">{{ $rule->min_qty }}</td>
                                <td class="py-2 pr-2">
                                    @if ($rule->discount_type === 'percentage')
                                        {{ rtrim(rtrim(number_format($rule->discount_value, 2), '0'), '.') }}%
                                    @else
                                        Rp{{ number_format($rule->discount_value, 0, ',', '.') }}/unit
                                    @endif
                                </td>
                                <td class="py-2 pr-2">
                                    {{ $rule->is_active ? 'Aktif' : 'Nonaktif' }}
                                </td>
                                <td class="py-2 pr-2">
                                    <div class="flex gap-2 text-sm">
                                        <button type="button" @click="edit(@js([
                                            'id' => $rule->id,
                                            'name' => $rule->name,
                                            'scope' => $rule->scope,
                                            'product_id' => $rule->product_id,
                                            'category_id' => $rule->category_id,
                                            'min_qty' => $rule->min_qty,
                                            'discount_type' => $rule->discount_type,
                                            'discount_value' => $rule->discount_value,
                                        ]))" class="text-blue-600 hover:underline">Edit</button>

                                        <form method="POST" action="{{ route('discounts.toggle', $rule) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="{{ $rule->is_active ? 'text-red-600' : 'text-green-600' }} hover:underline">
                                                {{ $rule->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('discounts.destroy', $rule) }}"
                                            onsubmit="return confirm('Hapus aturan diskon ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-500 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-slate-500">Belum ada aturan diskon.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold" x-text="mode === 'edit' ? 'Edit Aturan Diskon' : 'Tambah Aturan Diskon'">
            </h2>
            <form method="POST"
                :action="mode === 'edit' ? '{{ url('discounts') }}/' + form.id : '{{ route('discounts.store') }}'"
                class="space-y-3">
                @csrf
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <input type="text" name="name" x-model="form.name" class="w-full rounded border px-3 py-2"
                    placeholder="Nama aturan" required>

                <div>
                    <label class="mb-1 block text-sm text-slate-600">Target Diskon</label>
                    <select name="scope" x-model="form.scope" class="w-full rounded border px-3 py-2">
                        <option value="product">Produk</option>
                        <option value="category">Kategori</option>
                    </select>
                </div>

                <template x-if="form.scope === 'product'">
                    <div>
                        <label class="mb-1 block text-sm text-slate-600">Produk</label>
                        <select name="product_id" x-model="form.product_id" class="w-full rounded border px-3 py-2">
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <template x-if="form.scope === 'category'">
                    <div>
                        <label class="mb-1 block text-sm text-slate-600">Kategori</label>
                        <select name="category_id" x-model="form.category_id" class="w-full rounded border px-3 py-2">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <div>
                    <label class="mb-1 block text-sm text-slate-600">Minimal Qty</label>
                    <input type="number" name="min_qty" x-model="form.min_qty" min="1"
                        class="w-full rounded border px-3 py-2" required>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-slate-600">Tipe Diskon</label>
                    <select name="discount_type" x-model="form.discount_type" class="w-full rounded border px-3 py-2">
                        <option value="percentage">Persentase (%)</option>
                        <option value="nominal">Nominal (Rp per unit)</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-slate-600">Nilai Diskon</label>
                    <input type="number" step="0.01" name="discount_value" x-model="form.discount_value" min="0"
                        class="w-full rounded border px-3 py-2" required>
                </div>

                <div class="flex gap-2">
                    <button class="w-full rounded bg-blue-600 px-4 py-2 text-white"
                        x-text="mode === 'edit' ? 'Simpan Perubahan' : 'Simpan'"></button>
                    <button type="button" x-show="mode === 'edit'" @click="reset"
                        class="rounded border px-4 py-2 text-slate-600">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function discountPage() {
            return {
                mode: 'create',
                form: {
                    id: null,
                    name: '',
                    scope: 'product',
                    product_id: '',
                    category_id: '',
                    min_qty: 1,
                    discount_type: 'percentage',
                    discount_value: 0,
                },
                edit(rule) {
                    this.mode = 'edit';
                    this.form = {
                        ...rule,
                        product_id: rule.product_id ?? '',
                        category_id: rule.category_id ?? '',
                    };
                },
                reset() {
                    this.mode = 'create';
                    this.form = {
                        id: null,
                        name: '',
                        scope: 'product',
                        product_id: '',
                        category_id: '',
                        min_qty: 1,
                        discount_type: 'percentage',
                        discount_value: 0,
                    };
                },
            };
        }
    </script>
@endsection
