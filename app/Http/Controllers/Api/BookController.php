<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\BookResource;

use App\Models\Book;
use App\Models\History;
use App\Models\BookNotify;

class BookController extends Controller
{
    public function getBook($bookId, Request $request)
    {
        $book = Book::find($bookId);

        if (! $book) {
            abort(404);
        }

        $user = $request->user();

        $history = History::where('book_id', $book->id)
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        $status = null;

        if ($history) {
            if ($history->approved === null) {
                $status = 'pending';
            } else if ($history->approved) {
                if ($history->returned_at) {
                    $status = 'returned';
                } else {
                    $status = 'approved';
                }
            } else {
                $status = 'denied';
            }
        }

        return new BookResource($book, $status);
    }

    public function getBooks($categoryId, Request $request)
    {
        $books = Book::where('category_id', $categoryId)
            ->orderBy('title')
            ->get();

        return BookResource::collection($books);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $books = Book::where('title', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->orWhere('author', 'LIKE', "%{$keyword}%")
            ->orWhere('publisher', 'LIKE', "%{$keyword}%")
            ->orWhere('circulation', 'LIKE', "%{$keyword}%")
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
                return response("Please return the book you borrowed before posting a new borrow request.", 422);
            }
        }

        if (strtolower($user->classification) == 'student' && $user->active_borrow >= 3) {
            return response("You have reached the maximum number of books allowed to be borrowed.\n\nPlease return the books you borrowed before posting a new borrow request.", 429);
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

    public function notify($bookId, Request $request)
    {
        $book = Book::find($bookId);
        $user = $request->user();

        if (! $book) {
            abort(404);
        }

        BookNotify::updateOrCreate([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        return response()->noContent(Response::HTTP_CREATED);
    }
}
