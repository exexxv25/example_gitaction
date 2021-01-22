<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Validator;

class LocationController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');
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

}
