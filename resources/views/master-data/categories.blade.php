@extends('layouts.admin')

@section('title', 'Kategori')
@section('page-title', 'Master Kategori')
@section('page-subtitle', 'Kelola kategori produk')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_0.8fr]">
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Daftar Kategori</h2>
            <ul class="space-y-2">
                @foreach ($categories as $category)
                    <li class="rounded border px-3 py-2">{{ $category->name }}</li>
                @endforeach
            </ul>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Tambah Kategori</h2>
            <form method="POST" action="{{ route('categories.store') }}" class="space-y-3">
                @csrf
                <input type="text" name="name" class="w-full rounded border px-3 py-2" placeholder="Nama kategori"
                    required>
                <button class="w-full rounded bg-blue-600 px-4 py-2 text-white">Simpan</button>
            </form>
        </div>
    </div>
@endsection
