<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class avenue81_stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_name',
        'item_quantity',
        'item_number',
    ];

  
}
