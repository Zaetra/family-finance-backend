<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Transaction;
use App\Traits\ApiResponse;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use ApiResponse;

    public function summary()
    {
        $user = Auth::user();

        // 1. Total Balance (Sum of all user accounts)
        $totalBalance = Account::where('user_id', $user->id)->sum('balance');

        // 2. Weekly Expenses (Sum of expense transactions in current week)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyExpenses = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereBetween('transaction_date', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        // 3. Recent Transactions (Last 5)
        $recentTransactions = Transaction::with('account')
            ->where('user_id', $user->id)
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        return $this->successResponse([
            'total_balance' => $totalBalance,
            'weekly_expenses' => $weeklyExpenses,
            'recent_transactions' => $recentTransactions
        ], 'Dashboard summary retrieved successfully');
    }
}
