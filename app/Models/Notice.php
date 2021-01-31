<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_user_id',
        'fk_location_id',
        'tittle',
        'expired',
        'body',
        ];
}
