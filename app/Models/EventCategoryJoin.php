<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategoryJoin extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'event_category_id'];
}
