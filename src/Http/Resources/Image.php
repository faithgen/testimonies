<?php

namespace Faithgen\Testimonies\Http\Resources;

use FaithGen\SDK\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use InnoFlash\LaraStart\Helper;

class Image extends JsonResource
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
            'caption' => $this->caption,
            'comments_count' => $this->comments()->count(),
            'date' => Helper::getDates($this->created_at),
            'avatar' => ImageHelper::getImage('testimonies', $this->resource, config('faithgen-sdk.ministries-server')),
        ];
    }
}
