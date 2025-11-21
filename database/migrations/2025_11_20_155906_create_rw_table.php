<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rw', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rw', 3);
            $table->string('nama_ketua_rw')->nullable();
            $table->string('telepon_ketua_rw')->nullable();
            $table->text('alamat_ketua_rw')->nullable();
            $table->integer('jumlah_rt')->default(0);
            $table->integer('jumlah_penduduk')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('nomor_rw');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rw');
    }
};
