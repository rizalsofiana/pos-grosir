@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Master Kategori')
@section('page-subtitle', 'Kelola kategori produk')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_0.8fr]" x-data="categoryPage()">
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Daftar Kategori</h2>
            <ul class="space-y-2">
                @foreach ($categories as $category)
                    <li
                        class="flex items-center justify-between rounded border px-3 py-2 {{ !$category->is_active ? 'opacity-50' : '' }}">
                        <span>
                            {{ $category->name }}
                            @if (!$category->is_active)
                                <span
                                    class="ml-2 rounded-full bg-slate-200 px-2 py-0.5 text-xs text-slate-600">Nonaktif</span>
                            @endif
                        </span>
                        <div class="flex gap-2 text-sm">
                            <button type="button" @click="edit(@js(['id' => $category->id, 'name' => $category->name]))"
                                class="text-blue-600 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                </svg>
                            </button>
                            <form method="POST" action="{{ rouwte('categories.toggle', $category) }}"
                                onsubmit="return confirm('{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }} kategori ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="{{ $category->is_active ? 'text-red-600' : 'text-green-600' }} hover:underline">
                                    @if ($category->is_active)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold" x-text="mode === 'edit' ? 'Edit Kategori' : 'Tambah Kategori'"></h2>
            <form method="POST"
                :action="mode === 'edit' ? '{{ url('categories') }}/' + form.id : '{{ route('categories.store') }}'"
                class="space-y-3">
                @csrf
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="text" name="name" x-model="form.name" class="w-full rounded border px-3 py-2"
                    placeholder="Nama kategori" required>
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
        function categoryPage() {
            return {
                mode: 'create',
                form: {
                    id: null,
                    name: ''
                },
                edit(category) {
                    this.mode = 'edit';
                    this.form = {
                        ...category
                    };
                },
                reset() {
                    this.mode = 'create';
                    this.form = {
                        id: null,
                        name: ''
                    };
                },
            };
        }
    </script>
@endsection
