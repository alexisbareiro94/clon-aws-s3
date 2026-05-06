<?php

namespace Database\Factories;

use App\Models\Bucket;
use App\Models\Objecto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ObjectoFactory extends Factory
{
    protected $model = Objecto::class;

    public function definition(): array
    {
        return [
            'bucket_id' => Bucket::factory(),
            'user_id' => User::factory(),
            'object_key' => $this->faker->uuid().'.txt',
            'original_name' => $this->faker->word().'.txt',
            'storage_disk' => 'local',
            'storage_path' => 'test/'.$this->faker->uuid(),
            'mime_type' => 'text/plain',
            'size_bytes' => $this->faker->numberBetween(100, 10000),
            'checksum' => md5($this->faker->word()),
            'visibility' => 'pr',
        ];
    }
}
