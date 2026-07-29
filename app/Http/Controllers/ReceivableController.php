<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReceivableController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'outstanding');
        $search = $request->input('search');

        $query = Sale::with(['customer', 'user'])
            ->whereIn('payment_status', ['unpaid', 'partial']);

        if ($status === 'lunas') {
            $query = Sale::with(['customer', 'user'])->where('payment_status', 'paid')
                ->where(function ($q) {
                    $q->whereNotNull('debtor_name')->orWhereNotNull('due_date');
                });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('debtor_name', 'like', "%{$search}%");
            });
        }

        $sales = $query->orderBy('due_date')->orderByDesc('sale_date')->paginate(15)->withQueryString();

        return view('receivables.index', [
            'sales' => $sales,
            'status' => $status,
            'search' => $search ?? '',
        ]);
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'saleDetails.product', 'payments.user']);

        return view('receivables.show', ['sale' => $sale]);
    }

    public function pay(Request $request, Sale $sale)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        if ($data['amount'] > $sale->outstanding) {
            throw ValidationException::withMessages([
                'amount' => 'Jumlah bayar melebihi sisa piutang.',
            ]);
        }

        DB::transaction(function () use ($sale, $data) {
            SalePayment::create([
                'sale_id' => $sale->id,
                'user_id' => Auth::id(),
                'amount' => $data['amount'],
                'paid_at' => now(),
                'note' => $data['note'] ?? null,
            ]);

            $sale->refresh();

            $sale->update([
                'payment_status' => $sale->outstanding <= 0 ? 'paid' : 'partial',
            ]);
        });

        return redirect()->route('receivables.show', $sale)->with('success', 'Pembayaran piutang berhasil dicatat.');
    }
}
