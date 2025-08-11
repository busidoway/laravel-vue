<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationCityJoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'city_id'
    ];
}
