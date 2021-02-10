<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(type_message_data::class);
        $this->call(LocationSeeder::class);
        $this->call(user_test::class);
        $this->call(FlowSeeder::class);
        $this->call(RolSeeder::class);
        $this->call(TypePermissionSeeder::class);
        $this->call(RolFlowSeeder::class);

    }
}
