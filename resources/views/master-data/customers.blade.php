@extends('layouts.admin')

@section('title', 'Customer')
@section('page-title', 'Master Customer')
@section('page-subtitle', 'Kelola data customer')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1fr_0.8fr]">
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Daftar Customer</h2>
            <ul class="space-y-2">
                @foreach ($customers as $customer)
                    <li class="rounded border px-3 py-2">
                        <div class="font-medium">{{ $customer->name }}</div>
                        <div class="text-sm text-slate-600">{{ $customer->phone }} - {{ $customer->address }}</div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="rounded-xl bg-white p-4 shadow">
            <h2 class="mb-4 font-semibold">Tambah Customer</h2>
            <form method="POST" action="{{ route('customers.store') }}" class="space-y-3">
                @csrf
                <input type="text" name="name" class="w-full rounded border px-3 py-2" placeholder="Nama customer"
                    required>
                <input type="text" name="phone" class="w-full rounded border px-3 py-2" placeholder="Nomor telepon">
                <textarea name="address" class="w-full rounded border px-3 py-2" placeholder="Alamat"></textarea>
                <button class="w-full rounded bg-blue-600 px-4 py-2 text-white">Simpan</button>
            </form>
        </div>
    </div>
@endsection
