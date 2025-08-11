<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        "name_sender",
        "middle_name_sender",
        "last_name_sender",
        "email_sender",
        "phone_sender",
        "text",
        "date_send",
        "status"
    ];
}
