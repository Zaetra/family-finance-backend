<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;

class TransactionService
{
    /**
     * Get transactions for a user (personal and family).
     */
    public function getTransactionsForUser(User $user)
    {
        $query = Transaction::query();
        
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
     * Create a new transaction.
     */
    public function createTransaction(User $user, array $data)
    {
        $data['user_id'] = $user->id;
        $data['family_group_id'] = $user->family_group_id;

        return Transaction::create($data);
    }

    /**
     * Update a transaction.
     */
    public function updateTransaction(Transaction $transaction, array $data)
    {
        $transaction->update($data);
        return $transaction;
    }

    /**
     * Delete a transaction.
     */
    public function deleteTransaction(Transaction $transaction)
    {
        $transaction->delete();
    }
}
