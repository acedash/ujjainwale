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
        Schema::table('external_headlines', function (Blueprint $table) {
            $table->text('source_url')->change();
            $table->text('thumbnail_url')->nullable()->change();
            $table->text('title')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_headlines', function (Blueprint $table) {
            $table->string('source_url')->change();
            $table->string('thumbnail_url')->nullable()->change();
            $table->string('title')->change();
        });
    }
};
