<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $approved = 0;

        if ($this->approved == null) {
            $approved = -1;
        } else {
            $approved = $this->approved ? 1 : 0;
        }

        return [
            'id' => $this->id,
            'book' => new BookResource($this->book),
            'approved' => $approved,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'approved_at' => $this->approved_at,
            'denied_at' => $this->denied_at,
            'returned_at' => $this->returned_at,
            'due_at' => $this->due_at,
        ];
    }
}
