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
        Schema::create('rate_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('rule_type'); // e.g. 'season', 'occupancy', 'package', 'dynamic'
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('adjustment_type')->default('percentage'); // 'percentage' or 'fixed'
            $table->decimal('adjustment_value', 10, 2)->default(0); // e.g. 15 for 15% or -10 for -10%
            $table->integer('min_occupancy_percent')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_rules');
    }
};
