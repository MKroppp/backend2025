<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'description'];

    // Связь многие ко многим с жанрами
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }

    // Связь многие ко многим с авторами
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    // Связь многие ко многим с избранными книгами (для пользователей)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_favorites');
    }
}
