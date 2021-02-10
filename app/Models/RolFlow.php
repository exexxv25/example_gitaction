<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            ->where("fk_user_id",$user->id)
            ->get([
                    'rol_flows.*',
                    'flows.description as flujo',
                    'type_permissions.description as permiso',
                    'rols.name as role',
                ]);


            $roles = array_unique($autorizations->map->role->toArray());

            $flow=array();

                foreach ($autorizations->toArray() as $key => $element) {

                    foreach ($roles as $key => $value) {

                        if($element["role"] == $value){

                            if(!isset($flow[$value][$element["flujo"]])){

                                $flow[$value][$element["flujo"]] = array($element["permiso"]);

                            }else{

                                array_push($flow[$value][$element["flujo"]],$element["permiso"]);

                            }
                        }
                    }
                }

            return $flow;

    }


}
