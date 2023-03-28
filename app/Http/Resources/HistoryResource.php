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
        return [
            'id' => $this->id,
            'category' => [
                'id' => $this->book->category->id,
                'name' => $this->book->category->name,
                'path' => $this->book->category->full_path,
            ],
            'book' => [
                'id' => $this->book_id,
                'name' => $this->book->name,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
