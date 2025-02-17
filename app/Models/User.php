<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;  // Импортируем интерфейс JWTSubject
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model implements Authenticatable, JWTSubject  // Реализуем интерфейс JWTSubject
{
    use HasFactory, AuthenticatableTrait;

    protected $fillable = ['email', 'password', 'role'];

    // Метод для получения уникального идентификатора пользователя для JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Возвращаем идентификатор пользователя
    }

    // Метод для добавления пользовательских данных в токен
    public function getJWTCustomClaims()
    {
        return [];  // Здесь можно возвращать любые дополнительные данные для токена
    }

    // Связь многие ко многим с книгами (избранные книги)
    public function favorites()
    {
        return $this->belongsToMany(Book::class, 'user_favorites');
    }
}
