<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rw_id')->constrained('rw')->onDelete('cascade');
            $table->string('nomor_rt', 3);
            $table->string('nama_ketua_rt')->nullable();
            $table->string('telepon_ketua_rt')->nullable();
            $table->text('alamat_ketua_rt')->nullable();
            $table->integer('jumlah_keluarga')->default(0);
            $table->integer('jumlah_penduduk')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['rw_id', 'nomor_rt']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rt');
    }
};
