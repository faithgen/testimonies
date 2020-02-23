<?php

namespace Faithgen\Testimonies\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Intervention\Image\ImageManager;

final class ProcessImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deleteWhenMissingModels = true;

    /**
     * The tesimony to process images for
     *
     * @var Testimony
     */
    protected $testimony;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Testimony $testimony)
    {
        $this->testimony = $testimony;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageManager $imageManager)
    {
        foreach ($this->testimony->images as $image) {
            try {
                $ogImage = storage_path('app/public/testimonies/original/') . $image->name;
                $thumb50 = storage_path('app/public/testimonies/50-50/') . $image->name;
                $thumb100 = storage_path('app/public/testimonies/100-100/') . $image->name;

                $imageManager->make($ogImage)->fit(100, 100, function ($constraint) {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                }, 'center')->save($thumb100);

                $imageManager->make($ogImage)->fit(50, 50, function ($constraint) {
                    $constraint->upsize();
                    $constraint->aspectRatio();
                }, 'center')->save($thumb50);
            } catch (\Exception $e) {
            }
        }
    }
}
