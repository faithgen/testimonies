<?php

namespace Faithgen\Testimonies\Http\Resources;

use InnoFlash\LaraStart\Http\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonyDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'testimony' => $this->testimony,
            'approved' => (bool) $this->approved,
            'user' => $this->user,
            'date' => Helper::getDates($this->created_at),
            'avatars' => Image::collection($this->images)
        ];
    }
}
