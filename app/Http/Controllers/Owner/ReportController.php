<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    // ─── Laporan Penjualan ───────────────────────────────────────────────────

    public function sales(Request $request): View
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to',   now()->toDateString());

        $transactions = Transaction::with('user:id,name')
            ->where('status', Transaction::STATUS_PAID)
            ->whereDate('transaction_date', '>=', $dateFrom)
            ->whereDate('transaction_date', '<=', $dateTo)
            ->latest('transaction_date')
            ->get();

        $summary = [
            'count' => $transactions->count(),
            'total' => $transactions->sum('total'),
        ];

        return view('owner.reports.sales', compact('transactions', 'summary', 'dateFrom', 'dateTo'));
    }

    // ─── Laporan Pengeluaran ─────────────────────────────────────────────────

    public function expenses(Request $request): View
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to',   now()->toDateString());
        $category = $request->input('category');

        $expenses = Expense::with('creator:id,name')
            ->when($category, fn ($q) => $q->where('category', $category))
            ->whereDate('expense_date', '>=', $dateFrom)
            ->whereDate('expense_date', '<=', $dateTo)
            ->latest('expense_date')
            ->get();

        $summary = [
            'total' => $expenses->sum('amount'),
        ];

        $categories = Expense::CATEGORIES;

        return view('owner.reports.expenses', compact('expenses', 'summary', 'dateFrom', 'dateTo', 'category', 'categories'));
    }

    // ─── Laporan Stok ────────────────────────────────────────────────────────

    public function stocks(Request $request): View
    {
        $dateFrom  = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo    = $request->input('date_to',   now()->toDateString());
        $productId = $request->input('product_id');
        $type      = $request->input('type');

        $movements = StockMovement::with(['product:id,name', 'creator:id,name'])
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->when($type,      fn ($q) => $q->where('type', $type))
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->get();

        $products = Product::orderBy('name')->get(['id', 'name']);

        $summary = [
            'total_in'          => $movements->where('type', 'IN')->sum('quantity'),
            'total_out'         => $movements->where('type', 'OUT')->sum('quantity'),
            'total_adjustment'  => $movements->where('type', 'ADJUSTMENT')->count(),
        ];

        return view('owner.reports.stocks', compact('movements', 'summary', 'products', 'dateFrom', 'dateTo', 'productId', 'type'));
    }

    // ─── PDF Exports ─────────────────────────────────────────────────────────

    public function exportSalesPdf(Request $request): \Illuminate\Http\Response
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to',   now()->toDateString());

        $transactions = Transaction::with('user:id,name')
            ->where('status', Transaction::STATUS_PAID)
            ->whereDate('transaction_date', '>=', $dateFrom)
            ->whereDate('transaction_date', '<=', $dateTo)
            ->latest('transaction_date')
            ->get();

        $summary = ['count' => $transactions->count(), 'total' => $transactions->sum('total')];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'owner.reports.pdf.sales',
            compact('transactions', 'summary', 'dateFrom', 'dateTo')
        );

        return $pdf->download("laporan-penjualan-{$dateFrom}-{$dateTo}.pdf");
    }

    public function exportExpensesPdf(Request $request): \Illuminate\Http\Response
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to',   now()->toDateString());
        $category = $request->input('category');

        $expenses = Expense::with('creator:id,name')
            ->when($category, fn ($q) => $q->where('category', $category))
            ->whereDate('expense_date', '>=', $dateFrom)
            ->whereDate('expense_date', '<=', $dateTo)
            ->latest('expense_date')
            ->get();

        $summary = ['total' => $expenses->sum('amount')];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'owner.reports.pdf.expenses',
            compact('expenses', 'summary', 'dateFrom', 'dateTo', 'category')
        );

        return $pdf->download("laporan-pengeluaran-{$dateFrom}-{$dateTo}.pdf");
    }

    public function exportStocksPdf(Request $request): \Illuminate\Http\Response
    {
        $dateFrom  = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo    = $request->input('date_to',   now()->toDateString());
        $productId = $request->input('product_id');
        $type      = $request->input('type');

        $movements = StockMovement::with(['product:id,name', 'creator:id,name'])
            ->when($productId, fn ($q) => $q->where('product_id', $productId))
            ->when($type,      fn ($q) => $q->where('type', $type))
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->latest()
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'owner.reports.pdf.stocks',
            compact('movements', 'dateFrom', 'dateTo', 'type')
        );

        return $pdf->download("laporan-stok-{$dateFrom}-{$dateTo}.pdf");
    }

    // ─── Excel Exports ───────────────────────────────────────────────────────

    public function exportSalesExcel(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to',   now()->toDateString());

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SalesExport($dateFrom, $dateTo),
            "laporan-penjualan-{$dateFrom}-{$dateTo}.xlsx"
        );
    }

    public function exportExpensesExcel(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to',   now()->toDateString());
        $category = $request->input('category');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ExpensesExport($dateFrom, $dateTo, $category),
            "laporan-pengeluaran-{$dateFrom}-{$dateTo}.xlsx"
        );
    }

    public function exportStocksExcel(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $dateFrom  = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo    = $request->input('date_to',   now()->toDateString());
        $productId = $request->input('product_id');
        $type      = $request->input('type');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\StocksExport($dateFrom, $dateTo, $productId, $type),
            "laporan-stok-{$dateFrom}-{$dateTo}.xlsx"
        );
    }
}
