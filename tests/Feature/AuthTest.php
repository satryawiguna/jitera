<?php

namespace Tests\Feature;

use App\Domain\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $user = User::all()->first();

        Http::fake([
            'jit_server/oauth/token' => Http::response([
                'token_type' => 'Bearer',
                'expires_in' => 86400,
                'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9',
                'refresh_token' => 'def50200a9e6f7b1117627ec627f12d5adcf86560d280f400',
            ], 200)]);

        $loginResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->json('POST', route('api.auth.login'), [
            'identity' => $user->email,
            'password' => 'password'
        ]);

        $loginResponse->assertOk()
            ->assertJsonStructure(['data' => ['token']]);
    }

    public function test_login_invalid()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $user = User::all()->first();

        $loginResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->json('POST', route('api.auth.login'), [
            'identity' => $user->email,
            'password' => 'password'
        ]);

        $loginResponse->assertStatus(401)
            ->assertJson(['type' => 'ERROR']);
    }
}
