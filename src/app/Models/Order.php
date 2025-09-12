<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'user_id', 'payment_method', 'postal_code', 'address', 'building_name','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
}