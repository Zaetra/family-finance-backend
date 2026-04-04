<?php

namespace App\Http\Controllers;

use App\Http\Requests\Account\StoreAccountRequest;
use App\Http\Requests\Account\UpdateAccountRequest;
use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index(Request $request)
    {
        $accounts = $this->accountService->getAccountsForUser($request->user());

        return $this->successResponse($accounts, 'Cuentas recuperadas exitosamente');
    }

    public function store(StoreAccountRequest $request)
    {
        $account = $this->accountService->createAccount($request->user(), $request->validated());

        return $this->successResponse($account, 'Cuenta creada exitosamente', 201);
    }

    public function show(Account $account)
    {
        return $this->successResponse($account, 'Detalles de la cuenta');
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $updatedAccount = $this->accountService->updateAccount($account, $request->validated());

        return $this->successResponse($updatedAccount, 'Cuenta actualizada exitosamente');
    }

    public function destroy(Account $account)
    {
        $this->accountService->deleteAccount($account);

        return $this->successResponse(null, 'Cuenta eliminada exitosamente', 204);
    }
}
