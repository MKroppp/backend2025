<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        // Получаем список всех жанров
        $genres = Genre::all();
        return response()->json($genres);
    }

    public function show($id)
    {
        // Получаем жанр по ID
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json(['message' => 'Genre not found'], 404);
        }

        return response()->json($genre);
    }
}
