<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::where('name', 'John Doe')->first()) {
            $user = User::factory()->create([
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
            ]);
            $roles = Role::where('guard_name', 'admin')->get();
            $user->roles()->attach($roles);

        }

    }
}
