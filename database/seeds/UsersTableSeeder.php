<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=[
            'name'=>'SomethingCute',
            'email'=>'user@example.com',
            'password'=>bcrypt('password')
        ];
        User::create($user);
    }
}
