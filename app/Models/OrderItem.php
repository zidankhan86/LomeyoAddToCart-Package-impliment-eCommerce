<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship: An order item belongs to a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
