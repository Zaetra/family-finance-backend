<?php

namespace App\Http\Requests\FamilyGroup;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFamilyGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
