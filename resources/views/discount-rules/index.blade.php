@extends('layouts.admin')

@section('title', 'Diskon')
@section('page-title', 'Manajemen Diskon')
@section('page-subtitle', 'Kelola aturan diskon kuantitas per produk atau kategori')

@section('content')
    <div x-data="{ showSuccess: {{ session('success') ? 'true' : 'false' }} }">
        <div x-show="showSuccess" x-cloak x-transition
            class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9" />
                <path d="M8.5 12.5l2.5 2.5 4.5-5" />
            </svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button type="button" @click="showSuccess = false" class="shrink-0 text-emerald-500 hover:text-emerald-700">
                &times;
            </button>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.3fr_1fr]" x-data="discountPage()">
        {{-- Daftar Aturan Diskon --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm flex flex-col">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-slate-800">Daftar Aturan Diskon</h2>
                    <p class="text-xs text-slate-400">{{ $rules->total() }} aturan terdaftar</p>
                </div>
                <div class="flex items-center justify-between sm:justify-end gap-3">
                    <form method="GET" action="{{ route('discounts') }}"
                        class="flex items-center gap-2 text-sm text-slate-500">
                        <label for="per_page">Tampilkan</label>
                        <select id="per_page" name="per_page" onchange="this.form.submit()"
                            class="rounded-lg border border-slate-200 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none">
                            @foreach ([10, 25, 50, 100] as $option)
                                <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                    {{ $option }}</option>
                            @endforeach
                        </select>
                    </form>
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M20.59 13.41L11 3.83A2 2 0 0 0 9.59 3.17H5a2 2 0 0 0-2 2v4.59a2 2 0 0 0 .59 1.41l9.58 9.58a2 2 0 0 0 2.83 0l4.59-4.59a2 2 0 0 0 0-2.83z" />
                            <path d="M7 7h.01" />
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Desktop / tablet view --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Target</th>
                            <th class="px-5 py-3 text-right">Min Qty</th>
                            <th class="px-5 py-3 text-right">Diskon</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rules as $rule)
                            <tr class="transition-colors hover:bg-slate-50 {{ !$rule->is_active ? 'opacity-50' : '' }}">
                                <td class="px-5 py-3 font-medium text-slate-700">{{ $rule->name }}</td>
                                <td class="px-5 py-3">
                                    @if ($rule->scope === 'product')
                                        <span
                                            class="inline-flex rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-600">Produk</span>
                                        <span class="text-slate-500 text-xs block mt-0.5">{{ $rule->product?->name }}</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-purple-50 px-2 py-0.5 text-xs font-medium text-purple-600">Kategori</span>
                                        <span class="text-slate-500 text-xs block mt-0.5">{{ $rule->category?->name }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right text-slate-600">{{ $rule->min_qty }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-slate-800">
                                    @if ($rule->discount_type === 'percentage')
                                        {{ rtrim(rtrim(number_format($rule->discount_value, 2), '0'), '.') }}%
                                    @else
                                        Rp{{ number_format($rule->discount_value, 0, ',', '.') }}/unit
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    @if ($rule->is_active)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-600">Aktif</span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex justify-end gap-3 text-sm">
                                        <button type="button" @click="edit(@js([
    'id' => $rule->id,
    'name' => $rule->name,
    'scope' => $rule->scope,
    'product_id' => $rule->product_id,
    'category_id' => $rule->category_id,
    'min_qty' => $rule->min_qty,
    'discount_type' => $rule->discount_type,
    'discount_value' => $rule->discount_value,
]))"
                                            class="font-medium text-blue-600 hover:underline">Edit</button>

                                        <form method="POST" action="{{ route('discounts.toggle', $rule) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="font-medium {{ $rule->is_active ? 'text-amber-600' : 'text-emerald-600' }} hover:underline">
                                                {{ $rule->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('discounts.destroy', $rule) }}"
                                            onsubmit="return confirm('Hapus aturan diskon ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="font-medium text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path
                                                d="M20.59 13.41L11 3.83A2 2 0 0 0 9.59 3.17H5a2 2 0 0 0-2 2v4.59a2 2 0 0 0 .59 1.41l9.58 9.58a2 2 0 0 0 2.83 0l4.59-4.59a2 2 0 0 0 0-2.83z" />
                                            <path d="M7 7h.01" />
                                        </svg>
                                        <p class="text-sm">Belum ada aturan diskon.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile card list view --}}
            <div class="block md:hidden divide-y divide-slate-100">
                @forelse ($rules as $rule)
                    <div class="p-4 transition-colors hover:bg-slate-50 {{ !$rule->is_active ? 'opacity-60' : '' }}">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">{{ $rule->name }}</h3>
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @if ($rule->scope === 'product')
                                        <span class="inline-flex rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-medium text-blue-600">Produk</span>
                                        <span class="text-slate-500 text-xs mt-0.5">{{ $rule->product?->name }}</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-purple-50 px-2 py-0.5 text-[10px] font-medium text-purple-600">Kategori</span>
                                        <span class="text-slate-500 text-xs mt-0.5">{{ $rule->category?->name }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-sm font-bold text-slate-800 shrink-0">
                                @if ($rule->discount_type === 'percentage')
                                    {{ rtrim(rtrim(number_format($rule->discount_value, 2), '0'), '.') }}%
                                @else
                                    Rp{{ number_format($rule->discount_value, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-y-1 text-xs text-slate-500 mb-3">
                            <span>Minimal Qty</span>
                            <span class="text-right text-slate-700 font-semibold">{{ $rule->min_qty }} Unit</span>
                            <span>Status</span>
                            <span class="text-right">
                                @if ($rule->is_active)
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-medium text-emerald-600">Aktif</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500">Nonaktif</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-end gap-3 text-xs border-t border-slate-50 pt-2">
                            <button type="button" @click="edit(@js([
        'id' => $rule->id,
        'name' => $rule->name,
        'scope' => $rule->scope,
        'product_id' => $rule->product_id,
        'category_id' => $rule->category_id,
        'min_qty' => $rule->min_qty,
        'discount_type' => $rule->discount_type,
        'discount_value' => $rule->discount_value,
    ]))"
                                class="font-medium text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:underline">Edit</button>

                            <form method="POST" action="{{ route('discounts.toggle', $rule) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="font-medium {{ $rule->is_active ? 'text-amber-600 bg-amber-50' : 'text-emerald-600 bg-emerald-50' }} px-3 py-1.5 rounded-lg hover:underline">
                                    {{ $rule->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('discounts.destroy', $rule) }}"
                                onsubmit="return confirm('Hapus aturan diskon ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="font-medium text-red-600 bg-red-50 px-3 py-1.5 rounded-lg hover:underline">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center gap-2 px-5 py-14 text-center text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M20.59 13.41L11 3.83A2 2 0 0 0 9.59 3.17H5a2 2 0 0 0-2 2v4.59a2 2 0 0 0 .59 1.41l9.58 9.58a2 2 0 0 0 2.83 0l4.59-4.59a2 2 0 0 0 0-2.83z" />
                            <path d="M7 7h.01" />
                        </svg>
                        <p class="text-sm">Belum ada aturan diskon.</p>
                    </div>
                @endforelse
            </div>

            <div class="flex flex-col items-center gap-3 border-t border-slate-100 px-5 py-3 text-sm text-slate-500 sm:flex-row sm:justify-between mt-auto">
                <span class="text-center sm:text-left">
                    Menampilkan {{ $rules->firstItem() ?? 0 }}-{{ $rules->lastItem() ?? 0 }}
                    dari {{ $rules->total() }} data
                </span>
                <div>
                    {{ $rules->onEachSide(1)->links() }}
                </div>
            </div>
        </div>

        {{-- Form Aturan Diskon --}}
        <div class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="font-semibold text-slate-800"
                    x-text="mode === 'edit' ? 'Edit Aturan Diskon' : 'Tambah Aturan Diskon'"></h2>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20V10M12 10l-4 4M12 10l4 4M4 4h16" />
                    </svg>
                </span>
            </div>

            <form method="POST"
                :action="mode === 'edit' ? '{{ url('discounts') }}/' + form.id : '{{ route('discounts.store') }}'"
                class="space-y-4 px-5 py-5">
                @csrf
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Nama Aturan</label>
                    <input type="text" name="name" x-model="form.name"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Nama aturan" required>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Target Diskon</label>
                    <select name="scope" x-model="form.scope"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="product">Produk</option>
                        <option value="category">Kategori</option>
                    </select>
                </div>

                <template x-if="form.scope === 'product'">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Produk</label>
                        <select name="product_id" x-model="form.product_id"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">Pilih Produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <template x-if="form.scope === 'category'">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Kategori</label>
                        <select name="category_id" x-model="form.category_id"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </template>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Minimal Qty</label>
                        <input type="number" name="min_qty" x-model="form.min_qty" min="1"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                            required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Tipe Diskon</label>
                        <select name="discount_type" x-model="form.discount_type"
                            class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="percentage">Persentase (%)</option>
                            <option value="nominal">Nominal (Rp)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium text-slate-500">Nilai Diskon</label>
                    <input type="number" step="0.01" name="discount_value" x-model="form.discount_value"
                        min="0"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        required>
                </div>

                <div class="flex gap-2 pt-1">
                    <button
                        class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        x-text="mode === 'edit' ? 'Simpan Perubahan' : 'Simpan'"></button>
                    <button type="button" x-show="mode === 'edit'" @click="reset"
                        class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</button>
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
