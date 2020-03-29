<?php

namespace Faithgen\Testimonies\Http\Resources;

use FaithGen\SDK\Helpers\ImageHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'image' => ImageHelper::getImage('users', $this->image, config('faithgen-sdk.users-server'))
        ]);
    }
}
