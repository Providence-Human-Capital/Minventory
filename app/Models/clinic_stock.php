<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clinic_stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_name',
        'item_quantity',
        'item_number',
        'clinic'
    ];

    public $timestamps = false;
}
