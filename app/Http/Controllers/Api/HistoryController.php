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
        $show = $request->input('show');
    
        $histories = History::where('user_id', $user->id);

        if ($show == 'pendings') {
            $histories->whereNull('approved');
        } else if ($show == 'to_return') {
            $histories->where('approved', true);
            $histories->whereNull('returned_at');
        }
        
        $histories->orderBy('approved');
        $histories->orderBy('created_at', 'DESC');

        return HistoryResource::collection($histories->get());
    }

    public function getHistory($historyId, Request $request)
    {
        $history = History::find($historyId);

        if (! $history) {
            abort(404);
        }

        return new HistoryResource($history);
    }

    public function cancel($historyId, Request $request)
    {
        $history = History::find($historyId);

        if (! $history) {
            abort(404);
        }

        if ($history->approved === null) {
            $history->delete();
        } else if ($history->approved) {
            return response("Your request has already been approved. Please approach an administrator to get your request cancelled.", 422);
        } else {
            return response("Your request has already been denied. No further action is required from you.", 422);
        }

        return response()->noContent();
    }

    public function getOverview(Request $request) 
    {
        $user = $request->user();
        
        $pendings = History::where('user_id', $user->id)
            ->whereNull('approved')
            ->count();

        $toReturn = History::where('user_id', $user->id)
            ->where('approved', true)
            ->whereNull('returned_at')
            ->count();

        $all = History::where('user_id', $user->id)->count();

        return [
            'pendings' => $pendings,
            'to_return' => $toReturn,
            'all' => $all,
        ];
    }
}
