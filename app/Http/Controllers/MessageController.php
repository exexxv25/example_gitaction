<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\TypeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MessageController extends Controller
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
     * path="/api/v1/message",
     * summary="Generar un ticket",
     * description="Generar un ticket (con Token)",
     * operationId="TicketsCreate",
     * tags={"Tickets"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del Ticket",
     *    @OA\JsonContent(
     *       required={"user_id","type_id","location_id","subject","body"},
     *       @OA\Property(property="user_id", type="int", format="number", example=1),
     *       @OA\Property(property="type_id", type="int", format="number", example=1),
     *       @OA\Property(property="location_id", type="int", format="number", example=1),
     *       @OA\Property(property="subject", type="string", format="text", example="Alerta Covid"),
     *       @OA\Property(property="body", type="string", format="text", example="Mi vecino tiene covid"),
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
     *          @OA\Property(property="body", type="string", format="text", example="Mi vecino tiene covid"),
     *          @OA\Property(property="subject", type="string", format="text", example="Alerta Covid"),
     *          @OA\Property(property="location_id", type="string", format="text", example=1),
     *          @OA\Property(property="type_id", type="string", format="text", example=1),
     *          @OA\Property(property="user_id", type="string", format="text", example=1),
     *          @OA\Property(property="opened", type="boolean", format="number", example=1),
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
     *       @OA\Property(property="type_id", type="number", example="The type_id field is required."),
     *       @OA\Property(property="location_id", type="number", example="The location_id field is required."),
     *       @OA\Property(property="subject", type="string", example="The subject field is required."),
     *       @OA\Property(property="body", type="string", example="The body field is required."),
     *        )
     *     ),
     *  security={{ "apiAuth": {} }}
     * )
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|int',
            'type_id'     => 'required|int',
            'location_id' => 'required|int',
            'subject'  => 'required|string',
            'body'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }


        $message = Message::create([
            'fk_user_id' => $request->user_id,
            'fk_type_message_id' => $request->type_id,
            'fk_location_id' => $request->location_id,
            'subject' => $request->subject,
            'body' => $request->body
        ]);

        return response()->json($message, 201);
    }

    /**
     * @OA\Put(
     * path="/api/v1/message",
     * summary="Modificar un ticket",
     * description="Modificar un ticket (con Token)",
     * operationId="TicketsUpdate",
     * tags={"Tickets"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos del Ticket",
     *    @OA\JsonContent(
     *       required={"id"},
     *       @OA\Property(property="id", type="int", format="number", example=1),
     *       @OA\Property(property="user_id", type="int", format="number", example=1),
     *       @OA\Property(property="type_id", type="int", format="number", example=1),
     *       @OA\Property(property="location_id", type="int", format="number", example=1),
     *       @OA\Property(property="opened", type="boolean", format="number", example=1),
     *       @OA\Property(property="subject", type="string", format="text", example="Alerta Covid"),
     *       @OA\Property(property="body", type="string", format="text", example="Mi vecino tiene covid"),
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
     *          @OA\Property(property="body", type="string", format="text", example="Mi vecino tiene covid"),
     *          @OA\Property(property="subject", type="string", format="text", example="Alerta Covid"),
     *          @OA\Property(property="location_id", type="string", format="text", example=1),
     *          @OA\Property(property="type_id", type="string", format="text", example=1),
     *          @OA\Property(property="user_id", type="string", format="text", example=1),
     *          @OA\Property(property="opened", type="boolean", format="number", example=1),
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
            $message = Message::find($request->id);
            if ($message) {
                foreach ($obj as $key => $value)
                {
                    if($value != "id"){

                        $message->$key = $value;

                    }
                }
                $message->save();

                return response()->json($message, 200);

            } else {
                return response()->json(['type' => 'data' , 'error' => 'id '.$request->id.' message not exists'], 422);

            }
        } catch (\Exception $th) {
            return response()->json(['type' => 'sql' , 'error' => $th->getMessage()], 422);
        }

    }

    /**
     * @OA\Get(
     * path="/api/v1/message",
     * summary="Listar los tickets",
     * description="Listar los tickets (con Token)",
     * operationId="TicketsGet",
     * tags={"Tickets"},
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

        $messages = Message::leftJoin('users','users.id','=','messages.fk_user_id')
        ->leftJoin('type_messages','type_messages.id','=','messages.fk_type_message_id')
        ->leftJoin('locations','locations.id','=','messages.fk_location_id')
        ->where('messages.opened',1)
        ->get();

        return response()->json($messages, 200);
    }

    /**
     * @OA\Get(
     * path="/api/v1/message/type",
     * summary="Listar los tipos de tickets",
     * description="Listar los tipos de tickets (con Token)",
     * operationId="TicketsGetHistory",
     * tags={"Tickets"},
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

    public function type(){

        $typeMessages = TypeMessage::all();

        return response()->json($typeMessages, 200);
    }

}
