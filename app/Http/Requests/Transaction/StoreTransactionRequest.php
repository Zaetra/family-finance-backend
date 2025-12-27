<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric',
            'type' => 'required|string|in:income,expense',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
        ];
    }
}
