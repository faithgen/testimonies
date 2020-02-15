<?php

namespace Faithgen\Testimonies\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Intervention\Image\ImageManager;

final class UploadImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var testimony
     */
    protected $testimony;

    /**
     * List of images to be saved
     */
    protected array $images;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Testimony $testimony, array $images)
    {
        $this->testimony = $testimony;
        $this->images = $images;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageManager $imageManager)
    {
        foreach ($this->images as $imageData) {
            $fileName = str_shuffle($this->testimony->id . time() . time()) . '.png';
            $ogSave = storage_path('app/public/testimonies/original/') . $fileName;
            $imageManager->make($imageData)->save($ogSave);
            $this->testimony->images()->create([
                'imageable_id' => $this->testimony->id,
                'name' => $fileName
            ]);
        }
    }
}