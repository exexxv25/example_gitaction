<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class user_test extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'AdminTest',
            'email' => 'admin@neighbors.com.ar',
            'password' => bcrypt('neighbors3212021'),
        ]);
    }
}
