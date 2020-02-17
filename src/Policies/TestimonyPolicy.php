<?php

namespace Faithgen\Testimonies\Policies;

use FaithGen\SDK\Models\Ministry;
use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestimonyPolicy
{
    use HandlesAuthorization;

    private $adminRouteNames = [
        'testimonies.toggle-approval',
        'testimonies.delete-image',
    ];

    public function viewAny(Ministry $ministry)
    {
        //
    }

    /**
     * Determine whether the ministry can view the testimony.
     *
     * @param  \App\Models\Ministry  $ministry
     * @param  \App\Testimony  $testimony
     * @return mixed
     */
    public function view(Ministry $ministry, Testimony $testimony)
    {
        return $ministry->id === $testimony->ministry_id;
    }

    /**
     * Determine whether the ministry can create testimonies.
     *
     * @param  \App\Models\Ministry  $ministry
     * @return mixed
     */
    public function create(Ministry $ministry)
    {
        //
    }

    /**
     * Determine whether the ministry can update the testimony.
     *
     * @param  \App\Models\Ministry  $ministry
     * @param  \App\Testimony  $testimony
     * @return mixed
     */
    public function update(Ministry $ministry, Testimony $testimony)
    {
        if (in_array(request()->route()->getName(), $this->adminRouteNames))
            return $ministry->id === $testimony->ministry_id;
        $user = auth('web')->user();
        return $user->id === $testimony->user_id
            && $user->active
            && $ministry->id === $testimony->ministry_id;
    }

    /**
     * Determine whether the ministry can delete the testimony.
     *
     * @param  \App\Models\Ministry  $ministry
     * @param  \App\Testimony  $testimony
     * @return mixed
     */
    public function delete(Ministry $ministry, Testimony $testimony)
    {
        return $ministry->id === $testimony->ministry_id || auth('web')->user()->id === $testimony->user_id;
    }
}
