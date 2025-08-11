<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'cat',
        'title',
        'subtitle',
        'price',
        'price_text',
        'price_m',
        'date_public',
        'date_end',
        'date_list',
        'schedule',
        'time',
        'place',
        'period',
        'vol_program',
        'format',
        'format_text',
        'short',
        'text',
        'image',
        'position_visible',
        'slider_in',
        'slider_text',
        'url'
    ];
}
