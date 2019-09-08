<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'first_name' => '',
            'last_name' => '',
            'email' => 'admin@example.com',
            'password' => bcrypt('secret'),
            'is_admin' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        DB::table('users')->insert([
            'name' => 'User',
            'first_name' => 'Юзер',
            'last_name' => 'Юзеренко',
            'email' => 'user@example.com',
            'password' => bcrypt('secret'),
            'is_admin' => false,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}
