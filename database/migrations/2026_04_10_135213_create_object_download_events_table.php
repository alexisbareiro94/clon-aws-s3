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
        Schema::create('object_download_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('object_id')->constrained('objects')->cascadeOnDelete();
            $table->foreignId('share_link_id')->nullable()->constrained('object_share_links')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->ipAddress('ip_address')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('referrer', 255)->nullable();

            $table->timestamps();

            $table->index(['object_id', 'created_at']);
            $table->index(['share_link_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('object_download_events');
    }
};
