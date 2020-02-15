<?php

namespace Faithgen\Testimonies\Policies;

use FaithGen\SDK\Models\Ministry;
use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Auth\Access\HandlesAuthorization;

class TestimonyPolicy
{
    use HandlesAuthorization;

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
        //
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
