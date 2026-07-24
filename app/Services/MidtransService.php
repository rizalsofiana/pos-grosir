<?php

namespace App\Services;

use App\Models\Sale;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createSnapToken(Sale $sale): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $sale->midtrans_order_id,
                'gross_amount' => (int) $sale->grand_amount,
            ],
            'customer_details' => [
                'first_name' => $sale->customer?->name ?? 'Umum',
                'phone' => $sale->customer?->phone,
            ],
            'item_details' => $sale->saleDetails->map(fn($detail) => [
                'id' => $detail->product_id,
                'price' => (int) $detail->price,
                'quantity' => $detail->quantity,
                'name' => \Illuminate\Support\Str::limit($detail->product?->name ?? 'Produk', 50, ''),
            ])->toArray(),
        ];

        return Snap::getSnapToken($params);
    }

    public function handleNotification(): Notification
    {
        return new Notification();
    }

    public function mapTransactionStatusToPaymentStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        return match ($transactionStatus) {
            'capture' => $fraudStatus === 'accept' ? 'paid' : 'pending',
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'cancel', 'expire' => 'failed',
            default => 'pending',
        };
    }
}
