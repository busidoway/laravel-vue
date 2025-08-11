<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEventPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_event_id',
        'date_start',
        'date_end',
        'price',
        'active'
    ];
}
