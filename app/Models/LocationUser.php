<?php

namespace App\Models;

use App\Models\RolFlow;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocationUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function dataEs($user_id){

    $location = RolFlow::leftJoin('flows','flows.id','=','rol_flows.fk_flow_permission_id')
    ->leftJoin('type_permissions','type_permissions.id','=','rol_flows.fk_type_permission_id')
    ->leftJoin('rols','rols.id','=','rol_flows.fk_rol_id')
    ->leftJoin('users','users.id','=','rol_flows.fk_user_id')
    ->leftJoin('location_users','location_users.fk_user_id','=','users.id')
    ->leftJoin('locations','locations.id','=','location_users.fk_location_id')
    ->where("users.id",$user_id)
    ->groupBy('locations.name')
    ->get([
            'locations.id as id_barrio',
            'locations.name as nombre_barrio',
            'rols.name as role',

        ]);

        $roles = array_unique($location->map->role->toArray());

        if(!isset($roles[0])){

            return "SIN ROL ASIGNADO";
        }

        if(is_null(array_unique($location->map->nombre_barrio->toArray())[0]) && $roles[0] == "MASTER_ROL" ){

            $locations = Location::all(["name as nombre_barrio","id as id_barrio"])->toArray();

        }else{

            $locations = $location->makeHidden(['role'])->toArray();
        }


        if(is_null($locations)){

            return "SIN BARRIO";
        }

        return $locations;

    }
}
