<?php

namespace App\Http\Controllers;

use Validator;
use GuzzleHttp\Client;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LocationController extends Controller
{

    public $http;

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');
        $this->http = new Client();

    }

    /**
     * @OA\Get(
     * path="/api/v1/location",
     * summary="Listar los barrios",
     * description="Listar los barrios (con Token)",
     * operationId="LocationsGet",
     * tags={"Barrios"},
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

    public function show(){

        $locations = Location::all();

        return response()->json($locations, 200);
    }

    /**
     * @OA\Post(
     * path="/api/v1/location",
     * summary="Crear un Barrio",
     * description="Crear un Barrio (con Token)",
     * operationId="LocationsCreate",
     * tags={"Barrios"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del Barrio",
     *    @OA\JsonContent(
     *       required={"name"},
     *       @OA\Property(property="name", type="string", format="text", example="La arboleda"),
     *       @OA\Property(property="altitude", type="double", format="number", example=13.22132),
     *       @OA\Property(property="latitude", type="double", format="number", example=13.22132),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Created",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="Location successfully registered"),
     *        @OA\Property(
     *           property="objLocation",
     *           type="object",
     *          @OA\Property(property="name", type="string", format="text", example="La arboleda"),
     *          @OA\Property(property="altitude", type="double", format="number", example=13.22132),
     *          @OA\Property(property="latitude", type="double", format="number", example=13.22132),
     *          @OA\Property(property="updated_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="created_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="id", type="int", format="number", example=2),
     *        )
     *     )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="The name field is required."),
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }

        try {
            $obj = $request->all();
            $location = new Location;
            foreach ($obj as $key => $value) {
                $location->$key = $value;
            }
            $location->save();

        } catch (\Exception $th) {
            return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
        }

        return response()->json($location, 201);
    }

    /**
     * @OA\Put(
     * path="/api/v1/location",
     * summary="Modificar un Barrio",
     * description="Modificar un Barrio (con Token)",
     * operationId="LocationsUpdate",
     * tags={"Barrios"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del Barrio",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="int", format="number", example=1),
     *       @OA\Property(property="name", type="string", format="text", example="La arboleda"),
     *       @OA\Property(property="altitude", type="double", format="number", example=13.22132),
     *       @OA\Property(property="latitude", type="double", format="number", example=13.22132),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ok",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="Location successfully updated"),
     *        @OA\Property(
     *           property="objLocation",
     *           type="object",
     *          @OA\Property(property="name", type="string", format="text", example="La arboleda"),
     *          @OA\Property(property="altitude", type="double", format="number", example=13.22132),
     *          @OA\Property(property="latitude", type="double", format="number", example=13.22132),
     *          @OA\Property(property="updated_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="created_at", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="id", type="int", format="number", example=2),
     *        )
     *     )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="id", type="string", example="The id field is required."),
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'     => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }

        try {
            $obj = $request->all();
            $location = Location::find($request->id);
            if ($location) {
                foreach ($obj as $key => $value)
                {
                    if($value != "id"){

                        $location->$key = $value;
                    }
                }
                $location->save();

                return response()->json($location, 200);

            } else {
                return response()->json(['type' => 'data' , 'error' => 'id '.$request->id.' location not exists'], 422);

            }
        } catch (\Exception $th) {
            return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
        }
    }


    /**
     * @OA\Get(
     * path="/api/v1/location/state",
     * summary="Listar las provincias",
     * description="Listar las provincias (con Token)",
     * operationId="EstateGet",
     * tags={"Provincias"},
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

    public function estateArg(){

        $state = $this->http->get('https://apis.datos.gob.ar/georef/api/provincias');

        try {

            $state = json_decode($state->getBody());

            return response()->json($state, 200);

        }catch (\Exception $e) {

            return response()->json(['type' => 'http' , 'error' =>json_encode($e->getMessage())],422);

        }

    }


    /**
     * @OA\Get(
     * path="/api/v1/location/district/{state_id}",
     * summary="Listar de localidades",
     * description="Listar de localidades (con Token)",
     * operationId="DistrictGet",
     * tags={"Localidades"},
     * @OA\Parameter(
     *         description="ID de la Provincia",
     *         in="path",
     *         name="state_id",
     *         required=true,
     *         @OA\Schema(
     *             format="int64",
     *             type="integer"
     *         )
     *      ),
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

    public function districtArg($state_id = null){

        if (is_null($state_id)) {
            return response()->json(['type' => 'data' , 'error' => "EL Id es requerido"], 422);
        }

        try {

            $district = $this->http->get("https://apis.datos.gob.ar/georef/api/municipios?provincia=$state_id&max=5000");

            $district = json_decode($district->getBody());

            return response()->json($district, 200);

        }catch (\Exception $e) {

            return response()->json(['type' => 'http' , 'error' =>json_encode($e->getMessage())],422);

        }

    }



    /**
     * @OA\Get(
     * path="/api/v1/location/lot/{location_id}",
     * summary="Listar lotes en el Barrio",
     * description="Listar lotes en el Barrio (con Token)",
     * operationId="LotsGet",
     * tags={"Lotes"},
     * @OA\Parameter(
     *         description="ID del barrio",
     *         in="path",
     *         name="location_id",
     *         required=true,
     *         @OA\Schema(
     *             format="int64",
     *             type="integer"
     *         )
     *      ),
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

    public function locationLot($location_id){

        if (is_null($location_id)) {
            return response()->json(['type' => 'data' , 'error' => "EL Id es requerido"], 422);
        }

        $lot = DB::table("locations")
        ->leftJoin("lot_users","lot_users.fk_location_id","=","locations.id")
        ->where("locations.id",$location_id)
        ->groupBy("lot_users.id")
        ->get([
            "lot_users.*"
            // "lot_users.id as lote_id",
            // "lot_users.name as nombre",
            // "lot_users.created_at as creado",
            // "locations.id as barrio_id"
        ]);

        return response()->json($lot, 200);
    }

    /**
     * @OA\Get(
     * path="/api/v1/location/lotAll",
     * summary="Listar todos los lotes",
     * description="Listar todos los lotes (con Token)",
     * operationId="LotAllsGet",
     * tags={"Lotes"},
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

    public function allLocationLot(){

        $lot = DB::table("locations")
        ->leftJoin("lot_users","lot_users.fk_location_id","=","locations.id")
        ->groupBy("lot_users.id")
        ->get([
            "lot_users.id as lote_id",
            "lot_users.name as nombre",
            "lot_users.created_at as creado",
            "locations.id as barrio_id"
        ]);


        $lot = (object)[
            "ok" => true,
            "unidades" => [
                (object)[
                    "habilitado" => true,
                    "lote" => 9,
                    "licencia" => 141,
                    "direccion" => "Diego de Villarroel 1359",
                    "cliente" => (object)[
                        "habilitado" => true,
                        "_id" => "5fb826ff42da0c27280b49bc",
                        "nombre" => "Nicholasaaaa"
                    ],
                    "vecino" => (object)[
                        "_id" => "5f778c79d62dd3048093011f",
                        "nombre" => "test3",
                        "apellido" => "test3",
                        "telefono" => 3816234567
                    ],
                    "estado" => "vivienda",
                    "latitud" => "225134",
                    "longitud" => "3533312",
                    "alta" => "2021-01-06T18:09:51.091Z",
                    "padron" => 313559,
                    "unid" => "5ff5fcef2da2220f48af2fa3"
                ],
                (object)[
                    "habilitado" => true,
                    "lote" => 7,
                    "licencia" => 143,
                    "direccion" => "Diego de Villarroel 1349",
                    "cliente" => (object)[
                        "habilitado" => true,
                        "_id" => "5fb826ff42da0c27280b49bc",
                        "nombre" => "Nicholasaaaa"
                    ],
                    "vecino" => (object)[
                        "_id" => "5f778c79d62dd3048093011f",
                        "nombre" => "test3",
                        "apellido" => "test3",
                        "telefono" => 3816234567
                    ],
                    "estado" => "lote vacio",
                    "latitud" => "225104",
                    "longitud" => "3533122",
                    "alta" => "2021-01-06T19:02:40.825Z",
                    "padron" => 313555,
                    "unid" => "5ff60950d60a5c2260dcbe0e"
                ],
                (object)[
                    "habilitado" => true,
                    "lote" => 8,
                    "licencia" => 166,
                    "direccion" => "Diego de Villarroel 1444",
                    "cliente" => (object)[
                        "habilitado" => true,
                        "_id" => "5fb826ff42da0c27280b49bc",
                        "nombre" => "Nicholasaaaa"
                    ],
                    "estado" => "lote vacio",
                    "latitud" => "2251034",
                    "longitud" => "35331522",
                    "alta" => "2021-01-18T19:17:55.458Z",
                    "padron" => 313655,
                    "unid" => "6005dee37262841d803d449a"
                ]
            ],
            "total" => 3
        ];

        return response()->json($lot, 200);
    }

    /**
     * @OA\Post(
     * path="/api/v1/location/lotCreate",
     * summary="Crear un lote",
     * description="Crear un lote (con Token)",
     * operationId="LotCreate",
     * tags={"Lotes"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del Lote",
     *    @OA\JsonContent(
     *       required={"location_id","user_id","name","state","situation"},
     *       @OA\Property(property="location_id",description="Id del barrio", type="string",format="text", example="1"),
     *       @OA\Property(property="user_id", type="string",description="Id del usuario dueño del lote" , format="text", example="1"),
     *       @OA\Property(property="license", type="string",description="licencia" ,  format="text", example="12321"),
     *       @OA\Property(property="address", type="string",description="direccion" , format="text", example=" av siempre viva"),
     *       @OA\Property(property="allow", type="string",description="booleanos de si esta habilitado o no" , format="text", example="1"),
     *       @OA\Property(property="latitude", type="string", format="text", example="72.12321321"),
     *       @OA\Property(property="longitude", type="string", format="text", example="54.21321321"),
     *       @OA\Property(property="census", type="string",description="padron" , format="text", example="23"),
     *       @OA\Property(property="lot", type="string", format="text",description="n° lote" , example="123"),
     *       @OA\Property(property="state", type="string", format="text",description="estado solo permite vivienda, lote vacio , obra" , example="obra"),
     *       @OA\Property(property="situation", type="string" ,description="situacion solo permite propietario o inquilino" ,  format="text", example="inquilino"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Created",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="Location successfully registered"),
     *        @OA\Property(
     *           property="objLocation",
     *           type="object",
     *           @OA\Property(property="obj", type="string", example="array()"),
     *        )
     *     )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="string", example="Unauthorized")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Unprocessable Entity",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="The name field is required."),
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */


    public function createLot(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'location_id'     => 'required|string',
            'user_id'     => 'required|string',
            'name'     => 'required|string',
            'state' => [
                'required',
                Rule::in(['vivienda', 'obra', 'lote vacio']),
            ],
            'situation' => [
                'required',
                Rule::in(['propietario', 'inquilino']),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }

        try {
            $obj = $request->all();
            $location = new Location;
            foreach ($obj as $key => $value) {

                if($key == "location_id"){

                    $location->fk_location_id = $value;

                }elseif ($key == "user_id") {

                    $location->fk_user_id = $value;

                }else{

                    $location->$key = $value;
                }
            }
            $location->save();

        } catch (\Exception $th) {
            return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
        }

        return response()->json($location, 201);
    }
}
