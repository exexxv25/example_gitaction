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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            $validator = Validator::make($request->all(), [
                'user_id'     => 'required|int',
                'amenities_id' => 'required|int',
                'date' => 'required|date',
                'amenities_date'  => 'required|int'
            ]);

            if ($validator->fails()) {
                return response()->json(['type' => 'data' , 'error' => $validator->errors()], 422);
            }

            $notification = AmenitiesReservation::create([
                'fk_user_id' => $request->user_id,
                'fk_amenities_id' => $request->amenities_id,
                'fk_amenities_date' => $request->type_amenities_id,
                'date' => $request->date,
                'opened' => 1
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
     * @OA\Get(
     * path="/api/v1/amenitie/reservacion/{amenities_id}",
     * summary="Listar de reservas de Amenities (mes)",
     * description="Listar de reservas de Amenities (con Token)",
     * operationId="ReserveAmenitiesGet",
     * tags={"Amenities"},
     * @OA\Parameter(
     *         description="ID del Amenitie",
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AmenitiesDate  $amenitiesDate
     * @return \Illuminate\Http\Response
     */
    public function show(AmenitiesReservation $amenitiesReservation)
    {

        $toDay = date('Y-m-d');
        $days = [];
        $turns = AmenitiesDate::all();

        $reserve = AmenitiesReservation::leftJoin("amenities_dates","amenities_dates.id","=","amenities_reservations.fk_amenities_date")
        ->where("amenities_reservations.date", ">=" , $toDay)
        ->where("amenities_reservations.opened",1)
        ->where("amenities_reservations.fk_amenities_id",$amenitiesReservation)
        ->get();

        foreach ($reserve as $key => $value) {

            if(!isset($days[$toDay])){

                $$days[$toDay] = [];
            }

                for ($f=0; $f < $turns->count(); $f++) {

                    if($value->fk_amenities_date == $turns[$f]->id){

                        array_push($$days[$toDay],[
                            (object)[
                                "franja" => $turns[$f]->init." ".$turns[$f]->expired,
                                "disponible" => ($value->opened)? "si" : "no"
                            ],
                        ]);

                    }else{

                        array_push($$days[$toDay],[
                            (object)[
                                "franja" => $turns[$f]->init." ".$turns[$f]->expired,
                                "disponible" => "si"
                            ],
                        ]);

                    }
                }
                $f=0;
            }

            for ($x=0; $x < 30; $x++) {

                if(!isset($days[$toDay])){

                    $days[$toDay] = [];

                    for ($i=0; $i < $turns->count(); $i++) {

                        array_push($days[$toDay],[
                                (object)[
                                    "franja" => $turns[$i]->init." ".$turns[$i]->expired,
                                    "disponible" => "si"
                                ],
                            ]);

                        }
                }
                $i=0;
                $toDay = strtotime($toDay."+ 1 days");
                $toDay = date("Y-m-d",$toDay);

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
