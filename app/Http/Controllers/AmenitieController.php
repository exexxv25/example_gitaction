<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\TypeAmenity;
use App\Models\TypeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AmenitieController extends Controller
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
     * @OA\Post(
     * path="/api/v1/amenitie",
     * summary="Crear un Amenitie",
     * description="Crear un Amenitie (con Token)",
     * operationId="amenitiecreate",
     * tags={"Amenities"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del amenitie",
     *    @OA\JsonContent(
     *       required={"user_id","location_id","type_amenities_id","name","lot","charge","mobile_numer"},
     *       @OA\Property(property="user_id", type="int", format="number", example=1),
     *       @OA\Property(property="location_id", type="int", format="number", example=1),
     *       @OA\Property(property="type_amenities_id", type="int", format="number", example=1),
     *       @OA\Property(property="name", type="string", format="text", example="Quincho 1"),
     *       @OA\Property(property="lot", type="string", format="text", example="268"),
     *       @OA\Property(property="charge", type="string", format="text", example="Encargado Raul"),
     *       @OA\Property(property="mobile_numer", type="string", format="text", example="898465468")
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Created",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="Message successfully registered"),
     *        @OA\Property(
     *           property="objMessage",
     *           type="object",
     *          @OA\Property(property="name", type="string", format="text", example="Quincho 1")
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
     *       @OA\Property(property="user_id", type="number", example="The user_id field is required."),
     *       @OA\Property(property="location_id", type="number", example="The location_id field is required.")
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|int',
            'location_id' => 'required|int',
            'type_amenities_id'  => 'required|int',
            'name'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }

        $notification = Amenity::create([
            'fk_user_id' => $request->user_id,
            'fk_location_id' => $request->location_id,
            'fk_type_amenities_id' => $request->type_amenities_id,
            'name' => $request->name,
            'lot' => (isset($request->lot))? $request->lot : null,
            'charge' => (isset($request->charge))? $request->charge : null,
            'mobile_number' => (isset($request->mobile_number))? $request->mobile_number : null
        ]);
            //falta guardar archivo si es que se envia
            // //imagenes storage
            // $img   = $request->file('file');
            // $extention = strtolower($img->getClientOriginalExtension());
            // $filename  = strtolower(str_replace(" ","_","named")).'.'.$extention;
            // Storage::disk('data')->put($filename,  File::get($img));
            //     foreach($request->file('files') as $uploadedFile){
            //         $filename = time() . '_' . $uploadedFile->getClientOriginalName();
            //          $path = $uploadedFile->store($filename, 'uploads');
            //          $fileStore = new FileStore();
            //          $fileStore->file_id = $notification->id;
            //          $fileStore->name = $uploadedFile->getClientOriginalName();
            //          $fileStore->path = $path;
            //          $fileStore->save();
            //   }

        return response()->json($notification, 201);
    }

    /**
     * @OA\Put(
     * path="/api/v1/amenitie",
     * summary="Modificar un Amenitie",
     * description="Modificar un Amenitie (con Token)",
     * operationId="notificationUpdate",
     * tags={"Amenities"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del Amenitie",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="int", format="number", example=1)
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ok",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="Message successfully updated"),
     *        @OA\Property(
     *           property="objMessage",
     *           type="object",
     *          @OA\Property(property="subject", type="string", format="text", example="123 ")
     *        )
     *     )
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
     *       @OA\Property(property="id", type="number", example="The id field is required."),
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
            $notification = Amenity::find($request->id);
            if ($notification) {
                foreach ($obj as $key => $value)
                {
                    if($value != "id"){

                        switch ($key) {
                            case 'location_id':
                                $notification->fk_location_id = $value;
                            break;
                            case 'type_amenities_id':
                                $notification->fk_type_amenities_id = $value;
                            break;
                            default:
                                $notification->$key = $value;
                            break;
                        }

                    }
                }
                $notification->save();

                return response()->json($notification, 200);

            } else {
                return response()->json(['type' => 'data' , 'error' => 'id '.$request->id.' amenities not exists'], 422);

            }
        } catch (\Exception $th) {
            return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
        }

    }

    /**
     * @OA\Get(
     * path="/api/v1/amenitie",
     * summary="Listar los Amenities",
     * description="Listar los Amenities (con Token)",
     * operationId="amenitieGet",
     * tags={"Amenities"},
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

        if(auth()->user()->myFirstRols() == "MASTER_ROL"){

            $amenities = Amenity::all();

        }else{

            $amenities = Amenity::whereIn("fk_location_id",auth()->user()->myLocation())->get();
        }


        $amenities = (object)[
            "ok" => true,
            "amenities" => [
                (object)[
                    "habilitado" => true,
                    "nombre" => "Quincho",
                    "sede" => (object)[
                        "habilitado" => true,
                        "_id" => "5fab078d2340a909e89ee2a0",
                        "nombre" => "Quincho"
                    ],
                    "cliente" => (object)[
                        "habilitado" => true,
                        "_id" => "5fb826ff42da0c27280b49bc",
                        "nombre" => "Nicholasaaaa"
                    ],
                    "lote" => 265,
                    "encargado" => "Carlos Juarez",
                    "telefono" => 4645823,
                    "aid" => "5ff76f48694fad07b4a7056f"
                ],
                (object)[
                    "habilitado" => true,
                    "nombre" => "cancha de tenis",
                    "sede" => null,
                    "cliente" => (object)[
                        "habilitado" => true,
                        "_id" => "5fb826ff42da0c27280b49bc",
                        "nombre" => "Nicholasaaaa"
                    ],
                    "lote" => 269,
                    "encargado" => "Julian Cerrano",
                    "telefono" => 4532345,
                    "aid" => "5ff76fe1694fad07b4a70573"
                ]
            ],
            "total" => 3
        ];

        return response()->json($amenities, 200);
    }

    /**
     * @OA\Post(
     * path="/api/v1/amenitie/type",
     * summary="Crear un tipo de Amenitie",
     * description="Crear un tipo de Amenitie (con Token)",
     * operationId="typeamenitiecreate",
     * tags={"Amenities"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del tipo de amenitie",
     *    @OA\JsonContent(
     *       required={"description"},
     *       @OA\Property(property="description", type="string", format="text", example="cancha de.."),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="Created",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="Message successfully registered"),
     *        @OA\Property(
     *           property="objMessage",
     *           type="object",
     *          @OA\Property(property="description", type="string", format="text", example="cancha de..")
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
     *       @OA\Property(property="user_id", type="number", example="The user_id field is required."),
     *       @OA\Property(property="location_id", type="number", example="The location_id field is required.")
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */

    public function type(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }

        $notification =    TypeAmenity::create([
            'description' => $request->description

        ]);

        return response()->json($notification, 201);
    }

    /**
     * @OA\Get(
     * path="/api/v1/amenitie/type",
     * summary="Listar los tipos de Amenities",
     * description="Listar los tipos de Amenities (con Token)",
     * operationId="typeamenitieGet",
     * tags={"Amenities"},
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

    public function showType()
    {
        $type = TypeAmenity::all();

        return response()->json($type, 201);
    }

}
