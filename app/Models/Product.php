<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
<<<<<<< HEAD
    protected $fillable = [
        'name', 'slug', 'qty', 'price', 'description',
        'thumbnail', 'first_image', 'second_image', 'third_image',
        'status', 'category_id', 'brand_id'
    ];




=======
    protected $fillable = ["name","slug","qty","price",
        "desc","thumbnail","first_image","second_image","third_image",
        "status","category_id","brand_id"];
>>>>>>> origin/Juan

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

<<<<<<< HEAD
    public function products()
{
    return $this->belongsToMany(Product::class)
                ->withPivot('quantity')
                ->withTimestamps();
}

=======
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
>>>>>>> origin/Juan

    public function reviews()
    {
        return $this->hasMany(Review::class)
            ->with('user')
            ->where('approved',1)
            ->latest();
    }

    public function getRouteKeyName()
    {
        return "slug";
    }
}
