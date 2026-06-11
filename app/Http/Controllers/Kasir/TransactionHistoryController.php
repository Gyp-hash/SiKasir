<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        $transactions = Transaction::with('user')
            ->where('user_id', $request->user()->id)
            ->when($search, function ($query) use ($search): void {
                $query->where('code', 'like', '%'.$search.'%');
            })
            ->latest('transaction_date')
            ->paginate(10)
            ->withQueryString();

        return view('kasir.transactions.index', compact('transactions', 'search'));
    }

    public function show(Request $request, Transaction $transaction): View
    {
        abort_unless($transaction->user_id === $request->user()->id, 403);

        $transaction->load(['details.product.category', 'user']);

        return view('kasir.transactions.show', compact('transaction'));
    }
}
