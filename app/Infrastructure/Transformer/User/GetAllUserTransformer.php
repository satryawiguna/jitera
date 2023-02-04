<?php

namespace App\Infrastructure\Transformer\User;

use App\Domain\User;
use League\Fractal\TransformerAbstract;

/**
 * @OA\Schema(
 *      schema="GetAllUserDataResponse",
 *      type="array",
 *      @OA\Items(
 *          type="object",
 *          @OA\Property(
 *               property="id",
 *               title="id",
 *               example="043acf25-9a28-4cc8-99d5-9cb6b29d4836",
 *               type="string"
 *          ),
 *          @OA\Property(
 *               property="username",
 *               title="username",
 *               example="satryawiguna",
 *               type="string"
 *          ),
 *          @OA\Property(
 *               property="email",
 *               title="email",
 *               example="satrya@freshcms.net",
 *               type="string"
 *          )
 *      )
 * )
 */
class GetAllUserTransformer extends TransformerAbstract
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
            "id" => $user->id,
            "username" => $user->username,
            "email" => $user->email,
        ];
    }
}
