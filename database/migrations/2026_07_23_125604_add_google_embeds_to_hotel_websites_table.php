<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotel_websites', function (Blueprint $table) {
            $table->text('google_map_embed')->nullable();
            $table->text('google_reviews_embed')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('hotel_websites', function (Blueprint $table) {
            $table->dropColumn(['google_map_embed', 'google_reviews_embed']);
        });
    }
};
