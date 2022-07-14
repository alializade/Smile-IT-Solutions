<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use JetBrains\PhpStorm\ArrayShape;

class TransferMoneyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    #[ArrayShape(['from' => "array", 'to' => "array", 'amount' => "string[]"])]
    public function rules(): array
    {
        return [
            'from'   => ['required', Rule::exists('accounts', 'account_number')],
            'to'     => ['required', Rule::exists('accounts', 'account_number')],
            'amount' => ['required', 'numeric'],
        ];
    }
}
