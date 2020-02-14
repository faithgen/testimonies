<?php

namespace Faithgen\Testimonies\Models;

use FaithGen\SDK\Models\UuidModel;
use FaithGen\SDK\Traits\Relationships\Belongs\BelongsToMinistryTrait;
use FaithGen\SDK\Traits\Relationships\Belongs\BelongsToUserTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\CommentableTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\ImageableTrait;
use FaithGen\SDK\Traits\TitleTrait;

class Testimony extends UuidModel
{
    use BelongsToMinistryTrait, BelongsToUserTrait, TitleTrait, CommentableTrait, ImageableTrait;
}
