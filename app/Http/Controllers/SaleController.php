<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DiscountRule;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\StockMovement;
use App\Services\MidtransService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index()
    {
        return view('sales.index', [
            'sales' => Sale::with(['customer', 'user'])
                ->whereDate('sale_date', now()->toDateString())
                ->latest('sale_date')
                ->get(),
            'customers' => Customer::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'discountRules' => DiscountRule::active()->get(),
            'midtransClientKey' => config('midtrans.client_key'),
            'midtransIsProduction' => config('midtrans.is_production'),
        ]);
    }

    public function history(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $salesQuery = Sale::with(['customer', 'user'])
            ->whereDate('sale_date', '>=', $startDate)
            ->whereDate('sale_date', '<=', $endDate);

        if ($search = $request->input('search')) {
            $salesQuery->where('invoice_number', 'like', "%{$search}%");
        }

        $sales = $salesQuery->orderByDesc('sale_date')->paginate($perPage)->withQueryString();

        return view('sales.history', [
            'sales' => $sales,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'perPage' => $perPage,
            'search' => $request->input('search', ''),
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'sale_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,cashless'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        $sale = DB::transaction(function () use ($data) {
            $products = Product::whereIn('id', collect($data['items'])->pluck('product_id'))
                ->get()
                ->keyBy('id');

            foreach ($data['items'] as $item) {
                $product = $products[$item['product_id']];
                if ($product->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Stok produk {$product->name} tidak mencukupi.",
                    ]);
                }
            }

            $productIds = $products->keys();
            $categoryIds = $products->pluck('category_id')->filter()->unique();

            $rules = DiscountRule::active()
                ->where(function ($query) use ($productIds, $categoryIds) {
                    $query->whereIn('product_id', $productIds)
                        ->orWhereIn('category_id', $categoryIds);
                })
                ->get();

            $lineDiscounts = collect($data['items'])->map(function ($item) use ($products, $rules) {
                $product = $products[$item['product_id']];

                $bestDiscount = $rules
                    ->filter(fn($rule) => ($rule->scope === 'product' && $rule->product_id === $product->id)
                        || ($rule->scope === 'category' && $rule->category_id === $product->category_id))
                    ->map(fn($rule) => $rule->calculateDiscount($item['quantity'], $item['price']))
                    ->max() ?? 0;

                return $bestDiscount;
            });

            $subTotal = collect($data['items'])->sum(fn($item) => $item['quantity'] * $item['price']);
            $discount = $lineDiscounts->sum();
            $grandAmount = $subTotal - $discount;

            $invoiceNumber = $this->generateInvoiceNumber();
            $isCashless = $data['payment_method'] === 'cashless';

            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $data['customer_id'],
                'user_id' => Auth::id(),
                'sale_date' => $data['sale_date'],
                'sub_total' => $subTotal,
                'discount' => $discount,
                'grand_amount' => $grandAmount,
                'payment_method' => $data['payment_method'],
                'payment_status' => $isCashless ? 'pending' : 'paid',
                'midtrans_order_id' => $isCashless ? $invoiceNumber . '-' . time() : null,
            ]);

            foreach ($data['items'] as $index => $item) {
                $lineDiscount = $lineDiscounts[$index];
                $lineSubTotal = ($item['quantity'] * $item['price']) - $lineDiscount;

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $lineDiscount,
                    'sub_total' => $lineSubTotal,
                ]);

                $product = Product::lockForUpdate()->find($item['product_id']);
                $stockBefore = $product->stock;
                $product->decrement('stock', $item['quantity']);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockBefore - $item['quantity'],
                    'reference_type' => 'sale',
                    'reference_id' => $sale->id,
                    'user_id' => Auth::id(),
                ]);
            }

            session(['last_sale_id' => $sale->id]);

            return $sale;
        });

        if ($sale->payment_method === 'cashless') {
            $sale->load(['customer', 'saleDetails.product']);
            $snapToken = (new MidtransService())->createSnapToken($sale);
            $sale->update(['snap_token' => $snapToken]);

            if ($request->wantsJson()) {
                return response()->json(['snap_token' => $snapToken, 'sale_id' => $sale->id]);
            }

            return redirect()->route('sales')->with('success', 'Transaksi disimpan, silakan selesaikan pembayaran.');
        }

        if ($request->wantsJson()) {
            return response()->json(['redirect' => route('sales')]);
        }

        return redirect()->route('sales')->with('success', 'Transaksi penjualan berhasil disimpan.');
    }


    public function midtransNotification(Request $request)
    {
        $notification = (new MidtransService())->handleNotification();

        $sale = Sale::where('midtrans_order_id', $notification->order_id)->first();

        if (!$sale) {
            Log::warning('Midtrans notification: sale not found', ['order_id' => $notification->order_id]);
            return response()->json(['message' => 'Sale not found'], 404);
        }

        $paymentStatus = (new MidtransService())->mapTransactionStatusToPaymentStatus(
            $notification->transaction_status,
            $notification->fraud_status ?? null
        );

        $sale->update(['payment_status' => $paymentStatus]);

        return response()->json(['message' => 'OK']);
    }

    public function checkStatus(Sale $sale)
    {
        return response()->json(['payment_status' => $sale->payment_status]);
    }

    public function receipt(Sale $sale)
    {
        $sale->load(['customer', 'user', 'saleDetails.product']);

        return view('sales.receipt', ['sale' => $sale]);
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $lastNumber = Sale::where('invoice_number', 'like', $prefix . '%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $sequence = $lastNumber ? ((int) substr($lastNumber, -4)) + 1 : 1;

        return $prefix . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
