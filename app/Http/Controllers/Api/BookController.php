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
        $books = Book::where('category_id', $categoryId)
            ->orderBy('title')
            ->get();

        return BookResource::collection($books);
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
            ->where(function($query) {
                $query->whereNull('approved')
                    ->orWhere(function($query) {
                        $query->where('approved', true)
                            ->whereNull('returned_at');
                    });
            })
            ->first();

        if ($history) {
            if ($history->approved_at === null) {
                return response("You have a pending borrow request.", 422);
            } else {
                return response("Please return book before a posting new borrow request.", 422);
            }
        }

        if ($book->getAvailable() <= 0) {
            return response("Book has no available copy for borrowing at the moment.", 423);
        }

        History::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        return response()->noContent(Response::HTTP_CREATED);
    }
}
