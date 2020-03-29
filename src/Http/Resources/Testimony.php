<?php

namespace Faithgen\Testimonies\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use InnoFlash\LaraStart\Helper;

class Testimony extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
       //     'comments_count' => $this->comments()->count(),
            'approved' => (bool)$this->approved,
            'user' => new User($this->user),
            'date' => Helper::getDates($this->created_at)
        ];
    }
}
