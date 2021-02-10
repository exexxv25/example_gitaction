<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Models\MessageHistory;

class MessageHistoryController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Post(
     * path="/api/v1/history/message/response",
     * summary="Responder un ticker",
     * description="Responder un ticker (con Token)",
     * operationId="TicketsHisCreate",
     * tags={"Tickets - Mensajes"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos para responder un Ticket",
     *    @OA\JsonContent(
     *       required={"fk_message_id","fk_user_id"},
     *       @OA\Property(property="fk_user_id", type="int", format="number", example=1),
     *       @OA\Property(property="fk_message_id", type="int", format="number", example=1),
     *       @OA\Property(property="body", type="string", format="text", example="Mi vecino sigue con covid"),
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
     *          @OA\Property(property="body", type="string", format="text", example="Mi vecino sigue con covid"),
     *          @OA\Property(property="message_id", type="string", format="text", example=1),
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
     *       @OA\Property(property="message_id", type="number", example="The message_id field is required."),
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'fk_message_id'     => 'required|int',
            'fk_user_id'     => 'required|int',
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }

        try {
            $obj = $request->all();
            $MessageHistory = new MessageHistory();
            foreach ($obj as $key => $value) {

                    $MessageHistory->$key = $value;

            }
            $MessageHistory->save();

            return response()->json($MessageHistory, 201);
            //falta guardar archivo si es que se envia
                    // //imagenes storage
            // $img   = $request->file('file');
            // $extention = strtolower($img->getClientOriginalExtension());
            // $filename  = strtolower(str_replace(" ","_","named")).'.'.$extention;
            // Storage::disk('data')->put($filename,  File::get($img));
            //     foreach($request->file('files') as $uploadedFile){
            //         $filename = time() . '_' . $uploadedFile->getClientOriginalName();
            //          $path = $uploadedFile->store($filename, 'uploads');
            //          $messagehistoryfilestore = new messagehistoryfilestore();
            //          $messagehistoryfilestore->message_id = $message_id->id;
            //          $messagehistoryfilestore->name = $uploadedFile->getClientOriginalName();
            //          $messagehistoryfilestore->path = $path;
            //          $messagehistoryfilestore->save();
            //   }

        } catch (\Exception $th) {
            return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
        }

        return response()->json($MessageHistory, 201);

    }

    /**
     * @OA\Get(
     * path="/api/v1/history/message/all/{id}",
     * summary="Historial de respuestas de un ticker",
     * description="Historial de respuestas de un ticker (con Token)",
     * operationId="TicketsHisGet",
     * tags={"Tickets - Mensajes"},
     * @OA\Parameter(
     *         description="ID del mensaje",
     *         in="path",
     *         name="id",
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $MessageHistory = MessageHistory::dataEs($id);

        return response()->json($MessageHistory, 200);
    }

    /**
     * @OA\Put(
     * path="/api/v1/history/message/edit",
     * summary="Modificar una respuesta a un ticker",
     * description="Modificar una respuesta a un ticker (con Token)",
     * operationId="TicketsHisUpdate",
     * tags={"Tickets - Mensajes"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos para editar una respuesta a un Ticket",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="body", type="string", format="text", example="Mi vecino sigue con covid V2"),
     *       @OA\Property(property="id", type="int", format="number", example=1),
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
     *          @OA\Property(property="body", type="string", format="text", example="Mi vecino sigue con covid V2"),
     *          @OA\Property(property="message_id", type="string", format="text", example=1),
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
     *       @OA\Property(property="message_history_id", type="number", example="The message_history_id field is required."),
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
            $MessageHistory = MessageHistory::find($request->id);
            if ($MessageHistory) {
                foreach ($obj as $key => $value)
                {
                    if($value != "id"){

                        $MessageHistory->$key = $value;
                    }
                }
                $MessageHistory->save();

                return response()->json($MessageHistory, 200);

            } else {
                return response()->json(['type' => 'data' , 'error' => 'id '.$request->id.' historuy_message not exists'], 422);

            }
        } catch (\Exception $th) {
            return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
        }

    }

}
