@extends('layouts.admin')

@section('title', 'Audit Log')
@section('page-title', 'Audit Log')
@section('page-subtitle', 'Riwayat perubahan data penting di sistem')

@section('content')
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('audit-logs') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-6">
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Aksi</label>
                <select name="action"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Semua</option>
                    @foreach (['created' => 'Tambah', 'updated' => 'Ubah', 'deleted' => 'Hapus'] as $value => $label)
                        <option value="{{ $value }}" {{ $action == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Pengguna</label>
                <select name="user_id"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Semua</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Tipe Data</label>
                <select name="model_type"
                    class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    <option value="">Semua</option>
                    @foreach ($modelTypes as $type)
                        <option value="{{ $type }}" {{ $modelType == $type ? 'selected' : '' }}>
                            {{ class_basename($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('audit-logs') }}"
                    class="w-full rounded-lg border border-slate-200 px-4 py-2 text-center text-sm font-semibold text-slate-500 transition hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <div>
                <h2 class="font-semibold text-slate-800">Riwayat Aktivitas</h2>
                <p class="text-xs text-slate-400">{{ $auditLogs->total() }} catatan</p>
            </div>
            <form method="GET" action="{{ route('audit-logs') }}" class="flex items-center gap-2 text-sm text-slate-500">
                @foreach (request()->except('per_page', 'page') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <label for="per_page">Tampilkan</label>
                <select id="per_page" name="per_page" onchange="this.form.submit()"
                    class="rounded-lg border border-slate-200 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none">
                    @foreach ([10, 25, 50, 100] as $option)
                        <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50/90">
                    <tr class="text-left text-xs font-medium uppercase tracking-wide text-slate-400">
                        <th class="px-5 py-3">Waktu</th>
                        <th class="px-5 py-3">Pengguna</th>
                        <th class="px-5 py-3">Aksi</th>
                        <th class="px-5 py-3">Data</th>
                        <th class="px-5 py-3">Perubahan</th>
                        <th class="px-5 py-3">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($auditLogs as $log)
                        <tr class="align-top hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-3 text-slate-600">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-5 py-3 text-slate-600">{{ $log->user?->name ?? 'Sistem' }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $badgeClass = match ($log->action) {
                                        'created' => 'bg-emerald-50 text-emerald-600',
                                        'updated' => 'bg-amber-50 text-amber-600',
                                        'deleted' => 'bg-rose-50 text-rose-600',
                                        default => 'bg-slate-100 text-slate-500',
                                    };
                                    $badgeLabel = match ($log->action) {
                                        'created' => 'Tambah',
                                        'updated' => 'Ubah',
                                        'deleted' => 'Hapus',
                                        default => $log->action,
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $badgeClass }}">
                                    {{ $badgeLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            </td>
                            <td class="px-5 py-3 text-slate-500">
                                @if ($log->action === 'updated' && $log->new_values)
                                    <ul class="space-y-1">
                                        @foreach ($log->new_values as $field => $newValue)
                                            <li>
                                                <span class="font-medium text-slate-600">{{ $field }}:</span>
                                                <span class="text-rose-500 line-through">{{ $log->old_values[$field] ?? '-' }}</span>
                                                &rarr;
                                                <span class="text-emerald-600">{{ $newValue }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif ($log->action === 'created')
                                    <span class="text-xs text-slate-400">Data baru dibuat</span>
                                @elseif ($log->action === 'deleted')
                                    <span class="text-xs text-slate-400">Data dihapus</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-400">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M4 4v16h16" />
                                        <path d="M8 16v-4" />
                                        <path d="M12 16V8" />
                                        <path d="M16 16v-7" />
                                    </svg>
                                    <p class="text-sm">Belum ada riwayat aktivitas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between border-t border-slate-100 px-5 py-3 text-sm text-slate-500">
            <span>
                Menampilkan {{ $auditLogs->firstItem() ?? 0 }}-{{ $auditLogs->lastItem() ?? 0 }}
                dari {{ $auditLogs->total() }} data
            </span>
            <div>
                {{ $auditLogs->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
