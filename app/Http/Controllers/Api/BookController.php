<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Book;
use App\Models\History;

class BookController extends Controller
{
    public function getBooks($categoryId, Request $request)
    {
        return Book::where('category_id', $categoryId)->get();
    }

    public function requestBorrow($bookId, Request $request)
    {
        $book = Book::find($bookId);

        if (! $book) {
            abort(404);
        }

        $user = $request->user();

        History::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        return response()->noContent(Response::HTTP_CREATED);
    }
}
