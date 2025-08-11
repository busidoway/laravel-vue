<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSubCategoryEventJoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'event_sub_category_id',
    ];
}
