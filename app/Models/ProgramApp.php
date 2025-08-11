<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramApp extends Model
{
    use HasFactory;

    protected $fillable = [
        'programs_education_id',
        'application_id'
    ];

}
