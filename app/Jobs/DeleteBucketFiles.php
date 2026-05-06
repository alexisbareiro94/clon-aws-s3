<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class DeleteBucketFiles implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Storage::deleteDirectory($this->path);
    }
}
