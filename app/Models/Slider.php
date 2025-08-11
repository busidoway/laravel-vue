<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'text1',
        'text2',
        'text3',
        'image',
        'img_full',
        'url',
        'date',
        'date_end',
        'sort'
    ];
}
