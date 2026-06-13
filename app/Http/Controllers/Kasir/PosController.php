<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $cart = $request->session()->get('cart', []);

        $products = Product::with('category')
            ->where('status', Product::STATUS_ACTIVE)
            ->where('stock', '>', 0)
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($searchQuery) use ($search): void {
                    $searchQuery->where('name', 'like', '%'.$search.'%')
                        ->orWhereHas('category', function ($categoryQuery) use ($search): void {
                            $categoryQuery->where('name', 'like', '%'.$search.'%');
                        });
                });
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $cartSummary = $this->transactionService->calculateCart($cart);

        return view('kasir.pos.index', [
            'products' => $products,
            'search' => $search,
            'cartItems' => $cartSummary['items'],
            'cartSubtotal' => $cartSummary['subtotal'],
        ]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($product->status !== Product::STATUS_ACTIVE || $product->stock <= 0) {
            return back()->with('error', 'Produk tidak tersedia untuk dijual.');
        }

        $cart = $request->session()->get('cart', []);
        $currentQuantity = $cart[$product->id] ?? 0;
        $newQuantity = $currentQuantity + (int) $validated['quantity'];

        if ($newQuantity > $product->stock) {
            return back()->with('error', "Stok {$product->name} hanya tersedia {$product->stock}.");
        }

        $cart[$product->id] = $newQuantity;
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function updateCart(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($product->status !== Product::STATUS_ACTIVE) {
            // Remove the inactive product from cart automatically
            $cart = $request->session()->get('cart', []);
            unset($cart[$product->id]);
            $request->session()->put('cart', $cart);

            return back()->with('error', "Produk \"{$product->name}\" sudah tidak aktif dan dihapus dari keranjang.");
        }

        $quantity = (int) $validated['quantity'];

        if ($quantity > $product->stock) {
            return back()->with('error', "Stok {$product->name} hanya tersedia {$product->stock}.");
        }

        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = $quantity;
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Quantity keranjang berhasil diperbarui.');
    }

    public function removeFromCart(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);

        $request->session()->put('cart', $cart);

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'discount' => ['nullable', 'numeric', 'min:0'],
            'cash_paid' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $transaction = $this->transactionService->checkout(
                $request->user(),
                $request->session()->get('cart', []),
                (float) ($validated['discount'] ?? 0),
                (float) $validated['cash_paid'],
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->with('error', collect($e->errors())->flatten()->first());
        }

        $request->session()->forget('cart');

        return redirect()
            ->route('kasir.transactions.show', $transaction)
            ->with('success', 'Transaksi berhasil disimpan.');
    }
}
