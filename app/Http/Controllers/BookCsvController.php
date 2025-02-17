<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class BookCsvController extends Controller
{
    // Выгрузка списка книг в CSV
    public function exportCsv()
    {
        // Проверка, что пользователь является администратором
        $user = JWTAuth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $books = Book::all();
        $csvHeader = ['ID', 'Title', 'Description'];

        // Подготовка CSV
        $csvData = [];
        foreach ($books as $book) {
            $csvData[] = [$book->id, $book->title, $book->description];
        }

        // Генерация и возврат CSV
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
