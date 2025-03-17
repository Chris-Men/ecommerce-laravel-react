<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    protected $fillable = ["name","discount","valid_until"];

    /**
     * Pasar a mayusculas el codigo del cupon
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::upper($value);
    }

    
    /**
     * Verificar si el cupon no esta expirado
     */
    public function checkIfValid()
    {
        if($this->valid_until > Carbon::now()) {
            return true;
        }else {
            return false;
        }
    }
}
