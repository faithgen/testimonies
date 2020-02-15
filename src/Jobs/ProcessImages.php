<?php

namespace Faithgen\Testimonies\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Faithgen\Testimonies\Models\Testimony;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

final class ProcessImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $deleteWhenMissingModels = true;
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
    public function handle()
    {
        //
    }
}
