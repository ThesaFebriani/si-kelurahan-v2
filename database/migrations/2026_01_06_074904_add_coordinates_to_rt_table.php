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
        Schema::table('rt', function (Blueprint $table) {
            $table->string('latitude')->nullable()->after('jumlah_penduduk');
            $table->string('longitude')->nullable()->after('latitude');
            $table->string('warna_wilayah')->nullable()->default('#3b82f6')->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rt', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'warna_wilayah']);
        });
    }
};
