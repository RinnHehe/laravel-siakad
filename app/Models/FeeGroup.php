<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'group',
        'amount',
    ];
    
}
