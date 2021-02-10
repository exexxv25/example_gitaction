<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationUser extends Model
{
    use HasFactory;

    public static function dataEs($user_id){

        $location = self::leftJoin('users','users.id','=','location_users.fk_user_id')
        ->leftJoin("locations","locations.id","=","location_users.fk_location_id")
        ->where("fk_user_id",$user_id)
        ->get([
                'locations.id as id_barrio',
                'locations.name as nombre_barrio'
            ]);


        return $location;

    }
}
