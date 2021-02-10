<?php

namespace Database\Seeders;

use App\Models\TypePermission;
use Illuminate\Database\Seeder;

class TypePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typePermissions = ["c" => "create","r" => "read","u" => "update","d" => "delete"];

        foreach ($typePermissions as $key => $value) {

            TypePermission::create([
                "description" => $value,
                "letter" => $key
            ]);

        }
    }
}
