<?php

namespace Faithgen\Testimonies\Http\Resources;

use FaithGen\SDK\SDK;
use InnoFlash\LaraStart\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class Image extends JsonResource
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
            'caption' => $this->caption,
	    'comments_count' => $this->comments()->count(),
            'date' => Helper::getDates($this->created_at),
            'avatar' => [
                '_50' => SDK::getAsset('storage/testimonies/50-50/' . $this->name),
                '_100' => SDK::getAsset('storage/testimonies/100-100/' . $this->name),
                'original' => SDK::getAsset('storage/testimonies/original/' . $this->name),
            ]
        ];
    }
}
