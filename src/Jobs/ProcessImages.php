<?php

namespace Faithgen\Testimonies\Jobs;

use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManager;

final class ProcessImages implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public bool $deleteWhenMissingModels = true;

    /**
     * The testimony to process images for.
     *
     * @var Testimony
     */
    protected Testimony $testimony;

    /**
     * Create a new job instance.
     *
     * @param Testimony $testimony
     */
    public function __construct(Testimony $testimony)
    {
        $this->testimony = $testimony;
    }

    /**
     * Execute the job.
     *
     * @param ImageManager $imageManager
     *
     * @return void
     */
    public function handle(ImageManager $imageManager)
    {
        foreach ($this->testimony->images as $image) {
            try {
                $ogImage = storage_path('app/public/testimonies/original/').$image->name;
                $thumb50 = storage_path('app/public/testimonies/50-50/').$image->name;
                $thumb100 = storage_path('app/public/testimonies/100-100/').$image->name;

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
