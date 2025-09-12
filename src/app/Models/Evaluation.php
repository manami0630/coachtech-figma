<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'target_user_id',
        'rating',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}