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
        Schema::create('dynamic_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('dynamic_field_id')->constrained()->onDelete('cascade');
            $table->text('value_text')->nullable();
            $table->decimal('value_number', 15, 2)->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->date('value_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_values');
    }
};
