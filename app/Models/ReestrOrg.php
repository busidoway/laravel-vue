<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReestrOrg extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_org',
        'name_org_full',
        'name_org_short',
        'subdiv',
        'num_cert',
        'city',
        'region',
        'date_start',
        'date_end',
        'manager',
        'website',
        'phone',
        'ur_address',
        'address',
        'email',
        'boss',
        'program'
    ];
}
