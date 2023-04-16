<?php

namespace App\Observers;

use App\Models\History;
use App\Jobs\PostApproveJob;
use App\Jobs\PostDenyJob;
use App\Jobs\PostReturnJob;
use App\Jobs\DueNotifyJob;

class HistoryObserver
{
    public function updating(History $history)
    {
        if ($history->isDirty('approved_at') && $history->approved_at) {
            PostApproveJob::dispatch($history);

            $notifyAt = $history->due_at;
            $notifyAt->hour = 8;
            $notifyAt->minute = 0;
            $notifyAt->second = 0;

            if (config('app.env') == 'local') {
                $notifyAt = now()->addSeconds(10);
            }

            DueNotifyJob::dispatch($history)
                ->delay($notifyAt);
        }

        if ($history->isDirty('denied_at') && $history->denied_at) {
            PostDenyJob::dispatch($history);
        }

        if ($history->isDirty('returned_at') && $history->returned_at) {
            PostReturnJob::dispatch($history);
        }
    }
}
