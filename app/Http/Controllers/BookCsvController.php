<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookCsvController extends Controller
{
    public function exportCsv()
    {
        $user = JWTAuth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $books = Book::all();
        $csvHeader = ['ID', 'Title', 'Description'];

        $csvData = [];
        foreach ($books as $book) {
            $csvData[] = [$book->id, $book->title, $book->description];
        }

        $filename = 'books.csv';
        $handle = fopen('php://output', 'w');
        fputcsv($handle, $csvHeader);

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        return response()->stream(function () use ($handle) {
            fclose($handle);
        }, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
        ]);
    }
}
