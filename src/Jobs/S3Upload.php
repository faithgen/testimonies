<?php

namespace Faithgen\Testimonies\Jobs;

use FaithGen\SDK\Traits\SavesToAmazonS3;
use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class S3Upload implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        SavesToAmazonS3;
    /**
     * @var Testimony
     */
    private Testimony $testimony;

    public bool $deleteWhenMissingModels = true;

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
     * @return void
     */
    public function handle()
    {
        $this->saveFiles($this->testimony);
    }
}
