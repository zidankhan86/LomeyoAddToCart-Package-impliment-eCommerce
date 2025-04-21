<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductColor extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product()
{
    return $this->belongsTo(Product::class);
}

public function variants()
{
    return $this->hasMany(ProductVariant::class);
}

}
