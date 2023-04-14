<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'category_id' => $this->category_id,
            'title' => $this->title,
            'language' => $this->language,
            'description' => $this->description,
            'image' => $this->image,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'date_published' => $this->date_published,
            'pages' => $this->pages,
            'category' => new CategoryResource($this->category)
        ];
    }
}
