<?php

namespace App\Http\Controllers;

use App\Models\AmenitiesDate;
use App\Models\AmenitiesReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AmenitiesReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * @OA\Post(
     * path="/api/v1/amenitie/reservation",
     * summary="Reservar un Amenitie",
     * description="Reservar un Amenitie (con Token)",
     * operationId="reservationamenitiecreate",
     * tags={"Amenities"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Datos de la reserva del amenitie",
     *    @OA\JsonContent(
     *       required={"amenities_id","date","amenities_date"},
     *       @OA\Property(property="user_id", description="opcional" , type="int", format="number", example=4),
     *       @OA\Property(property="amenities_date", type="int", format="number", example=1),
     *       @OA\Property(property="amenities_id", type="int", format="number", example=1),
     *       @OA\Property(property="date", type="date", format="date", example="2021-02-28")
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
     *          @OA\Property(property="id", type="string", format="text", example="1")
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
            'amenities_id' => 'required|int',
            'date' => 'required|date',
            'amenities_date'  => 'required|int'
        ]);

        if ($validator->fails()) {
            return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
        }

        if(AmenitiesReservation::where('fk_user_id',(isset($request->user_id))? $request->user_id : auth()->user()->id  )
        ->where('fk_amenities_id',$request->amenities_id)
        ->where('date',$request->date)
        ->where('opened',1)
        ->where('fk_amenities_date',$request->amenities_date)->exists()){

            return response()->json(['type' => 'validation' , 'error' => "la fecha y horario ya fueron reservados"], 422);

        }else{


            $reservation = AmenitiesReservation::create([
                'fk_user_id' => (isset($request->user_id))? $request->user_id : auth()->user()->id,
                'fk_amenities_id' => $request->amenities_id,
                'fk_amenities_date' => $request->amenities_date,
                'date' => $request->date,
                'opened' => 1
            ]);

        }

        return response()->json($reservation, 201);
    }


    /**
     * @OA\Get(
     * path="/api/v1/amenitie/reservation/{amenities_id}",
     * summary="Listar de reservas de Amenities (mes)",
     * description="Listar de reservas de Amenities (con Token)",
     * operationId="ReserveAmenitiesGet",
     * tags={"Amenities"},
     * @OA\Parameter(
     *         description="ID del Amenitie",
     *         in="path",
     *         name="amenities_id",
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
     * @param  \App\Models\AmenitiesDate  $amenitiesDate
     * @return \Illuminate\Http\Response
     */
    public function show($reservation)
    {

        $toDay = date('Y-m-d');
        $days = [];
        $turns = AmenitiesDate::all();

        $reserve = AmenitiesReservation::leftJoin("amenities_dates","amenities_dates.id","=","amenities_reservations.fk_amenities_date")
        ->where("amenities_reservations.date", ">=" , $toDay)
        ->where("amenities_reservations.opened",1)
        ->whereNotNull("amenities_reservations.fk_amenities_date")
        ->where("amenities_reservations.fk_amenities_id",$reservation)
        ->get();

        for ($x=0; $x < 30; $x++) {

            if(!isset($days[$toDay])){

                $days[$toDay] = [];

                for ($i=0; $i < $turns->count(); $i++) {

                    array_push($days[$toDay],
                            (object)[
                                "id_franja" => $turns[$i]->id,
                                "franja" => $turns[$i]->init." ".$turns[$i]->expired,
                                "disponible" => "si"
                            ],
                        );

                    }
            }
            $i=0;
            $toDay = strtotime($toDay."+ 1 days");
            $toDay = date("Y-m-d",$toDay);

        }
        $xxx = 0;
        foreach ($reserve as $key => $value) {

            foreach ($days[$value->date] as $keysx => $values) {

                for ($f=0; $f < $turns->count(); $f++) {

                    if($days[$value->date][$keysx]->id_franja == $turns[$f]->id ){

                        if($xxx == 0){
                            $days[$value->date][$keysx] = (object)[
                                "id_franja" => $turns[$f]->id,
                                "franja" => $turns[$f]->init." ".$turns[$f]->expired,
                                "disponible" => ($value->opened)? "no" : "si"
                            ];
                        }
                        $xxx++;
                    }else{

                        $xxx=0;
                    }
                }
                $f=0;
            }
        }

            return response()->json($days, 200);
        }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AmenitiesReservation  $amenitiesReservation
     * @return \Illuminate\Http\Response
     */
    public function edit(AmenitiesReservation $amenitiesReservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AmenitiesReservation  $amenitiesReservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AmenitiesReservation $amenitiesReservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AmenitiesReservation  $amenitiesReservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(AmenitiesReservation $amenitiesReservation)
    {
        //
    }
}
