<?php

namespace App\Models;

use App\Models\RolFlow;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function dataEs($user = null){

        $user?? $user = auth()->user();

        return (object)[
            'id' => $user->id,
            'role' => Rol::myRols($user),
            'permisos' => RolFlow::dataEs($user),
            'viviendas' => LotUser::dataEs($user->id),
            'barrios' => LocationUser::dataEs($user->id),
            'habilitado' => $user->allow,
            'nombre' => $user->name,
            'apellido' => $user->lastname,
            'dni' => $user->passport,
            'telefono' => $user->phone,
            'email' => $user->email,
            'img' => $user->avatar,
            'uid' => bin2hex(random_bytes(20))
        ];

    }

    public function myRols($user_id = null){

        if(is_null($user_id)){

            $user_id = auth()->user()->id;
        }

        $autorizations = RolFlow::leftJoin('flows','flows.id','=','rol_flows.fk_flow_permission_id')
        ->leftJoin('type_permissions','type_permissions.id','=','rol_flows.fk_type_permission_id')
        ->leftJoin('rols','rols.id','=','rol_flows.fk_rol_id')
        ->where("fk_user_id",$user_id)
        ->get([
                'rol_flows.*',
                'flows.description as flujo',
                'type_permissions.description as permiso',
                'rols.name as role',
            ]);

        $roles = array_unique($autorizations->map->role->toArray());

        if(!isset($roles[0])){

            return array("SIN ROL ASIGNADO");
        }

        return $roles;

    }


    public function myFirstRols($user_id = null){

        if(is_null($user_id)){

            $user_id = auth()->user()->id;
        }

        $autorizations = RolFlow::leftJoin('flows','flows.id','=','rol_flows.fk_flow_permission_id')
        ->leftJoin('type_permissions','type_permissions.id','=','rol_flows.fk_type_permission_id')
        ->leftJoin('rols','rols.id','=','rol_flows.fk_rol_id')
        ->where("fk_user_id",$user_id)
        ->first([
                'rols.name as role'
            ]);

            try {

                $roles = $autorizations->role;

                return $roles;
            } catch (\Throwable $th) {


                return array("SIN ROL ASIGNADO");

            }

    }


    public static function myLocation($user_id = null){

        if(is_null($user_id)){

            $user_id = auth()->user()->id;
        }

        $location = RolFlow::leftJoin('flows','flows.id','=','rol_flows.fk_flow_permission_id')
        ->leftJoin('type_permissions','type_permissions.id','=','rol_flows.fk_type_permission_id')
        ->leftJoin('rols','rols.id','=','rol_flows.fk_rol_id')
        ->leftJoin('users','users.id','=','rol_flows.fk_user_id')
        ->leftJoin('location_users','location_users.fk_user_id','=','users.id')
        ->leftJoin('locations','locations.id','=','location_users.fk_location_id')
        ->where("users.id",$user_id)
        ->groupBy('locations.name')
        ->get([
                'locations.id',
                'locations.name as nombre_barrio',
                'rols.name as role',

            ]);

            $roles = array_unique($location->map->role->toArray());

            if(!isset($roles[0])){

                return array("SIN ROL ASIGNADO");
            }

            if(is_null(array_unique($location->map->nombre_barrio->toArray())[0]) && $roles[0] == "MASTER_ROL" ){

                $locations = Location::all(["id"])->toArray();

            }else{

                $locations = $location->makeHidden(['role','nombre_barrio'])->toArray();
            }


            if(is_null($locations)){

                return array("SIN BARRIO");
            }

            return array_column($locations,"id");

        }

}
