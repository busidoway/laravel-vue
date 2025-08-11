<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramsGroupsJoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'programs_group_id',
        'program_id'
    ];
}
