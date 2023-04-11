<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\BookResource;

use App\Models\Book;
use App\Models\History;

class BookController extends Controller
{
    public function getBook($bookId, Request $request)
    {
        $book = Book::find($bookId);

        if (! $book) {
            abort(404);
        }

        return new BookResource($book);
    }

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

        $history = History::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereNull('approved')
            ->first();

        if ($history) {
            return response("You have a pending borrow request for \"{$book->title}\".", 422);
        }

        History::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        return response()->noContent(Response::HTTP_CREATED);
    }
}
