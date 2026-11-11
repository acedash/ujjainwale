<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('media')
            ->where('model_type', 'App\Models\Article')
            ->where('collection_name', 'cover')
            ->update(['collection_name' => 'featured_image']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('media')
            ->where('model_type', 'App\Models\Article')
            ->where('collection_name', 'featured_image')
            ->update(['collection_name' => 'cover']);
    }
};
