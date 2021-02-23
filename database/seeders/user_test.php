<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Flow;
use App\Models\User;
use App\Models\RolFlow;
use App\Models\LocationUser;
use App\Models\TypePermission;
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

        for ($i=0; $i < 10; $i++) {
            $userCreated = User::create(
                [
                    "name"=> "name_".mt_rand(1000000,9999999),
                    "password"=> bcrypt(1357910882221123123123123222),
                    "allow"=> "1",
                    "lastname"=> "lastname_".mt_rand(1000000,9999999),
                    "passport"=> 87654321,
                    "phone"=> 12345678,
                    "email"=> "test_".mt_rand(1000000,9999999)."@neigbors.com",
                    "avatar"=> "",
                    "user_relative" => 4
                ]);

                $location = LocationUser::create([
                    'fk_user_id' => $userCreated->id,
                        'fk_location_id' => 1
                    ]);

                $flow = Flow::where('description','dashboard')->first()->id;

                $rol = Rol::where('name','LICENCIA_ROL')->first()->id;

                $permission = TypePermission::where("letter","r")->first()->id;

                $rolFlow = RolFlow::create([
                    "fk_user_id" => $userCreated->id,
                    "fk_lot_user_id" => $location->id,
                    "fk_rol_id" => $rol,
                    "fk_flow_permission_id" => $flow,
                    "fk_type_permission_id" => $permission
                ]);

        }
    }
}
