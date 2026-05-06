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
        Schema::create('object_share_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('object_id')->constrained('objects')->cascadeOnDelete();
            $table->foreignId('created_by_user_id')->constrained('users')->cascadeOnDelete();

            $table->string('token', 80)->unique();
            $table->enum('permission', ['r', 'd', 'rd'])->default('r')->comment('r = read, d = download, rd = read and download');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('revoked_at')->nullable();

            $table->string('url');

            $table->unsignedInteger('download_limit')->nullable();
            $table->unsignedInteger('download_count')->default(0);

            $table->timestamps();

            $table->index(['object_id', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('object_share_links');
    }
};
