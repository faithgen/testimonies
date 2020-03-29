<?php

namespace Faithgen\Testimonies\Models;

use FaithGen\SDK\Models\User;
use FaithGen\SDK\Models\UuidModel;
use FaithGen\SDK\Traits\Relationships\Belongs\BelongsToMinistryTrait;
use FaithGen\SDK\Traits\Relationships\Belongs\BelongsToUserTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\CommentableTrait;
use FaithGen\SDK\Traits\Relationships\Morphs\ImageableTrait;
use FaithGen\SDK\Traits\StorageTrait;
use FaithGen\SDK\Traits\TitleTrait;

final class Testimony extends UuidModel
{
    use BelongsToMinistryTrait;
    use BelongsToUserTrait;
    use TitleTrait;
    use CommentableTrait;
    use ImageableTrait;
    use StorageTrait;

    public function filesDir()
    {
        return 'testimonies';
    }

    public function getFileName(): array
    {
        return $this->images()
            ->pluck('name')
            ->toArray();
    }

    public function scopeApproved($query, User $user = null)
    {
        $authedUser = auth('web')->user();

        if ($user === null || $authedUser === null) $isOwner = false;

        if ($authedUser && $authedUser->id === $user->id) $isOwner = true;

        if (config('faithgen-sdk.source') || $isOwner)
            return $query;

        return $query->whereApproved(true);
    }
}
