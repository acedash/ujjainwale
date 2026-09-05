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
        Schema::create('external_headlines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_source_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('original_snippet')->nullable();
            $table->text('ai_summary')->nullable();
            $table->string('source_url');
            $table->string('thumbnail_url')->nullable();
            $table->string('guid')->unique();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('fetched_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_headlines');
    }
};
