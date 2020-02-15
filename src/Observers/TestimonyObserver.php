<?php

namespace Faithgen\Testimonies\Observers;

use FaithGen\SDK\Traits\FileTraits;
use Faithgen\Testimonies\Jobs\ProcessImages;
use Faithgen\Testimonies\Jobs\S3Upload;
use Faithgen\Testimonies\Jobs\UploadImages;
use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Support\Facades\DB;

final class TestimonyObserver
{
    use FileTraits;
    /**
     * Handle the testimony "created" event.
     *
     * @param  \Faithgen\Testimonies\Models\Testimony  $testimony
     * @return void
     */
    public function created(Testimony $testimony)
    {
        if (auth()->user()->account->level !== 'Free')
            UploadImages::withChain([
                new ProcessImages($testimony),
                new S3Upload()
            ])->dispatch($testimony, request('images'));
    }

    /**
     * Handle the testimony "updated" event.
     *
     * @param  \Faithgen\Testimonies\Models\Testimony  $testimony
     * @return void
     */
    public function updated(Testimony $testimony)
    {
        //
    }

    /**
     * Handle the testimony "deleted" event.
     *
     * @param  \Faithgen\Testimonies\Models\Testimony  $testimony
     * @return void
     */
    public function deleted(Testimony $testimony)
    {
        if ($testimony->images()->exists()) {
            $this->deleteFiles($testimony);
            DB::table('images')
                ->whereIn('id', $testimony->images()->pluck('id')->toArray())
                ->delete();
        }
    }
}
