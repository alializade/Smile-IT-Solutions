<?php

namespace AliAlizade\Customer\Http\Requests;

use AliAlizade\Customer\Enums\CurrencyEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use JetBrains\PhpStorm\ArrayShape;

class CreateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    #[ArrayShape([
        'customer_id' => "array", 'initial_deposit_amount' => "string[]", 'currency' => "array",
    ])]
    public function rules(): array
    {
        return [
            'customer_id'            => ['required', Rule::exists('customers', 'id')],
            'initial_deposit_amount' => ['required', 'numeric', 'min:15', 'max:1000'],
            'currency'               => ['required', 'string', new Enum(CurrencyEnum::class)],
        ];
    }
}
