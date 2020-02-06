<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Master',
            'email' => 'master@email.com',
            'password' => bcrypt('asdasdasd'),
            'admin' => true,
        ]);
    }
}
