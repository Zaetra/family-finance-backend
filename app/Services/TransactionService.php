<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Get transactions for a user (personal and family).
     */
    public function getTransactionsForUser(User $user)
    {
        $query = Transaction::with('account');
        
        if ($user->family_group_id) {
            $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('family_group_id', $user->family_group_id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        return $query->orderBy('transaction_date', 'desc')->get();
    }

    /**
     * Create a new transaction and update account balance.
     */
    public function createTransaction(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $data['user_id'] = $user->id;
            $data['family_group_id'] = $user->family_group_id;

            $transaction = Transaction::create($data);

            // Update Account Balance
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

            return $transaction;
        });
    }

    /**
     * Update a transaction.
     * Note: Does NOT currently adjust balance for edits to avoid complexity unless requested.
     */
    public function updateTransaction(Transaction $transaction, array $data)
    {
        // TODO: Handle balance adjustments on update if amount/type/account changes.
        $transaction->update($data);
        return $transaction;
    }

    /**
     * Delete a transaction and revert account balance.
     */
    public function deleteTransaction(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            // Revert Account Balance
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
