<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'image', 'brand_id', 'mehsul', 'alish','satish','miqdar'
    ];
}
