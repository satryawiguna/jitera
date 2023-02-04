<?php

namespace App\Application\Request\Auth;


use App\Core\Domain\BrokenRule;

/**
 * @OA\Schema(
 *      schema="LoginDataRequest",
 *      type="object",
 *      required={"identity", "password"}
 * )
 */

class LoginDataRequest
{
    use BrokenRule;

    /**
     * @OA\Property(
     *      property="identity",
     *      title="identity",
     *      example="satrya@freshcms.net",
     *      type="string"
     * )
     */
    public string $identity = "";

    /**
     * @OA\Property(
     *      property="password",
     *      title="password",
     *      example="12345",
     *      type="string"
     * )
     */
    public string $password = "";

    public function rules()
    {
        if (filter_var($this->identity, FILTER_VALIDATE_EMAIL)) {
            $rules = [
                'identity' => ['required', 'string', 'email'],
                'password' => ['required', 'min:8']
            ];
        } else {
            $rules = [
                'identity' => ['required', 'string'],
                'password' => ['required', 'min:8']
            ];
        }

        return $rules;
    }
}
