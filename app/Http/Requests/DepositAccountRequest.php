<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "source_account" => "required|exists:accounts,id,deleted_at,NULL|different:destination_account",
            "destination_account" => "required|exists:accounts,id,deleted_at,NULL|different:source_account",
            "amount" => "required|numeric"
        ];
    }
}
