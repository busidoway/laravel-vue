<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramTypeProgramJoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_program_id',
        'program_id',
    ];
}
