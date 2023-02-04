<?php

namespace App\Infrastructure\Transformer\User;

use App\Domain\User;
use League\Fractal\TransformerAbstract;

class StoreUserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            "full_name" => $user->contact->full_name,
            "nick_name" => $user->contact->nick_name,
            "email" => $user->email,
        ];
    }
}
