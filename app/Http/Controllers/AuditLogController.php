<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $action = $request->input('action');
        $userId = $request->input('user_id');
        $modelType = $request->input('model_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = AuditLog::with('user')
            ->when($action, fn ($q) => $q->where('action', $action))
            ->when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when($modelType, fn ($q) => $q->where('model_type', $modelType))
            ->when($startDate, fn ($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('created_at', '<=', $endDate));

        $auditLogs = $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();

        $modelTypes = AuditLog::query()
            ->select('model_type')
            ->distinct()
            ->orderBy('model_type')
            ->pluck('model_type');

        return view('audit-logs.index', [
            'auditLogs' => $auditLogs,
            'users' => User::orderBy('name')->get(),
            'modelTypes' => $modelTypes,
            'perPage' => $perPage,
            'action' => $action,
            'userId' => $userId,
            'modelType' => $modelType,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
