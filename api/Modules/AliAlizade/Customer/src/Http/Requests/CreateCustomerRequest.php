<?php

namespace AliAlizade\Customer\Http\Requests;

use AliAlizade\Customer\Enums\CurrencyEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use JetBrains\PhpStorm\ArrayShape;

class CreateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    #[ArrayShape([
        'name'     => "string[]", 'initial_deposit_amount' => "string[]",
        'currency' => "array",
    ])]
    public function rules(): array
    {
        // todo: write test
        return [
            'name'                   => ['required', 'string', 'min:3', 'max:255',],
            // todo: full name
            'initial_deposit_amount' => ['required', 'numeric', 'min:15', 'max:1000'],
            'currency'               => ['required', 'string', new Enum(CurrencyEnum::class)],
        ];
    }
}
