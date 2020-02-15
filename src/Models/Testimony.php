<?php

namespace Faithgen\Testimonies\Models;

use FaithGen\SDK\Models\UuidModel;
use FaithGen\SDK\Traits\Relationships\Belongs\BelongsToMinistryTrait;
use FaithGen\SDK\Traits\Relationships\Belongs\BelongsToUserTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\CommentableTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\ImageableTrait;
use FaithGen\SDK\Traits\StorageTrait;
use FaithGen\SDK\Traits\TitleTrait;

final class Testimony extends UuidModel
{
    use BelongsToMinistryTrait, BelongsToUserTrait, TitleTrait, CommentableTrait, ImageableTrait, StorageTrait;

    public function filesDir()
    {
        return 'testimonies';
    }

    public function getFileName()
    {
        return '';
    }

    public function scopeApproved($query)
    {
        if (!config('faithgen-sdk.source'))
            return $query->whereApproved(true);
        else return $query;
    }
}
