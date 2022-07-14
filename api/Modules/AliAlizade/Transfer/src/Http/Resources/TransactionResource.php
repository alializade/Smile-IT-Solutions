<?php

namespace AliAlizade\Transfer\Http\Resources;

use AliAlizade\Transfer\Enums\TransactionStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property $tid
 * @property $from
 * @property $to
 * @property $amount
 * @property $status
 * @property $created_at
 */
class TransactionResource extends JsonResource
{
    #[ArrayShape([
        'tid'    => "int", 'from' => "int", 'to' => "int", 'amount' => "mixed",
        'status' => TransactionStatusEnum::class, 'created_at' => "datetime",
    ])]
    public function toArray($request): array
    {
        return [
            'tid'        => $this->tid,
            'from'       => $this->from,
            'to'         => $this->to,
            'amount'     => $this->amount,
            'status'     => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
