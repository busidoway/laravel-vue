<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReestr extends Model
{
    use HasFactory;

    protected $fillable = [
        'reestr_id',
        'name',
        'year',
        'status'
    ];
}
