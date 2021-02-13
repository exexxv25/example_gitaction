<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotUser extends Model
{
    use HasFactory;


    public static function dataEs($user_id){

        $lot = self::leftJoin('users','users.id','=','lot_users.fk_user_id')
        ->leftJoin("locations","locations.id","=","lot_users.fk_location_id")
        ->where("fk_user_id",$user_id)
        ->get([
                'lot_users.id as id_lote',
                'lot_users.name as nombre_lote',
                'locations.name as nombre_barrio*'
            ]);

            if(is_null($lot)){

                return "SIN CASAS";
            }

        return $lot;

    }


}
