<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function index()
    {
        // Получаем список всех книг
        $books = Book::with('genres', 'authors')->get();
        return response()->json($books);
    }

    // Получение информации по отдельной книге
    public function show($id)
    {
        // Получаем книгу по ID
        $book = Book::with('genres', 'authors')->find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($book);
    }

    // Добавление новой книги (только для администраторов)
    public function store(Request $request)
{
    $user = JWTAuth::parseToken()->authenticate();

    if ($user->role !== 'admin') {
        return response()->json(['error' => 'Forbidden'], 403);
    }

    $validationErrors = [];

    if (empty($request->title)) {
        $validationErrors['title'] = 'Title is required';
    }

    if (empty($request->description)) {
        $validationErrors['description'] = 'Description is required';
    }

    if (empty($request->genre_ids) || !is_array($request->genre_ids)) {
        $validationErrors['genre_ids'] = 'Genre IDs must be a non-empty array';
    }

    if (empty($request->author_ids) || !is_array($request->author_ids)) {
        $validationErrors['author_ids'] = 'Author IDs must be a non-empty array';
    }

    if (!empty($validationErrors)) {
        return response()->json(['errors' => $validationErrors], 400);
    }

    // Проверка существования жанров и авторов
    $invalidGenres = DB::table('genres')
        ->whereIn('id', $request->genre_ids)
        ->pluck('id')
        ->diff($request->genre_ids);

    if ($invalidGenres->isNotEmpty()) {
        return response()->json(['error' => 'Invalid genre IDs: ' . implode(', ', $invalidGenres->toArray())], 400);
    }

    $invalidAuthors = DB::table('authors')
        ->whereIn('id', $request->author_ids)
        ->pluck('id')
        ->diff($request->author_ids);

    if ($invalidAuthors->isNotEmpty()) {
        return response()->json(['error' => 'Invalid author IDs: ' . implode(', ', $invalidAuthors->toArray())], 400);
    }

    $book = Book::create([
        'title' => $request->title,
        'description' => $request->description,
    ]);

    $book->genres()->sync($request->genre_ids);
    $book->authors()->sync($request->author_ids);

    return response()->json($book, 201);
}

    // Удаление книги (только для администраторов)
    public function destroy($id)
    {
        // Получаем текущего пользователя
        $user = JWTAuth::user();

        // Проверка, если пользователь не администратор
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $book->delete();
        return response()->json(['message' => 'Book deleted successfully']);
    }

    // Добавление книги в избранное
    public function addToFavorites($id)
    {
        $user = JWTAuth::user();
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $user->favorites()->attach($book);
        return response()->json(['message' => 'Book added to favorites']);
    }

    // Удаление книги из избранного
    public function removeFromFavorites($id)
    {
        $user = JWTAuth::user();
        $book = Book::find($id);
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        $user->favorites()->detach($book);
        return response()->json(['message' => 'Book removed from favorites']);
    }
}
