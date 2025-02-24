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
        $books = Book::with('genres', 'authors')->get();
        return response()->json($books);
    }

    public function show($id)
    {
        $book = Book::with('genres', 'authors')->find($id);
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }
        return response()->json($book);
    }

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

    if (empty($request->genres) || !is_array($request->genres)) {
        $validationErrors['genres'] = 'Genres must be a non-empty array';
    }

    if (empty($request->authors) || !is_array($request->authors)) {
        $validationErrors['authors'] = 'Authors must be a non-empty array';
    }

    if (!empty($validationErrors)) {
        return response()->json(['errors' => $validationErrors], 400);
    }

    $genreIds = collect($request->genres)->map(function ($genreName) {
        $genre = DB::table('genres')->where('name', $genreName)->first();

        if (!$genre) {
            $genre = DB::table('genres')->insertGetId(['name' => $genreName]);
        } else {
            $genre = $genre->id;
        }

        return $genre;
    });

    $authorIds = collect($request->authors)->map(function ($authorName) {
        $author = DB::table('authors')->where('name', $authorName)->first();

        if (!$author) {
            $author = DB::table('authors')->insertGetId(['name' => $authorName]);
        } else {
            $author = $author->id;
        }

        return $author;
    });

    $book = Book::create([
        'title' => $request->title,
        'description' => $request->description,
    ]);

    $book->genres()->sync($genreIds);
    $book->authors()->sync($authorIds);

    return response()->json($book, 201);
}

    public function destroy($id)
    {
         $user = JWTAuth::user();

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
