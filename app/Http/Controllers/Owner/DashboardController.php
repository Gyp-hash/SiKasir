<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // ── Statistik Penjualan ──────────────────────────────────────────────
        $salesToday = Transaction::whereDate('transaction_date', today())
            ->where('status', Transaction::STATUS_PAID)
            ->sum('total');

        $salesThisMonth = Transaction::whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->where('status', Transaction::STATUS_PAID)
            ->sum('total');

        $totalTransactions = Transaction::where('status', Transaction::STATUS_PAID)->count();

        // ── Statistik Pengeluaran ────────────────────────────────────────────
        $expensesThisMonth = Expense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        $totalExpenses = Expense::sum('amount');

        // ── Laba Bersih Estimasi ─────────────────────────────────────────────
        $netProfitThisMonth = (float) $salesThisMonth - (float) $expensesThisMonth;

        // ── Produk ───────────────────────────────────────────────────────────
        $totalProducts = Product::count();

        $lowStockProducts = Product::where('status', Product::STATUS_ACTIVE)
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->get();

        // ── Top 5 Produk Terlaris ────────────────────────────────────────────
        $topProducts = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->with('product:id,name,selling_price')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // ── Aktivitas Terbaru (5 transaksi terakhir) ─────────────────────────
        $recentTransactions = Transaction::with('user:id,name')
            ->where('status', Transaction::STATUS_PAID)
            ->latest('transaction_date')
            ->limit(5)
            ->get();

        // ── Grafik 7 Hari Terakhir ───────────────────────────────────────────
        $last7Days = collect(range(6, 0))->map(fn ($d) => now()->subDays($d)->toDateString());

        $salesChart = Transaction::select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(total) as total')
            )
            ->where('status', Transaction::STATUS_PAID)
            ->whereBetween('transaction_date', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('date')
            ->pluck('total', 'date');

        $expensesChart = Expense::select(
                DB::raw('DATE(expense_date) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('expense_date', [now()->subDays(6)->toDateString(), now()->toDateString()])
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels      = $last7Days->map(fn ($d) => date('d M', strtotime($d)))->values();
        $chartSales       = $last7Days->map(fn ($d) => (float) ($salesChart[$d] ?? 0))->values();
        $chartExpenses    = $last7Days->map(fn ($d) => (float) ($expensesChart[$d] ?? 0))->values();

        return view('owner.dashboard', compact(
            'salesToday',
            'salesThisMonth',
            'expensesThisMonth',
            'netProfitThisMonth',
            'totalProducts',
            'totalTransactions',
            'totalExpenses',
            'lowStockProducts',
            'topProducts',
            'recentTransactions',
            'chartLabels',
            'chartSales',
            'chartExpenses',
        ));
    }
}
