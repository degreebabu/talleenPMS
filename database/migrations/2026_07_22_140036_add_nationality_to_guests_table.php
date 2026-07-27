<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('nationality')->nullable()->after('phone');
            $table->string('id_type')->nullable()->after('nationality'); // passport, aadhar, etc.
            $table->string('address')->nullable()->after('id_type');
            $table->date('dob')->nullable()->after('address');
            $table->json('preferences')->nullable()->after('dob'); // room preferences, dietary, etc.
            $table->integer('total_stays')->default(0)->after('preferences');
            $table->decimal('total_revenue', 10, 2)->default(0)->after('total_stays');
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn(['nationality', 'id_type', 'address', 'dob', 'preferences', 'total_stays', 'total_revenue']);
        });
    }
};
