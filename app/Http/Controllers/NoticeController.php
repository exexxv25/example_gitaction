<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoticeController extends Controller
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
     * path="/api/v1/news",
     * summary="Generar una Novedad",
     * description="Generar una Novedad (con Token)",
     * operationId="newsCreate",
     * tags={"Novedades"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos de la Novedad",
     *    @OA\JsonContent(
     *       required={"user_id","location_id","expired","tittle","body"},
     *       @OA\Property(property="user_id", type="int", format="number", example=1),
     *       @OA\Property(property="location_id", type="int", format="number", example=1),
     *       @OA\Property(property="expired", type="string", format="datetime", example="2021-01-18T15:31:11.000000Z"),
     *       @OA\Property(property="tittle", type="string", format="text", example="Nuevo gimnasio"),
     *       @OA\Property(property="body", type="string", format="text", example="Se habilito un nuevo ginmasio"),
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
     *          @OA\Property(property="tittle", type="string", format="text", example="Nuevo gimnasio"),
     *          @OA\Property(property="body", type="string", format="text", example="Se habilito un nuevo ginmasio"),
     *          @OA\Property(property="expired", type="string", format="date", example="2021-01-18T15:31:11.000000Z"),
     *          @OA\Property(property="location_id", type="integer", format="number", example=1),
     *          @OA\Property(property="user_id", type="integer", format="number", example=1),
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
     *       @OA\Property(property="user_id", type="number", example="The user_id field is required."),
     *       @OA\Property(property="location_id", type="number", example="The location_id field is required."),
     *       @OA\Property(property="name", type="string", example="The name field is required."),
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
            'expired'  => 'required|string',
            'tittle'  => 'required|string',
            'body'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }

        // dd($request->all());

        $notice = Notice::create([
            'fk_user_id' => $request->user_id,
            'fk_location_id' => $request->location_id,
            'expired' => $request->expired,
            'tittle' => $request->tittle,
            'body' => $request->body
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
            //          $fileStore->file_id = $notice->id;
            //          $fileStore->name = $uploadedFile->getClientOriginalName();
            //          $fileStore->path = $path;
            //          $fileStore->save();
            //   }

        return response()->json($notice, 201);
    }

    /**
     * @OA\Put(
     * path="/api/v1/news",
     * summary="Modificar una Novedad",
     * description="Modificar una Novedad (con Token)",
     * operationId="newsUpdate",
     * tags={"Novedades"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del Novedad",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="int", format="number", example=1),
     *       @OA\Property(property="tittle", type="string", format="text", example="Se habilito una nueva seccion del gimnacio X"),
     *       @OA\Property(property="body", type="string", format="text", example="Nuevo gimnasio"),
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
     *          @OA\Property(property="tittle", type="string", format="text", example="Nuevo gimnasio"),
     *          @OA\Property(property="body", type="string", format="text", example="Se habilito una nueva seccion del gimnacio X"),
     *          @OA\Property(property="expired", type="string", format="date", example="2021-01-18"),
     *          @OA\Property(property="location_id", type="integer", format="number", example=1),
     *          @OA\Property(property="user_id", type="integer", format="number", example=1),
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
            $notice = Notice::find($request->id);
            if ($notice) {
                foreach ($obj as $key => $value)
                {
                    if($value != "id"){

                        $notice->$key = $value;

                    }
                }
                $notice->save();

                return response()->json($notice, 200);

            } else {
                return response()->json(['type' => 'data' , 'error' => 'id '.$request->id.' notice not exists'], 422);

            }
        } catch (\Exception $th) {
            return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
        }

    }

    /**
     * @OA\Get(
     * path="/api/v1/news",
     * summary="Listar las Novedades",
     * description="Listar las Novedades (con Token)",
     * operationId="newsGet",
     * tags={"Novedades"},
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

        $notices = Notice::leftJoin('file_stores','file_stores.id','=','notices.fk_file_store_id')
        ->get([
            'notices.*',
        ]);

        return response()->json($notices, 200);
    }
}
