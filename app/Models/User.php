<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Los atributos que son asignables de manera masiva. 
     *
     * @var array<string> 
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
    ];

    /**
     * *Los atributos que deben estar ocultos durante la serialización.
     *
     * @var array<string> 
     */
    protected $hidden = [ 
        'password', 
        'remember_token', 
    ];
/**user */
    /**
     * Los atributos que deben ser casteados a tipos específicos.
     *
     * @return array<string, string> 
     */
    protected function casts(): array 
    {
        return [ 
            'email_verified_at' => 'datetime', 
            'password' => 'hashed', 
        ];
    }
}
