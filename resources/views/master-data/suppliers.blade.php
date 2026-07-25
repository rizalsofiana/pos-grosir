@extends('layouts.admin')

@section('title', 'Supplier')
@section('page-title', 'Master Supplier')
@section('page-subtitle', 'Kelola data supplier')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_0.8fr]" x-data="supplierPage()">
        <div class="rounded-xl bg-white p-4 shadow">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold">Daftar Supplier</h2>
                <form method="GET" action="{{ route('suppliers') }}" class="flex items-center gap-2 text-sm text-slate-500">
                    <label for="per_page">Tampilkan</label>
                    <select id="per_page" name="per_page" onchange="this.form.submit()"
                        class="rounded border border-slate-200 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none">
                        @foreach ([10, 25, 50, 100] as $option)
                            <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                    <span>data</span>
                </form>
            </div>
            <ul class="space-y-2">

                @foreach ($suppliers as $supplier)
                    <li class="flex items-center justify-between rounded border px-3 py-2 {{ ! $supplier->is_active ? 'opacity-50' : '' }}">
                        <div>
                            <div class="font-medium">
                                {{ $supplier->name }}
                                @if (! $supplier->is_active)
                                    <span class="ml-2 rounded-full bg-slate-200 px-2 py-0.5 text-xs text-slate-600">Nonaktif</span>
                                @endif
                            </div>
                            <div class="text-sm text-slate-600">{{ $supplier->phone }} - {{ $supplier->address }}</div>
                        </div>
                        <div class="flex gap-2 text-sm">
                            <button type="button"
                                @click="edit(@js(['id' => $supplier->id, 'name' => $supplier->name, 'phone' => $supplier->phone, 'address' => $supplier->address]))"
                                class="text-blue-600 hover:underline">Edit</button>
                            <form method="POST" action="{{ route('suppliers.toggle', $supplier) }}"
                                onsubmit="return confirm('{{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }} supplier ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="{{ $supplier->is_active ? 'text-red-600' : 'text-green-600' }} hover:underline">
                                    {{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 flex items-center justify-between text-sm text-slate-500">
                <span>
                    Menampilkan {{ $suppliers->firstItem() ?? 0 }}-{{ $suppliers->lastItem() ?? 0 }}
                    dari {{ $suppliers->total() }} data
                </span>
                <div>
                    {{ $suppliers->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold" x-text="mode === 'edit' ? 'Edit Supplier' : 'Tambah Supplier'"></h2>

            <form method="POST" :action="mode === 'edit' ? '{{ url('suppliers') }}/' + form.id : '{{ route('suppliers.store') }}'"
                class="space-y-3">
                @csrf
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="text" name="name" x-model="form.name" class="w-full rounded border px-3 py-2"
                    placeholder="Nama supplier" required>
                <input type="text" name="phone" x-model="form.phone" class="w-full rounded border px-3 py-2"
                    placeholder="Nomor telepon">
                <textarea name="address" x-model="form.address" class="w-full rounded border px-3 py-2" placeholder="Alamat"></textarea>
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
        function supplierPage() {
            return {
                mode: 'create',
                form: { id: null, name: '', phone: '', address: '' },
                edit(supplier) {
                    this.mode = 'edit';
                    this.form = { ...supplier };
                },
                reset() {
                    this.mode = 'create';
                    this.form = { id: null, name: '', phone: '', address: '' };
                },
            };
        }
    </script>
@endsection
