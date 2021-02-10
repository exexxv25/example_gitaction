<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_user_id',
        'fk_type_message_id',
        'fk_user_id',
        'subject',
        'body',
        ];


    public static function dataEs($messageId = null){

        $data = self::leftJoin('messages','messages.id','=','message_histories.fk_message_id')
        ->leftJoin('users','users.id','=','message_histories.fk_user_id')
        ->leftJoin('type_messages','type_messages.id','=','messages.fk_type_message_id')
        ->leftJoin('locations','locations.id','=','messages.fk_location_id')
        ->where('message_histories.fk_message_id',$messageId)
        ->get([
                'message_histories.*'
            ]);

        $messageHistory = [];

        foreach ($data as $key => $value) {

            if($value->fk_user_id == auth()->user()->id){

                if(!isset($messageHistory[0]["type"])){

                    $messageHistory[0]["type"] = "odd";
                    $messageHistory[0]["avatar"] = auth()->user()->avatar;

                }

                array_push($messageHistory[0]["messageResponse"],array(
                    "mensaje" => $value->body,
                    "fecha" => $value->updated_at
                ));

            }else{

                if(!isset($messageHistory[1]["type"])){

                    $messageHistory[1]["type"] = "even";
                    $messageHistory[1]["avatar"] = User::find($value->fk_user_id)->avatar;

                }

                array_push($messageHistory[1]["messageResponse"],array(
                    "mensaje" => $value->body,
                    "fecha" => $value->updated_at
                ));

            }
        };

        return $messageHistory;

    }
}
