<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_user_id',
        'fk_type_message_id',
        'fk_location_id',
        'fk_user_id',
        'subject',
        'id',
        'body',
        ];
}
