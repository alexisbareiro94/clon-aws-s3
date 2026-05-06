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
        Schema::create('objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bucket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('object_key', 255);      // ejemplo: docs/factura.pdf
            $table->string('original_name', 255);
            $table->string('storage_disk', 50)->default('local');
            $table->string('storage_path', 255);

            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('size_bytes');
            $table->string('checksum', 128)->nullable();
            $table->enum('visibility', ['pr', 'pu'])->default('pr');

            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['bucket_id', 'object_key']);
            $table->index(['bucket_id', 'visibility']);
            $table->index(['bucket_id', 'created_at']);
            $table->index('checksum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objects');
    }
};
