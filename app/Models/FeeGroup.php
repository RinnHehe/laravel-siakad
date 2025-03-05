<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeGroup extends Model
{
    protected $fillable = [
        'group',
        'amount',
    ];
    
}
