<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model implements Authenticatable, JWTSubject
{
    use HasFactory, AuthenticatableTrait;

    protected $fillable = ['email', 'password', 'role'];

    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return []; 
    }

    public function favorites()
    {
        return $this->belongsToMany(Book::class, 'user_favorites');
    }
}
