<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSubCategoryJoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_sub_category_id',
        'event_category_id'
    ];
}
