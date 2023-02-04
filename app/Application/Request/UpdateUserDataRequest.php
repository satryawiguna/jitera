<?php

namespace App\Application\Request;

use App\Core\Application\Request\IdentityableRequest;

/**
 * @OA\Schema(
 *      schema="UpdateUserDataRequest",
 *      type="object",
 *      required={"nick_name", "full_name", "role_id"},
 * )
 */

class UpdateUserDataRequest extends IdentityableRequest
{
    /**
     * @OA\Property(
     *      property="nick_name",
     *      title="nick_name",
     *      example="Satrya",
     *      type="string"
     * )
     */
    public string $nick_name;

    /**
     * @OA\Property(
     *      property="full_name",
     *      title="full_name",
     *      example="Satrya Wiguna",
     *      type="string"
     * )
     */
    public string $full_name;

    /**
     * @OA\Property(
     *      property="email",
     *      title="email",
     *      example="satrya@freshcms.net",
     *      type="string"
     * )
     */
    public string $email;

    /**
     * @OA\Property(
     *      property="password_old",
     *      title="password_old",
     *      example="12345",
     *      type="string"
     * )
     */
    public string $password_old;

    /**
     * @OA\Property(
     *      property="password",
     *      title="password",
     *      example="12345",
     *      type="string"
     * )
     */
    public string $password;

    /**
     * @OA\Property(
     *      property="password_confirm",
     *      title="password_confirm",
     *      example="12345",
     *      type="string"
     * )
     */
    public string $password_confirm;

    /**
     * @OA\Property(
     *      property="role_id",
     *      title="role_id",
     *      example=1,
     *      type="integer"
     * )
     */
    public string $role_id;

    public function rules()
    {
        return [
            'nick_name' => ['required', 'string'],
            'full_name' => ['required', 'string'],
            'password_old' => ['required', 'min:8'],
            'password' => ['required', 'min:8'],
            'password_confirm' => ['required', 'same:password'],
            'role_id' => ['required', 'integer'],
        ];
    }

    public function messages()
    {
        return [
            'nick_name.required' => 'Nick name is required',
            'nick_name.string' => 'Nick name is string expected',
            'username.required' => 'Username is required',
            'username.string' => 'Username is string expected',
            'username.unique' => 'Username already taken, please try another one',
            'email.required' => 'Email is required',
            'email.string' => 'Email is string expected',
            'email.unique' => 'Email already taken, please try another one',
            'email.email' => 'Invalid email format',
            'password.required' => 'Password is required',
            'password.min' => 'Password is not allowed less than 8 characters',
            'password_confirm.required' => 'Password confirm is required',
            'password_confirm.match' => 'Password must be match with password',
            'role_id.required' => 'Role id is required',
            'role_id.integer' => 'Role id is integer expected'
        ];
    }
}
