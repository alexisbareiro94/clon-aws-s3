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
        Schema::create('object_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('object_id')->constrained('objects')->cascadeOnDelete();

            $table->unsignedInteger('version_number');
            $table->string('storage_path', 255);
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('size_bytes');
            $table->string('checksum', 128)->nullable();
            $table->boolean('is_current')->default(false);
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->unique(['object_id', 'version_number']);
            $table->index(['object_id', 'is_current']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('object_versions');
    }
};
