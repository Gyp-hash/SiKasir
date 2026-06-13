<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionService
{
    public function checkout(User $cashier, array $cart, float $discount, float $cashPaid): Transaction
    {
        if ($cart === []) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang masih kosong.',
            ]);
        }

        return DB::transaction(function () use ($cashier, $cart, $discount, $cashPaid): Transaction {
            $products = Product::whereIn('id', array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotal = $this->calculateSubtotal($products, $cart);
            $discount = min(max($discount, 0), $subtotal);
            $total = $subtotal - $discount;

            if ($cashPaid < $total) {
                throw ValidationException::withMessages([
                    'cash_paid' => 'Uang tunai belum cukup untuk membayar total transaksi.',
                ]);
            }

            $transaction = Transaction::create([
                'code' => $this->generateCode(),
                'user_id' => $cashier->id,
                'transaction_date' => now(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => Transaction::PAYMENT_CASH,
                'cash_paid' => $cashPaid,
                'change' => $cashPaid - $total,
                'status' => Transaction::STATUS_PAID,
            ]);

            foreach ($cart as $productId => $quantity) {
                $product = $products->get((int) $productId);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'cart' => 'Produk di keranjang tidak ditemukan.',
                    ]);
                }

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok {$product->name} tidak cukup.",
                    ]);
                }

                $price = (float) $product->selling_price;

                $transaction->details()->create([
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'price'      => $price,
                    'subtotal'   => $price * $quantity,
                ]);

                $stockBefore = $product->stock;
                $product->decrement('stock', $quantity);

                StockMovement::create([
                    'product_id'   => $product->id,
                    'type'         => StockMovement::TYPE_OUT,
                    'quantity'     => $quantity,
                    'stock_before' => $stockBefore,
                    'stock_after'  => $stockBefore - $quantity,
                    'notes'        => 'Penjualan '.$transaction->code,
                    'created_by'   => $cashier->id,
                ]);
            }

            return $transaction;
        });
    }

    public function calculateCart(array $cart): array
    {
        if (empty($cart)) {
            return ['items' => collect(), 'subtotal' => 0.0];
        }

        $products = Product::with('category')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = collect();

        foreach ($cart as $productId => $quantity) {
            $product = $products->get((int) $productId);

            if (! $product) {
                continue;
            }

            $price = (float) $product->selling_price;

            $items->push([
                'product'  => $product,
                'quantity' => $quantity,
                'price'    => $price,
                'subtotal' => $price * $quantity,
            ]);
        }

        return [
            'items'    => $items,
            'subtotal' => $items->sum('subtotal'),
        ];
    }

    private function calculateSubtotal(Collection $products, array $cart): float
    {
        $subtotal = 0.0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get((int) $productId);

            if (! $product) {
                continue;
            }

            $subtotal += (float) $product->selling_price * $quantity;
        }

        return $subtotal;
    }

    private function generateCode(): string
    {
        // Use DB-level locking to prevent duplicate codes on concurrent requests
        $prefix = 'TRX-'.now()->format('Ymd').'-';
        $countToday = Transaction::whereDate('transaction_date', today())
            ->lockForUpdate()
            ->count() + 1;

        return $prefix.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);
    }
}
