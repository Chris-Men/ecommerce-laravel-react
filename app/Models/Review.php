<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'rating', 'comment'];

    protected $casts = [
        'approved' => 'boolean',
    ];

    // Relaciones
=======
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = ["title","body","rating","user_id",
        "product_id","approved"];

>>>>>>> origin/Juan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

<<<<<<< HEAD
    // Personalización de la fecha de creación
=======
>>>>>>> origin/Juan
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
