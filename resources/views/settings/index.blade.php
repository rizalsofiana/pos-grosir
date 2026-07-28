@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan')
@section('page-subtitle', 'Kelola informasi toko')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700 shadow-sm flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"/>
                <path d="M8.5 12.5l2.5 2.5 4.5-5"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-2xl rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
        <h2 class="mb-4 font-semibold text-slate-800">Informasi Toko</h2>
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Nama Toko</label>
                <input type="text" name="store_name" value="{{ old('store_name', $settings['store_name'] ?? '') }}"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" required>
            </div>

            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Alamat</label>
                <textarea name="store_address" rows="2" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('store_address', $settings['store_address'] ?? '') }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Telepon</label>
                <input type="text" name="store_phone" value="{{ old('store_phone', $settings['store_phone'] ?? '') }}"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Catatan Kaki Struk</label>
                <textarea name="receipt_footer" rows="2" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('receipt_footer', $settings['receipt_footer'] ?? '') }}</textarea>
            </div>

            <button class="w-full sm:w-auto rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Simpan Perubahan</button>
        </form>
    </div>
@endsection
