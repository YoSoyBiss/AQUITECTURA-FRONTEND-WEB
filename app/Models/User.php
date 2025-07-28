<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model implements AuthenticatableContract
{
    use HasApiTokens, Authenticatable;

    protected $connection = 'mongodb';
    protected $collection = 'users'; // Asegúrate que coincida con tu colección MongoDB

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación personalizada para tokens (MongoDB)
    public function tokens()
    {
        return $this->hasMany(MongoAccessToken::class, 'tokenable_id');
    }
}
