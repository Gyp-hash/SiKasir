<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Daftar pengeluaran dengan filter tanggal & kategori.
     */
    public function index(Request $request): View
    {
        $category  = $request->input('category');
        $dateFrom  = $request->input('date_from');
        $dateTo    = $request->input('date_to');

        $expenses = Expense::with('creator')
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($dateFrom,  fn ($q) => $q->whereDate('expense_date', '>=', $dateFrom))
            ->when($dateTo,    fn ($q) => $q->whereDate('expense_date', '<=', $dateTo))
            ->latest('expense_date')
            ->paginate(15)
            ->withQueryString();

        $totalFiltered = Expense::query()
            ->when($category, fn ($q) => $q->where('category', $category))
            ->when($dateFrom,  fn ($q) => $q->whereDate('expense_date', '>=', $dateFrom))
            ->when($dateTo,    fn ($q) => $q->whereDate('expense_date', '<=', $dateTo))
            ->sum('amount');

        $categories = Expense::CATEGORIES;

        return view('owner.expenses.index', compact(
            'expenses',
            'categories',
            'category',
            'dateFrom',
            'dateTo',
            'totalFiltered',
        ));
    }

    /**
     * Form tambah pengeluaran.
     */
    public function create(): View
    {
        $categories = Expense::CATEGORIES;

        return view('owner.expenses.create', compact('categories'));
    }

    /**
     * Simpan pengeluaran baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'expense_date' => ['required', 'date'],
            'category'     => ['required', 'string', \Illuminate\Validation\Rule::in(Expense::CATEGORIES)],
            'description'  => ['required', 'string', 'max:500'],
            'amount'       => ['required', 'numeric', 'min:1'],
        ]);

        Expense::create([
            'expense_date' => $validated['expense_date'],
            'category'     => $validated['category'],
            'description'  => $validated['description'],
            'amount'       => $validated['amount'],
            'created_by'   => $request->user()->id,
        ]);

        return redirect()
            ->route('owner.expenses.index')
            ->with('success', 'Pengeluaran berhasil dicatat.');
    }

    /**
     * Detail pengeluaran.
     */
    public function show(Expense $expense): View
    {
        $expense->load('creator');

        return view('owner.expenses.show', compact('expense'));
    }
}
