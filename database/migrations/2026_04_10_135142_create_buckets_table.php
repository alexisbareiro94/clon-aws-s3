<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buckets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name', 120);
            $table->string('slug', 140);
            $table->enum('visibility', ['pr', 'pu'])->default('pr')->comment('pr = private, pu = public');
            $table->text('description')->nullable();

            $table->unsignedBigInteger('max_size_bytes')->nullable();
            $table->json('settings')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'slug']);
            $table->index(['user_id', 'visibility']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buckets');
    }
};
