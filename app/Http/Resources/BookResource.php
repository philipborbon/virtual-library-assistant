<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\History;

class BookResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'language' => $this->language,
            'circulation' => $this->circulation,
            'description' => $this->description,
            'image' => $this->image,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'date_published' => $this->date_published,
            'pages' => $this->pages,
            'category' => new CategoryResource($this->category),
        ];
    }

    public function with($request)
    {
        $user = $request->user();

        $history = History::where('book_id', $this->id)
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

        return [
            '_meta' => [
                'is_available' => $this->getAvailable() > 0,
                'limit_reached' => $user->isBorrowLimitReached(),
                'active_borrow' => $user->active_borrow,
                'last_request_status' => $status,
            ],
        ];
    }
}
