<?php

namespace App\Infrastructure\Transformer\Auth;

use App\Domain\User;
use League\Fractal\TransformerAbstract;

/**
 * @OA\Schema(
 *      schema="RegisterDataResponse",
 *      type="object",
 *      @OA\Property(
 *           property="nick_name",
 *           title="nick_name",
 *           example="Satrya",
 *           type="string"
 *      ),
 *      @OA\Property(
 *           property="full_name",
 *           title="full_name",
 *           example="Satrya Wiguna",
 *           type="string"
 *      ),
 *      @OA\Property(
 *           property="email",
 *           title="email",
 *           example="satrya@freshcms.net",
 *           type="string"
 *      )
 * )
 */

class RegisterTransformer extends TransformerAbstract
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
