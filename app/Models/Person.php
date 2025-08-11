<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'name',
        'middle_name',
        'sex',
        'position',
        'img',
    ];

    public function viewpoint_person(){
        return $this->hasMany(ViewpointPerson::class);
    }
}
