<?php

namespace App\Jobs;

use App\Models\Objecto;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ExtractObjectMetadata implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param  Collection<int, Objecto>  $objectos
     */
    public function __construct(public Collection $objectos) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->objectos as $objecto) {
            $metadata = [];
            $disk = Storage::disk($objecto->storage_disk);

            if ($disk->exists($objecto->storage_path)) {
                $mimeType = $objecto->mime_type;

                if (str_starts_with($mimeType, 'image/')) {
                    $fullPath = $disk->path($objecto->storage_path);

                    $imageSize = @getimagesize($fullPath);
                    if ($imageSize) {
                        $metadata['width'] = $imageSize[0];
                        $metadata['height'] = $imageSize[1];
                    }
                }

                if (! empty($metadata)) {
                    $currentData = is_array($objecto->metadata) ? $objecto->metadata : [];
                    $objecto->update([
                        'metadata' => array_merge($currentData, $metadata),
                    ]);
                }
            }
        }
    }
}
