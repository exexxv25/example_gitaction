<?php

namespace App\Models;

use App\Models\RolFlow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    public static function myRols($user_id){


        $autorizations = RolFlow::leftJoin('flows','flows.id','=','rol_flows.fk_flow_permission_id')
        ->leftJoin('type_permissions','type_permissions.id','=','rol_flows.fk_type_permission_id')
        ->leftJoin('rols','rols.id','=','rol_flows.fk_rol_id')
        ->where("fk_user_id",$user_id->id)
        ->get([
                'rol_flows.*',
                'flows.description as flujo',
                'type_permissions.description as permiso',
                'rols.name as role',
            ]);


        $roles = array_unique($autorizations->map->role->toArray());


        return $roles;


    }


    public static function dataEs($rol_id){

        if(is_null($rol_id)){

            return (object)[
                'id' => null,
                'role' => "SIN_ROL"
            ];

        }else{

            $rol = self::find($rol_id);

            return (object)[
                'id' => $rol->id,
                'role' => $rol->name
            ];
        }

    }
}
