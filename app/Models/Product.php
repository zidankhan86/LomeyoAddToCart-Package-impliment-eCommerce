<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ImageGallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function images(): HasMany
    {
        return $this->hasMany(ImageGallery::class,'product_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id','id');
    }
}
