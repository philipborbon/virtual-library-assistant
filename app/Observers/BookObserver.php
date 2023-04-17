<?php

namespace App\Observers;

use App\Jobs\CheckBookAvailability;
use App\Models\Book;

class BookObserver
{
    public function updating(Book $book)
    {
        if ($book->isDirty('available') && $book->available) {
            CheckBookAvailability::dispatch($book)
                ->delay(now()->addSecond());
        }
    }
}
