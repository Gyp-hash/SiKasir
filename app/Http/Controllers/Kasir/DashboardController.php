<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $userId = $request->user()->id;

        // Transaksi hari ini oleh kasir ini
        $todayCount = Transaction::where('user_id', $userId)
            ->whereDate('transaction_date', today())
            ->where('status', Transaction::STATUS_PAID)
            ->count();

        $todaySales = Transaction::where('user_id', $userId)
            ->whereDate('transaction_date', today())
            ->where('status', Transaction::STATUS_PAID)
            ->sum('total');

        // Total seluruh transaksi pribadi
        $totalPersonal = Transaction::where('user_id', $userId)
            ->where('status', Transaction::STATUS_PAID)
            ->count();

        // Total item terjual hari ini oleh kasir ini
        $todayItemsSold = TransactionDetail::whereHas('transaction', function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->whereDate('transaction_date', today())
              ->where('status', Transaction::STATUS_PAID);
        })->sum('quantity');

        // 5 transaksi terakhir oleh kasir ini
        $recentTransactions = Transaction::where('user_id', $userId)
            ->where('status', Transaction::STATUS_PAID)
            ->latest('transaction_date')
            ->limit(5)
            ->get();

        return view('kasir.dashboard', compact(
            'todayCount',
            'todaySales',
            'totalPersonal',
            'todayItemsSold',
            'recentTransactions',
        ));
    }
}
