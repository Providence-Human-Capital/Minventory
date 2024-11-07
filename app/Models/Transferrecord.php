<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferrecord extends Model
{
    use HasFactory;

    public $fillable =
    [
        'drug_name',
        'clinic_from',
        'sender',
        'drug_amount',
        'clinic_to',
        'reciever',
        'status'

    ];
}
