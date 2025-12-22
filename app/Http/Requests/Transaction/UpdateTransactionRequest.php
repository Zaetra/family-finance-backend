<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'sometimes|required|numeric',
            'type' => 'sometimes|required|string|in:income,expense',
            'category' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'transaction_date' => 'sometimes|required|date',
        ];
    }
}
