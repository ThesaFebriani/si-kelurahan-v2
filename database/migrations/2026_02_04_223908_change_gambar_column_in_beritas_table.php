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
        Schema::table('beritas', function (Blueprint $table) {
            // Ubah tipe data kolom gambar menjadi JSON/TEXT
            // Kita gunakan TEXT agar aman di berbagai database, atau JSON jika MySQL >= 5.7.8
            // Disini saya pakai text agar fleksibel, nanti di model dicast ke array.
            $table->text('gambar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('gambar')->nullable()->change();
        });
    }
};
