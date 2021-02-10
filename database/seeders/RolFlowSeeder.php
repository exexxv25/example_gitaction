<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Flow;
use App\Models\User;
use App\Models\RolFlow;
use App\Models\Location;
use App\Models\LocationUser;
use App\Models\LotUser;
use Illuminate\Support\Str;
use App\Models\TypePermission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;

class RolFlowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $locations = Location::all();

        $locationsCount = count($locations);

        $lot = mt_rand(0,$locationsCount);

        $typePermission = TypePermission::all();

        $userMaster = User::firstOrCreate(
            ['email' => 'master@neighbors.com'],
            ['name' => "master",
            'email_verified_at' => now(),
            'password' => bcrypt('123456'), // password
            'lastname' => "master",
            'passport' => mt_rand(10000000,99999999),
            'phone' => mt_rand(10000000,99999999),
            'avatar' => Str::random(10)]
        );

        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@neighbors.com'],
            ['name' => "admin",
            'email_verified_at' => now(),
            'password' => bcrypt('123456'), // password
            'lastname' => "admin",
            'passport' => mt_rand(10000000,99999999),
            'phone' => mt_rand(10000000,99999999),
            'avatar' => Str::random(10)]
        );

        $lotAdmin = LocationUser::create([
            'fk_user_id' => $userAdmin->id,
            'fk_location_id' => $lot,
        ]);

        $userVecino = User::firstOrCreate(
            ['email' => 'vecino@neighbors.com'],
            ['name' => "vecino",
            'email_verified_at' => now(),
            'password' => bcrypt('123456'), // password
            'lastname' => "vecino",
            'passport' => mt_rand(10000000,99999999),
            'phone' => mt_rand(10000000,99999999),
            'avatar' => Str::random(10)]
        );

        LocationUser::create([
            'fk_user_id' => $userVecino->id,
            'fk_location_id' => $lot
        ]);

        LotUser::create([
            'fk_user_id' => $userVecino->id,
            'fk_location_id' => $lot,
            'name' => "miloteEjemplo1"
        ]);

        $rols = Rol::all();

        foreach ($rols as $key => $value) {


            switch ($value->name) {
                case 'ADMIN_ROL':

                    $Flow = [
                        "dashboard",
                        "notificaciones",
                        "gestiones",
                        "unidades",
                        "novedades",
                        "autorizaciones",
                        "amenities"
                    ];

                    foreach ($Flow as $afk => $afv) {

                        $afm = Flow::where("description",$afv)->first();

                        foreach ($typePermission as $tpk => $tpv) {

                            if($afm->description ==  "dashboard" && $tpv->letter == "r" ){

                                $rolFlow = RolFlow::create([
                                    "fk_user_id" => $userAdmin->id,
                                    "fk_lot_user_id" => $lotAdmin->id,
                                    "fk_rol_id" => $value->id,
                                    "fk_flow_permission_id" => $afm->id,
                                    "fk_type_permission_id" => $tpv->id,
                                ]);

                            }elseif($afm->description !=  "dashboard"){

                                $rolFlow = RolFlow::create([
                                    "fk_user_id" => $userAdmin->id,
                                    "fk_lot_user_id" => $lotAdmin->id,
                                    "fk_rol_id" => $value->id,
                                    "fk_flow_permission_id" => $afm->id,
                                    "fk_type_permission_id" => $tpv->id,
                                ]);
                            }
                        }
                    }

                break;
                case 'MASTER_ROL':

                    $Flow = [
                        "dashboard",
                        "clientes",
                        "administradores",
                        "mantenimientos"
                    ];

                    foreach ($Flow as $afk => $afv) {

                        $afm = Flow::where("description",$afv)->first();

                        foreach ($typePermission as $tpk => $tpv) {

                            if($afm->description ==  "dashboard" && $tpv->letter == "r"){

                                $rolFlow = RolFlow::create([
                                    "fk_user_id" => $userMaster->id,
                                    "fk_lot_user_id" => null,
                                    "fk_rol_id" => $value->id,
                                    "fk_flow_permission_id" => $afm->id,
                                    "fk_type_permission_id" => $tpv->id
                                ]);

                            }elseif($afm->description !=  "dashboard"){

                                $rolFlow = RolFlow::create([
                                    "fk_user_id" => $userMaster->id,
                                    "fk_lot_user_id" => null,
                                    "fk_rol_id" => $value->id,
                                    "fk_flow_permission_id" => $afm->id,
                                    "fk_type_permission_id" => $tpv->id
                                ]);
                            }
                        }
                    }

                break;
                case 'VECINO_ROL':


                    $Flow = [
                        "dashboard",
                        "documentos",
                        "licencias",
                        "gestiones",
                        "autorizaciones",
                        "calendario",
                        "guia_de_servicios",
                        "clima",
                        "encuestas",
                        "camaras",
                        "galeria",
                        "estado_cc",
                        "expensas",
                        "consulta_de_personas",
                        "horarios"
                    ];

                    foreach ($Flow as $afk => $afv) {

                        $afm = Flow::where("description",$afv)->first();

                        foreach ($typePermission as $tpk => $tpv) {

                        if($afm->description ==  "dashboard" && $tpv->letter == "r"){

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id
                            ]);

                        }elseif ($afm->description ==  "gestiones" && $tpv->letter == "r") {

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id
                            ]);

                        }elseif ($afm->description ==  "gestiones" && $tpv->letter == "c") {

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id
                            ]);

                        }elseif ($afm->description ==  "licencias" && $tpv->letter == "r") {

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id
                            ]);

                        }elseif ($afm->description ==  "licencias" && $tpv->letter == "c") {

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id
                            ]);

                        }elseif ($afm->description ==  "autorizaciones" && $tpv->letter == "r") {

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id
                            ]);

                        }elseif ($afm->description ==  "autorizaciones" && $tpv->letter == "c") {

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id

                            ]);

                        }elseif ($afm->description ==  "calendario" && $tpv->letter == "r") {

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id
                            ]);

                        }elseif ($afm->description ==  "calendario" && $tpv->letter == "c") {

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id
                            ]);

                        }elseif ($afm->description !=  "dashboard" && $afm->description !=  "gestiones"
                        && $afm->description !=  "licencias" && $afm->description != "autorizaciones"
                        && $afm->description !=  "calendario" && $tpv->letter == "r") {

                            $rolFlow = RolFlow::create([
                                "fk_user_id" => $userVecino->id,
                                "fk_lot_user_id" => $lot,
                                "fk_rol_id" => $value->id,
                                "fk_flow_permission_id" => $afm->id,
                                "fk_type_permission_id" => $tpv->id
                            ]);
                        }
                    }
                }
                break;
                default:
                break;
            }
        }
    }
}
