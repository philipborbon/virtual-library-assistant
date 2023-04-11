<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\History;
use App\Http\Resources\HistoryResource;

class HistoryController extends Controller
{
    public function getHistories(Request $request)
    {
        $user = $request->user();

        $histories = History::where('user_id', $user->id)
            ->orderBy('approved')
            ->orderBy('created_at', 'DESC')
            ->get();

        return HistoryResource::collection($histories);
    }

    public function getHistory($historyId, Request $request)
    {
        $history = History::find($historyId);

        if (! $history) {
            abort(404);
        }

        return new HistoryResource($history);
    }
}
