<?php

namespace Faithgen\Testimonies\Jobs;

use FaithGen\SDK\Traits\UploadsImages;
use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManager;

final class UploadImages implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        UploadsImages;

    /**
     * @var testimony
     */
    protected Testimony $testimony;

    /**
     * List of images to be saved.
     */
    protected array $images;

    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @param Testimony $testimony
     * @param array $images
     */
    public function __construct(Testimony $testimony, array $images)
    {
        $this->testimony = $testimony;
        $this->images = $images;
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
        $this->uploadImages($this->testimony, $this->images, $imageManager);
    }
}
