<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'reestr_id',
        'year',
        'status'
    ];
}
