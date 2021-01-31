<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
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
     * path="/api/v1/document",
     * summary="Generar un Documento",
     * description="Generar un Documento (con Token)",
     * operationId="DocumentsCreate",
     * tags={"Documents"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del Documento",
     *    @OA\JsonContent(
     *       required={"user_id","location_id","name"},
     *       @OA\Property(property="user_id", type="int", format="number", example=1),
     *       @OA\Property(property="location_id", type="int", format="number", example=1),
     *       @OA\Property(property="name", type="string", format="text", example="Reglamento vecinos"),
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
     *          @OA\Property(property="name", type="string", format="text", example="Reglamento vecinos"),
     *          @OA\Property(property="location_id", type="string", format="text", example=1),
     *          @OA\Property(property="user_id", type="string", format="text", example=1),
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
            'name'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }


        $document = Document::create([
            'fk_user_id' => $request->user_id,
            'fk_location_id' => $request->location_id,
            'name' => $request->name
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
            //          $fileStore->file_id = $document->id;
            //          $fileStore->name = $uploadedFile->getClientOriginalName();
            //          $fileStore->path = $path;
            //          $fileStore->save();
            //   }

        return response()->json($document, 201);
    }

    /**
     * @OA\Put(
     * path="/api/v1/document",
     * summary="Modificar un Documento",
     * description="Modificar un Documento (con Token)",
     * operationId="DocumentsUpdate",
     * tags={"Documents"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del Documento",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="int", format="number", example=1),
     *       @OA\Property(property="body", type="string", format="text", example="Reglamento vecinosV2"),
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
     *          @OA\Property(property="name", type="string", format="text", example="Reglamento vecinosV2"),
     *          @OA\Property(property="location_id", type="string", format="text", example=1),
     *          @OA\Property(property="user_id", type="string", format="text", example=1),
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
            $document = Document::find($request->id);
            if ($document) {
                foreach ($obj as $key => $value)
                {
                    if($value != "id"){

                        $document->$key = $value;

                    }
                }
                $document->save();

                return response()->json($document, 200);

            } else {
                return response()->json(['type' => 'data' , 'error' => 'id '.$request->id.' document not exists'], 422);

            }
        } catch (\Exception $th) {
            return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
        }

    }

    /**
     * @OA\Get(
     * path="/api/v1/document",
     * summary="Listar los documentos",
     * description="Listar los documentos (con Token)",
     * operationId="DocumentsGet",
     * tags={"Documents"},
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

        $document = Document::leftJoin('file_stores','file_stores.id','=','documents.fk_file_store_id')
        ->get();

        return response()->json($document, 200);
    }

}
