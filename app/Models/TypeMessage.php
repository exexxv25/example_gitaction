<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'description'
        ];
}
