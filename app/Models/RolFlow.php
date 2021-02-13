<?php

namespace App\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolFlow extends Model
{
    use HasFactory;


    public static function myPermiss($user_id){

        if(is_null($user_id)){

            return (object)[
                'id' => null,
                'role' => "SIN_ROL"
            ];

        }else{

            $data = RolFlow::where("fk_user_id",$user_id)->get();

            $rol = [];

            $cicle = 0;

            foreach ($data as $key => $value) {

                $dataRol = self::dataEs($value->fk_rol_id);

                if($cicle == 0){

                    $rol["id"] = $dataRol->id;
                    $rol["role"] = $dataRol->name;

                }else{

                    $rol["another"] = $dataRol;
                }

                $cicle++;
            }

            return (object)$rol;
        }

    }


    public static function dataEs($user){


            $autorizations = self::leftJoin('flows','flows.id','=','rol_flows.fk_flow_permission_id')
            ->leftJoin('type_permissions','type_permissions.id','=','rol_flows.fk_type_permission_id')
            ->leftJoin('rols','rols.id','=','rol_flows.fk_rol_id')
            ->leftJoin('users','users.id','=','rol_flows.fk_user_id')
            ->leftJoin('location_users','location_users.fk_user_id','=','users.id')
            ->leftJoin('locations','locations.id','=','location_users.fk_location_id')
            ->where("users.id",$user->id)
            ->get([
                    'flows.description as flujo',
                    'type_permissions.description as permiso',
                    'rols.name as role',
                    'locations.name as location_name',
                ]);

            $roles = array_unique($autorizations->map->role->toArray());


            if(!isset($roles[0])){

                return "SIN ROL ASIGNADO";
            }


            if(is_null(array_unique($autorizations->map->location_name->toArray())[0]) && $roles[0] == "MASTER_ROL" ){

                $locations = Location::all(["name"])->toArray();

            }else{

                $locations = array_unique($autorizations->map->location_name->toArray());

            }

            if(is_null($locations[0])){

                return array("error" => "configuracion de permisos");
            }

            $flow=array();

            foreach ($locations as $key => $location) {

                foreach ($roles as $key => $rol) {

                        foreach ($autorizations->toArray() as $key => $element) {

                        if($element["role"] == $rol){

                                    $dataLocation = is_array($location)? $location["name"] : $location;

                                    if($element["location_name"] == $dataLocation || is_null($element["location_name"])){

                                        if(!isset($flow[$dataLocation][$rol][$element["flujo"]])){

                                            $flow[$dataLocation][$rol][$element["flujo"]] = array($element["permiso"]);

                                        }else{

                                            array_push($flow[$dataLocation][$rol][$element["flujo"]],$element["permiso"]);

                                        }

                                    }
                            }
                        }
                    }
                }

            return $flow;

    }


}
