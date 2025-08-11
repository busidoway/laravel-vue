<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'description',
        'logo',
        'name_short',
        'name_full',
        'name_filter',
        'subdiv',
        'num_cert',
        'date_start',
        'date_end',
        'manager',
        'website',
        'phone',
        'address',
        'boss',
        'program',
        'hidden_program',
        'hidden_reestr',
        'hidden_more'
    ];
}
