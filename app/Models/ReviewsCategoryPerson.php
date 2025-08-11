<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewsCategoryPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviews_category_id',
        'person_id',
    ];
}
