<?php

namespace Faithgen\Testimonies\Jobs;

use FaithGen\SDK\Traits\ProcessesImages;
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
        SerializesModels,
        ProcessesImages;

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
        $this->processImage($imageManager, $this->testimony);
    }
}
