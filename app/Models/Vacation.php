<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'participants'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'participants' => 'array'
    ];
}
