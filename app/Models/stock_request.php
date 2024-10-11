<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock_request extends Model
{
    use HasFactory;
  
    protected $fillable=
    [
            'item_name',
            'item_quantity',
            'item_number',
            'clinic',
            'requester',
            'status',
            'approver',
            'date_requested',
            'date_approved',
    ];
}
