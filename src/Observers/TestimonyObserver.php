<?php

namespace Faithgen\Testimonies\Observers;

use Faithgen\Testimonies\Models\Testimony;

final class TestimonyObserver
{
    /**
     * Handle the testimony "created" event.
     *
     * @param  \App\Testimony  $testimony
     * @return void
     */
    public function created(Testimony $testimony)
    {
        //
    }

    /**
     * Handle the testimony "updated" event.
     *
     * @param  \App\Testimony  $testimony
     * @return void
     */
    public function updated(Testimony $testimony)
    {
        //
    }

    /**
     * Handle the testimony "deleted" event.
     *
     * @param  \App\Testimony  $testimony
     * @return void
     */
    public function deleted(Testimony $testimony)
    {
        //
    }
}
