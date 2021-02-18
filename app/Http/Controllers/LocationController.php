<?php

namespace App\Http\Controllers;

use Validator;
use GuzzleHttp\Client;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * operationId="LocationLotsGet",
     * tags={"Barrios"},
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
            "lot_users.id as lote_id",
            "lot_users.name as nombre",
            "lot_users.created_at as creado",
            "locations.id as barrio_id"
        ]);

        return response()->json($lot, 200);

    }
}
