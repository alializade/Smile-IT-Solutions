<?php

namespace AliAlizade\Customer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @property mixed $name
 * @property mixed $id
 */
class CustomerResource extends JsonResource
{
    #[ArrayShape(['id' => "mixed", 'name' => "mixed"])]
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
            // todo: get all related accounts
        ];
    }
}
