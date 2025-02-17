<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name'];

    // Связь многие ко многим с книгами
    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_author');
    }
}
