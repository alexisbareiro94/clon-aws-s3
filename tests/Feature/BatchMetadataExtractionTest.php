<?php

use App\Jobs\ExtractObjectMetadata;
use App\Models\Bucket;
use App\Models\Objecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('multiple file uploads dispatch metadata job once', function () {
    $this->withoutMiddleware();
    Queue::fake();
    Storage::fake('local');

    $user = User::factory()->create();
    $bucket = Bucket::factory()->create(['user_id' => $user->id]);

    $file1 = UploadedFile::fake()->image('photo1.jpg', 100, 100);
    $file2 = UploadedFile::fake()->image('photo2.png', 200, 200);

    $response = $this->actingAs($user)->post(route('object.store', $bucket), [
        'files' => [$file1, $file2],
        'visibility' => 'pr',
    ]);

    $response->assertStatus(302);

    // Verify job was dispatched exactly once
    Queue::assertPushed(ExtractObjectMetadata::class, 1);

    // Verify the job contains both objects
    Queue::assertPushed(ExtractObjectMetadata::class, function ($job) {
        return $job->objectos->count() === 2;
    });
});

test('batch metadata job extracts metadata for all objects', function () {
    Storage::fake('local');

    $user = User::factory()->create();
    $bucket = Bucket::factory()->create(['user_id' => $user->id]);

    // Create objects manually and store fake images
    $files = [
        ['name' => 'img1.jpg', 'w' => 50, 'h' => 50],
        ['name' => 'img2.png', 'w' => 150, 'h' => 150],
    ];

    $objectos = collect();

    foreach ($files as $f) {
        $file = UploadedFile::fake()->image($f['name'], $f['w'], $f['h']);
        $path = $file->store($bucket->slug, 'local');

        $objecto = Objecto::create([
            'bucket_id' => $bucket->id,
            'user_id' => $user->id,
            'object_key' => $f['name'],
            'original_name' => $f['name'],
            'storage_disk' => 'local',
            'storage_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'checksum' => md5($f['name']),
            'visibility' => 'pr',
        ]);
        $objectos->push($objecto);
    }

    // Execute the job synchronously
    $job = new ExtractObjectMetadata($objectos);
    $job->handle();

    // Verify metadata was updated for both
    foreach ($objectos as $index => $objecto) {
        $objecto->refresh();
        expect($objecto->metadata)->toBeArray()
            ->and($objecto->metadata['width'])->toBe($files[$index]['w'])
            ->and($objecto->metadata['height'])->toBe($files[$index]['h']);
    }
});
