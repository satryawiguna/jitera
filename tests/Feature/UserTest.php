<?php

namespace Tests\Feature;

use App\Domain\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_get_all_user_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')->get(route('api.user.all'));

        $response->assertOk()
            ->assertJsonStructure(['datas']);
    }

    public function test_get_all_user_invalid()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->json('GET', route('api.user.all'));

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_get_all_user_search_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')->post(route('api.user.search'), [
            "search" => null,
            "orderBy" => "id",
            "sort" => "asc",
            "args"=> []
        ]);

        $response->assertOk()
            ->assertJsonStructure(['datas']);
    }

    public function test_get_all_user_search_invalid()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post(route('api.user.search'), [
            "search" => null,
            "orderBy" => "id",
            "sort" => "asc",
            "args"=> []
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_get_all_user_search_page_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $user = User::all()->first();

        $response = $this->actingAs($user, 'api')->post(route('api.user.search.page'), [
            "search" => null,
            "orderBy" => "id",
            "sort" => "asc",
            "perPage" => 10,
            "page" => 1,
            "args"=> []
        ]);

        $response->assertOk()
            ->assertJsonStructure(['datas']);
    }

    public function test_get_all_user_search_page_invalid()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post(route('api.user.search.page'), [
            "search" => null,
            "orderBy" => "id",
            "sort" => "asc",
            "perPage" => 10,
            "page" => 1,
            "args"=> []
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_get_user_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userLogged = $users->first();
        $userTarget = $users->toArray()[rand(0, $users->count() - 1)];

        $response = $this->actingAs($userLogged, 'api')->get(route('api.user.get', ["id" => $userTarget["id"]]));

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_get_user_invalid()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userTarget = $users->toArray()[rand(0, $users->count() - 1)];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->json('GET', route('api.user.get', ["id" => $userTarget["id"]]));

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_store_user_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userLogged = $users->first();

        $response = $this->actingAs($userLogged, 'api')->post(route('api.user.store'), [
            "full_name" => "Erna",
            "nick_name" => "Laskmidewi",
            "username" => "riana",
            "email" => "riana@freshcms.net",
            "password" => "password",
            "password_confirm" => "password",
            "role_id" => 2
        ]);

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_store_user_invalid()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->json('POST', route('api.user.store'), [
            "full_name" => "Erna",
            "nick_name" => "Laskmidewi",
            "username" => "riana",
            "email" => "riana@freshcms.net",
            "password" => "password",
            "password_confirm" => "password",
            "role_id" => 2
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_update_user_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userTarget = $users->toArray()[rand(0, $users->count() - 1)];
        $userLogged = $users->first();

        $response = $this->actingAs($userLogged, 'api')->put(route('api.user.update', ["id" => $userTarget["id"]]), [
            "full_name" => "Kompel Lompel Dompel",
            "nick_name" => "Dompel",
            "password" => "12345"
        ]);

        $response->assertOk()
            ->assertJsonStructure(['data']);
    }

    public function test_update_user_invalid()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userTarget = $users->toArray()[rand(0, $users->count() - 1)];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->json('PUT', route('api.user.update', ["id" => $userTarget["id"]]), [
            "full_name" => "Kompel Lompel Dompel",
            "nick_name" => "Dompel",
            "password" => "12345"
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_destroy_user_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userLogged = $users->first();
        $userTarget = $users->toArray()[rand(0, $users->count() - 1)];

        $response = $this->actingAs($userLogged, 'api')->delete(route('api.user.destroy', ["id" => $userTarget["id"]]));

        $response->assertOk();
    }

    public function test_destroy_user_invalid()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userTarget = $users->toArray()[rand(0, $users->count() - 1)];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->json('DELETE', route('api.user.destroy', ["id" => $userTarget["id"]]));

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_follow_user_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userLogged = $users->first();

        $usersExceptUserLogged = $users->where('id', '!=', $userLogged->id);

        $userTarget = $usersExceptUserLogged->toArray()[rand(0, $usersExceptUserLogged->count() - 1)];

        $response = $this->actingAs($userLogged, 'api')->get(route('api.user.follow', ["userId" => $userTarget["id"]]));

        $response->assertOk()
            ->assertJson(["type" => "SUCCESS"]);
    }

    public function test_unfollow_user_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userLogged = $users->first();

        $usersExceptUserLogged = $users->where('id', '!=', $userLogged->id);

        $userTarget = $usersExceptUserLogged->toArray()[rand(0, $usersExceptUserLogged->count() - 1)];

        $this->actingAs($userLogged, 'api')->get(route('api.user.follow', ["userId" => $userTarget["id"]]));
        $response = $this->actingAs($userLogged, 'api')->get(route('api.user.follow', ["userId" => $userTarget["id"]]));

        $response->assertOk()
            ->assertJson(["type" => "SUCCESS"]);
    }

    public function test_follow_user_with_id_it_self_success()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userLogged = $users->first();

        $response = $this->actingAs($userLogged, 'api')->get(route('api.user.follow', ["userId" => $userLogged->id]));

        $response->assertStatus(400)
            ->assertJson(["type" => "ERROR"]);
    }

    public function test_follow_user_invalid()
    {
        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
        $this->artisan('passport:install');

        $users = User::all();

        $userTarget = $users->toArray()[rand(0, $users->count() - 1)];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->json('GET', route('api.user.follow', ["userId" => $userTarget["id"]]));

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
