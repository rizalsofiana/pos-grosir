@extends('layouts.admin')

@section('title', 'Customer')
@section('page-title', 'Master Customer')
@section('page-subtitle', 'Kelola data customer')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_0.8fr]" x-data="customerPage()">
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Daftar Customer</h2>
            <ul class="space-y-2">
                @foreach ($customers as $customer)
                    <li
                        class="flex items-center justify-between rounded border px-3 py-2 {{ !$customer->is_active ? 'opacity-50' : '' }}">
                        <div>
                            <div class="font-medium">
                                {{ $customer->name }}
                                @if (!$customer->is_active)
                                    <span
                                        class="ml-2 rounded-full bg-slate-200 px-2 py-0.5 text-xs text-slate-600">Nonaktif</span>
                                @endif
                            </div>
                            <div class="text-sm text-slate-600">{{ $customer->phone }} - {{ $customer->address }}</div>
                        </div>
                        <div class="flex gap-2 text-sm">
                            <button type="button" @click="edit(@js(['id' => $customer->id, 'name' => $customer->name, 'phone' => $customer->phone, 'address' => $customer->address]))"
                                class="text-blue-600 hover:underline">Edit</button>
                            <form method="POST" action="{{ route('customers.toggle', $customer) }}"
                                onsubmit="return confirm('{{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }} customer ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="{{ $customer->is_active ? 'text-red-600' : 'text-green-600' }} hover:underline">
                                    {{ $customer->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold" x-text="mode === 'edit' ? 'Edit Customer' : 'Tambah Customer'"></h2>
            <form method="POST"
                :action="mode === 'edit' ? '{{ url('customers') }}/' + form.id : '{{ route('customers.store') }}'"
                class="space-y-3">
                @csrf
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="text" name="name" x-model="form.name" class="w-full rounded border px-3 py-2"
                    placeholder="Nama customer" required>
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
        function customerPage() {
            return {
                mode: 'create',
                form: {
                    id: null,
                    name: '',
                    phone: '',
                    address: ''
                },
                edit(customer) {
                    this.mode = 'edit';
                    this.form = {
                        ...customer
                    };
                },
                reset() {
                    this.mode = 'create';
                    this.form = {
                        id: null,
                        name: '',
                        phone: '',
                        address: ''
                    };
                },
            };
        }
    </script>
@endsection
