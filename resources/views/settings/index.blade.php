@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Kelola informasi toko')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded bg-green-100 p-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="max-w-xl rounded-xl bg-white p-6 shadow">
        <h2 class="mb-4 font-semibold">Informasi Toko</h2>
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Nama Toko</label>
                <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}"
                    class="w-full rounded border px-3 py-2" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Alamat</label>
                <textarea name="store_address" rows="2" class="w-full rounded border px-3 py-2">{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Telepon</label>
                <input type="text" name="store_phone" value="{{ old('store_phone', $settings['store_phone'] ?? '') }}"
                    class="w-full rounded border px-3 py-2">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Catatan Kaki Struk</label>
                <textarea name="receipt_footer" rows="2" class="w-full rounded border px-3 py-2">{{ old('receipt_footer', $settings['receipt_footer'] ?? '') }}</textarea>
            </div>

            <button class="rounded bg-blue-600 px-4 py-2 text-white">Simpan Perubahan</button>
        </form>
    </div>
@endsection
