<?php

namespace App\Models;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product()
{
    return $this->belongsTo(Product::class);
}

public function color()
{
    return $this->belongsTo(ProductColor::class, 'product_color_id');
}

public function size()
{
    return $this->belongsTo(ProductSize::class, 'product_size_id');
}

}
