<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Flow;
use App\Models\User;
use App\Models\RolFlow;
use App\Models\LocationUser;
use Illuminate\Http\Request;
use App\Models\TypePermission;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth:api');
    }


    /**
     * @OA\Get(
     * path="/api/v1/relative",
     * summary="Listar mis licenciados",
     * description="Listar mis licenciados (con Token)",
     * operationId="RelativesGet",
     * tags={"Licencias"},
     * @OA\Response(
     *    response=200,
     *    description="Ok",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ok"),
     *       @OA\Property(property="obj", type="string", example="array()"),
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Error: Unauthorized",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthenticated"),
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */

    public function myRelative(Request $request){


        if(!isset($request->user_id)){
            $request->user_id = auth()->user()->id;
        }

        $myRelative = [];

        if(auth()->user()->myFirstRols() != "MASTER_ROL"){

            $users = User::whereNotNull("user_relative")->get();

        }else{

            $validator = Validator::make($request->all(), [
                'relative_id' => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $users = User::where("user_relative",$request->user_id)->where("id",$request->relative_id)->get();

        }

        foreach ($users as $key => $value) {

            $data = RolFlow::mypermisions($value);

            $myrol = Rol::myRols($value);

            array_push($myRelative, [
                'fullname' => $value->name.' '.$value->lastname,
                'id' => $value->id,
                'canEdit' => false,
                'open' => false,
                'telefono' => $value->phone,
                'type' => (isset($myrol[0]))? ($myrol[0] != "LICENCIADO_ROL")? "Licenciado" : "Titular" : "Licenciado",
                'permisos' => $data
            ]);
        }


        return response()->json($myRelative, 200);

    }

    /**
     * @OA\Post(
     * path="/api/v1/relative",
     * summary="Alta/Modificar de licencia",
     * description="Alta/Modificar de licencia (con Token)",
     * operationId="RelativePostPut",
     * tags={"Licencias"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del licenciado",
     *    @OA\JsonContent(
     *       required={"relative_id","lastname","name","email","location_id"},
     *       @OA\Property(property="relative_id", type="int", format="number", example=1),
     *       @OA\Property(property="location_id", type="int", format="number", example=1),
     *       @OA\Property(property="lastname", type="string", format="text", example="Ejemplo"),
     *       @OA\Property(property="name", type="string", format="text", example="Ejemplo"),
     *       @OA\Property(property="email", type="string", format="text", example="Ejemplo"),
     *       @OA\Property(property="status", type="string", format="text", example="array[]"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ok",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ok"),
     *       @OA\Property(property="obj", type="string", example="array()"),
     *        )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized"),
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="relative_id", type="number", example="The relative_id field is required."),
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */

    public function createOrUodateRelative(Request $request){


        if(!isset($request->relative_id)){

            $request->relative_id = 999999999;
        }

        if(!isset($request->user_id)){
            $request->user_id = auth()->user()->id;
        }

        if(!User::where("user_relative",$request->user_id)->where("id",$request->relative_id)->exists()){

            $validator = Validator::make($request->all(), [
                'lastname' => 'required|string',
                'email' => 'required|email',
                'name' => 'required|string',
                'location_id' => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $relative = User::create(
                [
                    "name"=> $request->name,
                    "password"=> bcrypt(1357910882221123123123123222),
                    // "password_confirmation"=> bcrypt(1357910882221123123123123222),
                    "allow"=> "1",
                    "lastname"=> $request->lastname,
                    "passport"=> 87654321,
                    "phone"=> 12345678,
                    "email"=> $request->email,
                    "avatar"=> "",
                    "user_relative" => auth()->user()->id
                ]);

                $location = LocationUser::create([
                'fk_user_id' => $relative->id,
                    'fk_location_id' => $request->location_id
                ]);

                // $token = Password::getRepository()->create($relative);

                // Mail::send(['text' => 'emails.password'], ['token' => $token], function (Message $message) use ($relative) {
                //     $message->subject(config('app.name') . ' Password Reset Link');
                //     $message->to($relative->email);
                // });


                if(!isset($request->rol) OR $request->rol == "LICENCIA_ROL"){

                    $flow = Flow::where('description','dashboard')->first()->id;

                    $rol = Rol::where('name','LICENCIA_ROL')->first()->id;

                    $permission = TypePermission::where("letter","r")->first()->id;

                    $rolFlow = RolFlow::create([
                        "fk_user_id" => $relative->id,
                        "fk_lot_user_id" => $request->location_id,
                        "fk_rol_id" => $rol,
                        "fk_flow_permission_id" => $flow,
                        "fk_type_permission_id" => $permission
                    ]);

                }
                $request->relative_id = $relative->id;

        }


        if(isset($request->status)){

            try {
                $permission = TypePermission::where("letter","r")->first()->id;

                $validator = Validator::make($request->all(), [
                    'status' => 'required|array'
                ]);

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 422);
                }

                foreach ($request->status as $key => $value) {

                $rolflow = RolFlow::where("fk_user_id",$request->relative_id)->where("fk_flow_permission_id",Flow::where("description",$value->type)->first()->id)->first();

                    if ($rolflow) {

                        $rolflow->fk_type_permission_id = ($value->active)? $permission : null;
                        $rolflow->save();

                    } else {

                        $relative = User::where("user_relative",$request->user_id)->where("id",$request->relative_id)->first();

                        $rol = Rol::where('name','LICENCIA_ROL')->first()->id;

                        $rolFlow = RolFlow::create([
                            "fk_user_id" => $request->relative_id,
                            "fk_lot_user_id" => $request->location_id,
                            "fk_rol_id" => $rol,
                            "fk_flow_permission_id" => Flow::where("description",$value->type)->first()->id,
                            "fk_type_permission_id" => ($request->active)? $permission->id : null,
                        ]);

                        return response()->json(['type' => 'data' , 'error' => 'id '.$request->id.' message not exists'], 422);
                    }
                }

                $response = User::dataEs(User::where("user_relative",$request->user_id)->where("id",$request->relative_id)->get());

                return response()->json($response, 200);

            } catch (\Exception $th) {
                return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
            }

        }else{

            $response = User::dataEs(User::where("user_relative",$request->user_id)->where("id",$request->relative_id)->first());

            return response()->json($response, 200);

        }
    }


    public function userAll(){

        return response()->json(User::all(), 200);

    }


}
