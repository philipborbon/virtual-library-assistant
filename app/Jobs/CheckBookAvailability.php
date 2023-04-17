<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Messaging\CloudMessage;

use App\Models\Book;
use App\Models\BookNotify;
use Log;

class CheckBookAvailability implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $book;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $book = $this->book;

        $toNotify = BookNotify::where('book_id', $book->id)->get();
        $pushTokens = array();

        foreach ($toNotify as $item) {
            if (! $item->user->push_token) {
                Log::error("Unable to notify user({$item->user->id}), empty push token.");
                continue;
            }

            $pushTokens[] = $item->user->push_token;
        }

        $messaging = app('firebase.messaging');

        $message = CloudMessage::fromArray([
            // 'token' => $item->user->push_token,
            'priority' => 'high',
            'notification' => [
                'title' => "Book Available: {$book->title}",
                'body' => "Book is now available.",
            ],
            'data' => [
                'id' => $book->id,
                'type' => 'book',
            ],
        ]);

        $messaging->sendMulticast($message, $pushTokens);

        BookNotify::where('book_id', $book->id)->delete();
    }
}
