<?php

namespace App\Models;

use DateTime;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageHistory extends Model
{
    use HasFactory;

    protected $guarded = [];


    public static function dataEs($messageId = null){

        $data = Message::leftJoin('message_histories','messages.id','=','message_histories.fk_message_id')
        ->leftJoin('users','users.id','=','message_histories.fk_user_id')
        ->leftJoin('type_messages','type_messages.id','=','messages.fk_type_message_id')
        ->leftJoin('locations','locations.id','=','messages.fk_location_id')
        ->where('messages.id',$messageId)
        ->get([
                'messages.subject as subjectmsg',
                'messages.body as bodymsg',
                'messages.id as idmsg',
                'messages.created_at as createdmsg',
                'message_histories.*'
            ]);

        $messageHistory = (object)[];
        $x=true;
        foreach ($data as $key => $value) {

            if($x){
                $messageHistory->id = $value->idmsg;
                $messageHistory->signature = $value->subjectmsg;
                $messageHistory->name = auth()->user()->name;
                $messageHistory->imagePath = auth()->user()->avatar;
                $messageHistory->time = date_format( new DateTime($value->createdmsg),'h:ia');
                $x=false;
            }

            if($value->fk_user_id == auth()->user()->id){

                if(!isset($messageHistory->chats[0]["chatClass"])){

                    $messageHistory->chats[0]["chatClass"] = "odd";
                    // $messageHistory->chats[0]["nombre"] = auth()->user()->name;
                    // $messageHistory->chats[0]["apellido"] = auth()->user()->lastname;
                    $messageHistory->chats[0]["imagePath"] = auth()->user()->avatar;
                    $messageHistory->chats[0]["time"] = date_format($value->updated_at , 'h:ia');

                }

                if(!isset($messageHistory->chats[0]["messageResponse"])){

                    $messageHistory->chats[0]["messageResponse"] = [];
                }

                array_push($messageHistory->chats[0]["messageResponse"],$value->body);

            }else{
                if(!isset($messageHistory->chats[1]["chatClass"])){

                    $userEven = User::find($value->fk_user_id);

                    $isNull = is_null($userEven);

                    if(!is_null($userEven)){

                        $messageHistory->chats[1]["chatClass"] = "even";
                        // $messageHistory->chats[1]["nombre"] = $userEven->name;
                        // $messageHistory->chats[1]["apellido"] = $userEven->lastname;
                        $messageHistory->chats[1]["imagePath"] = ($isNull)? null : $userEven->avatar;
                        $messageHistory->chats[1]["time"] = ($isNull)? null : date_format($value->updated_at , 'h:ia');

                    }

                }
                if(!is_null($value->fk_user_id)){

                    if(!isset($messageHistory->chats[1]["messageResponse"]) ){

                        $messageHistory->chats[1]["messageResponse"] = [];
                    }

                    array_push($messageHistory->chats[1]["messageResponse"],$value->body);

                }else{

                    $messageHistory->chats = null;
                }

            }
        };

        return $messageHistory;

    }
}
