<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = Role::where('name', 'user')->first();
        $role_admin = Role::where('name', 'admin')->first();                   

        $user = new User();
        $user->name = 'User';
        $user->email = 'heynerlbr@gmail.com';
        $user->password = bcrypt('sdk@laravel');
        $user->save();
        $user->roles()->attach($role_user);

        $user = new User();
        $user->name = 'Admin';
        $user->email = 'heyner.becerrasdk@gmail.com';
        $user->password = bcrypt('sdk@laravel');
        $user->save();
        $user->roles()->attach($role_admin);
    }
}
