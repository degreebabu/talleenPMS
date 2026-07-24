<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('booking_number')->unique()->nullable()->after('id');
            $table->text('notes')->nullable()->after('booking_type');
            $table->integer('adults')->default(1)->after('notes');
            $table->integer('children')->default(0)->after('adults');
            $table->timestamp('checked_in_at')->nullable()->after('children');
            $table->timestamp('checked_out_at')->nullable()->after('checked_in_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_number', 'notes', 'adults', 'children', 'checked_in_at', 'checked_out_at']);
        });
    }
};
