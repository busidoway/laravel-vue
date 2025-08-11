<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'date_start',
        'date_end',
        'price',
        'active'
    ];
}
