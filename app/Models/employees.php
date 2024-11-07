<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employees extends Model
{
    use HasFactory;

    public $fillable = 
    [
            'name',
           'uin',
            'dob',
            'national_id',
            'phone_number',
            'employeer'

    ];

    public $timestamps = false;
}
