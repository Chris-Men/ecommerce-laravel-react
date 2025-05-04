<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'qty', 'price', 'description',
        'thumbnail', 'first_image', 'second_image', 'third_image',
        'status', 'category_id', 'brand_id', 'color_id', 'size_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Relación corregida: uno a muchos
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)
            ->with('user')

            ->latest();
    }

    public function getRouteKeyName()
    {
        return "slug";
    }
}
