<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        "event_id",
        "app_id"
        ];
}
