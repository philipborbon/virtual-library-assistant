<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Messaging\CloudMessage;

use App\Models\History;
use Log;

class DueNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $history;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(History $history)
    {
        $this->history = $history;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $history = $this->history;

        if (config('app.env') != 'local') {
            if (! $history->due_at->isToday()) {
                // Skip due reminder if not today
                return;
            }
        }

        if (! $history->user->push_token) {
            Log::error("Unable to notify user({$user->id}), empty push token.");
            return;
        }

        $messaging = app('firebase.messaging');

        $message = CloudMessage::fromArray([
            'token' => $history->user->push_token,
            'notification' => [
                'title' => "Due Reminder: {$history->book->title}",
                'body' => "Please return the book you borrowed today.",
            ],
            'data' => [
                'id' => $history->id,
                'type' => 'history',
            ],
        ]);

        $messaging->send($message);
    }
}
