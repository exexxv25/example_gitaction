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

                if(!isset($messageHistory[0]["chatClass"])){

                    $messageHistory[0]["chatClass"] = "odd";
                    // $messageHistory[0]["nombre"] = auth()->user()->name;
                    // $messageHistory[0]["apellido"] = auth()->user()->lastname;
                    $messageHistory[0]["imagePath"] = auth()->user()->avatar;
                    $messageHistory[0]["time"] = date_format($value->updated_at , 'h:ia');

                }

                if(!isset($messageHistory[0]["messageResponse"])){

                    $messageHistory[0]["messageResponse"] = [];
                }

                array_push($messageHistory[0]["messageResponse"],$value->body);

            }else{
                if(!isset($messageHistory[1]["chatClass"])){

                    $userEven = User::find($value->fk_user_id);

                    $messageHistory[1]["chatClass"] = "even";
                    // $messageHistory[1]["nombre"] = $userEven->name;
                    // $messageHistory[1]["apellido"] = $userEven->lastname;
                    $messageHistory[1]["imagePath"] = $userEven->avatar;
                    $messageHistory[1]["time"] = date_format($value->updated_at , 'h:ia');
                }

                if(!isset($messageHistory[1]["messageResponse"])){

                    $messageHistory[1]["messageResponse"] = [];
                }

                array_push($messageHistory[1]["messageResponse"],$value->body);

            }
        };

        return $messageHistory;

    }
}
