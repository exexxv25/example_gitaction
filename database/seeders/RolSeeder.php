<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ["LICENCIA_ROL","ADMIN_ROL","MASTER_ROL","VECINO_ROL","INVITADO_ROL"];

        foreach ($roles as $key => $value) {

            Rol::create(
                [
                    "name" => $value
                ]
            );
        }
    }
}
