<?php

use App\Models\Bucket;
use App\Models\Objecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

uses(RefreshDatabase::class);

test('bucket show paginates objects', function () {
    $user = User::factory()->create();
    $bucket = Bucket::create([
        'user_id' => $user->id,
        'name' => 'Test Bucket',
        'visibility' => 'pr',
    ]);

    // Create 25 objects
    Objecto::factory()->count(25)->create([
        'user_id' => $user->id,
        'bucket_id' => $bucket->id,
        'storage_disk' => 'local',
        'storage_path' => 'test/path',
    ]);

    $response = $this->actingAs($user)->get(route('bucket.show', $bucket->slug));

    $response->assertStatus(200);
    $response->assertViewHas('objects', function ($objects) {
        return $objects instanceof LengthAwarePaginator
            && $objects->count() === 20 // First page size
            && $objects->total() === 25;
    });
});

test('bucket show does not trigger lazy loading', function () {
    $user = User::factory()->create();
    $bucket = Bucket::create([
        'user_id' => $user->id,
        'name' => 'Test Bucket',
        'visibility' => 'pr',
    ]);

    Objecto::factory()->count(5)->create([
        'user_id' => $user->id,
        'bucket_id' => $bucket->id,
    ]);

    // This will throw a LazyLoadingViolationException if N+1 occurs because we enabled it in AppServiceProvider
    $response = $this->actingAs($user)->get(route('bucket.show', $bucket->slug));

    $response->assertStatus(200);
});
