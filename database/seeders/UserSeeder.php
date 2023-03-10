<?php

namespace Database\Seeders;

use App\Domain\Contact;
use App\Domain\Role;
use App\Domain\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //It will add one user as Administrator
        $role = (new Role())->where('slug', '=', 'administrator')->first();

        $user = User::create([
            'username' => 'admin',
            'email' => 'admin@jitera.eu.org',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_by' => 'system',
            'role_id' => $role->id
        ]);

        $user->contact()->create([
            'nick_name' => 'Satrya',
            'full_name' => 'Satrya Wiguna'
        ]);

        //It will create randomly user as Member
        Contact::factory()->count(9)->create();
    }
}
