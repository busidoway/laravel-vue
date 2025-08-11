<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramsEducation extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'program_id',
        'form_education_id',
        'date',
        'price',
        'duration',
        'extension'
    ];
}
