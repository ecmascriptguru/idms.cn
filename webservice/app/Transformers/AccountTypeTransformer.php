<?php

namespace App\Transformers;

use App\Models\AccountType;
use League\Fractal\TransformerAbstract;

class AccountTypeTransformer extends TransformerAbstract
{
    /**
     * Transform a type.
     *
     * @param  AccountType $type
     *
     * @return array
     */
    public function transform(AccountType $type)
    {
        return [
            'id' => $type->id,
            'name' => $type->name,
            'created_at' => $type->created_at->toIso8601String(),
            'updated_at' => $type->updated_at->toIso8601String(),
        ];
    }
}
