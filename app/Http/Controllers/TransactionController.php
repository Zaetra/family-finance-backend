<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $transactions = $this->transactionService->getTransactionsForUser($request->user());
        return $this->successResponse($transactions, 'Transacciones recuperadas');
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = $this->transactionService->createTransaction($request->user(), $request->validated());

        return $this->successResponse($transaction, 'Transacción registrada correctamente', 201);
    }

    public function show(Transaction $transaction)
    {
        return $this->successResponse($transaction, 'Detalles de la transacción');
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $updated = $this->transactionService->updateTransaction($transaction, $request->validated());
        return $this->successResponse($updated, 'Transacción actualizada');
    }

    public function destroy(Transaction $transaction)
    {
        $this->transactionService->deleteTransaction($transaction);
        return $this->successResponse(null, 'Transacción eliminada', 204);
    }
}
