<?php

namespace App\Application\Request\Auth;

use App\Core\Application\Request\AuditableRequest;

/**
 * @OA\Schema(
 *      schema="RegisterDataRequest",
 *      type="object",
 *      required={"nick_name", "full_name"},
 * )
 */

class RegisterDataRequest extends AuditableRequest
{
    /**
     * @OA\Property(
     *      property="nick_name",
     *      title="nick_name",
     *      example="Satrya",
     *      type="string"
     * )
     */
    public string $nick_name = "";

    /**
     * @OA\Property(
     *      property="full_name",
     *      title="full_name",
     *      example="Satrya Wiguna",
     *      type="string"
     * )
     */
    public string $full_name = "";

    /**
     * @OA\Property(
     *      property="username",
     *      title="username",
     *      example="satryawiguna",
     *      type="string"
     * )
     */
    public string $username = "";

    /**
     * @OA\Property(
     *      property="email",
     *      title="email",
     *      example="satrya@freshcms.net",
     *      type="string"
     * )
     */
    public string $email = "";

    /**
     * @OA\Property(
     *      property="password",
     *      title="password",
     *      example="12345",
     *      type="string"
     * )
     */
    public string $password = "";

    /**
     * @OA\Property(
     *      property="password_confirm",
     *      title="password_confirm",
     *      example="12345",
     *      type="string"
     * )
     */
    public string $password_confirm = "";

    public function rules()
    {
        return [
            'nick_name' => ['required', 'string'],
            'full_name' => ['required', 'string'],
            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'string', 'unique:users', 'email'],
            'password' => ['required', 'min:8'],
            'password_confirm' => ['required', 'same:password']
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
            'password_confirm.match' => 'Password must be match with password'
        ];
    }
}
