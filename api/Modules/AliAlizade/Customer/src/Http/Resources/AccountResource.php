<?php

namespace AliAlizade\Customer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $current_amount
 * @property mixed $currency
 * @property mixed $account_number
 * @property mixed $customer
 * @property mixed $id
 */
class AccountResource extends JsonResource
{
    #[ArrayShape([
        'id'             => "mixed",
        'customer'       => "\Illuminate\Http\Resources\MissingValue|mixed",
        'account_number' => "mixed", 'currency' => "mixed", 'current_amount' => "mixed",
    ])]
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'customer'       => $this->whenLoaded('customer',
                fn() => new CustomerResource($this->customer)
            ),
            'account_number' => $this->account_number,
            'currency'       => $this->currency,
            'current_amount' => $this->current_amount,
        ];
    }
}
