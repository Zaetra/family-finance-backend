<?php

namespace App\Services;

use App\Models\Account;
use App\Models\PendingTransaction;
use App\Models\Transaction;
use App\Models\TransactionShare;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function getTransactionsForUser(User $user)
    {
        $query = Transaction::with(['account', 'shares', 'pendingTransaction']);

        if ($user->family_group_id) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('family_group_id', $user->family_group_id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        return $query->orderBy('transaction_date', 'desc')->get();
    }

    public function createTransaction(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $data['user_id'] = $user->id;
            $data['family_group_id'] = $user->family_group_id;

            $transaction = Transaction::create($data);

            if (isset($data['account_id'])) {
                $account = Account::find($data['account_id']);
                if ($account) {
                    if ($data['type'] === 'income') {
                        $account->balance += $data['amount'];
                    } elseif ($data['type'] === 'expense') {
                        $account->balance -= $data['amount'];
                    }
                    $account->save();
                }
            }

            if (isset($data['shares']) && is_array($data['shares'])) {
                foreach ($data['shares'] as $shareData) {
                    TransactionShare::create([
                        'transaction_id' => $transaction->id,
                        'user_id' => $shareData['user_id'],
                        'amount' => $shareData['amount'],
                    ]);
                }
            }

            if (isset($data['pending_transaction_id'])) {
                $pendingTransaction = PendingTransaction::find($data['pending_transaction_id']);
                if ($pendingTransaction) {
                    $pendingTransaction->update([
                        'status' => 'PAID',
                        'transaction_id' => $transaction->id,
                    ]);
                }
            }

            return $transaction;
        });
    }

    public function updateTransaction(Transaction $transaction, array $data)
    {
        $transaction->update($data);

        return $transaction;
    }

    public function deleteTransaction(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            if ($transaction->account_id) {
                $account = Account::find($transaction->account_id);
                if ($account) {
                    if ($transaction->type === 'income') {
                        $account->balance -= $transaction->amount;
                    } elseif ($transaction->type === 'expense') {
                        $account->balance += $transaction->amount;
                    }
                    $account->save();
                }
            }

            $transaction->delete();
        });
    }
}
