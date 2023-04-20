<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    protected $status;

    public function __construct($resource, $status = null)
    {
        parent::__construct($resource);

        $this->status = $status;
    }

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
            'circulation' => $this->circulation,
            'description' => $this->description,
            'image' => $this->image,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'date_published' => $this->date_published,
            'pages' => $this->pages,
            'is_available' => $this->getAvailable() > 0,
            'last_request_status' => $this->status,
            'category' => new CategoryResource($this->category),
        ];
    }
}
