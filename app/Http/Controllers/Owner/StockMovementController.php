<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    /**
     * Riwayat stok — dengan filter tanggal, produk, dan jenis.
     */
    public function index(Request $request): View
    {
        $productId = $request->input('product_id');
        $type      = $request->input('type');
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');

        $movements = StockMovement::with(['product', 'creator'])
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->when($type,      fn ($q) => $q->where('type', $type))
            ->when($dateFrom,  fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo,    fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $products = Product::orderBy('name')->get();

        return view('owner.stock.index', compact('movements', 'products', 'productId', 'type', 'dateFrom', 'dateTo'));
    }

    /**
     * Form restok produk.
     */
    public function create(): View
    {
        $products = Product::orderBy('name')->get();

        return view('owner.stock.restock', compact('products'));
    }

    /**
     * Proses restok produk.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'notes'      => ['nullable', 'string', 'max:500'],
        ]);

        $product     = Product::findOrFail($validated['product_id']);
        $stockBefore = $product->stock;
        $quantity    = (int) $validated['quantity'];
        $stockAfter  = $stockBefore + $quantity;

        // Tambah stok produk
        $product->increment('stock', $quantity);

        // Catat pergerakan stok
        StockMovement::create([
            'product_id'   => $product->id,
            'type'         => StockMovement::TYPE_IN,
            'quantity'     => $quantity,
            'stock_before' => $stockBefore,
            'stock_after'  => $stockAfter,
            'notes'        => $validated['notes'] ?? null,
            'created_by'   => $request->user()->id,
        ]);

        return redirect()
            ->route('owner.stock.index')
            ->with('success', "Restok {$product->name} sebanyak {$quantity} berhasil. Stok: {$stockBefore} → {$stockAfter}.");
    }
}
