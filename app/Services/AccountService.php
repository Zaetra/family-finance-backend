<?php

namespace App\Services;

use App\Models\Account;
use App\Models\User;

class AccountService
{
    /**
     * Get all accounts for a user.
     */
    public function getAccountsForUser(User $user)
    {
        return $user->accounts;
    }

    /**
     * Create a new account for a user.
     */
    public function createAccount(User $user, array $data)
    {
        return $user->accounts()->create($data);
    }

    /**
     * Update an account.
     */
    public function updateAccount(Account $account, array $data)
    {
        $account->update($data);
        return $account;
    }

    /**
     * Delete an account.
     */
    public function deleteAccount(Account $account)
    {
        $account->delete();
    }
}
