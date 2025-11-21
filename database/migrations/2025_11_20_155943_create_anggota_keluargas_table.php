<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_keluargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('keluarga_id')->constrained()->onDelete('cascade');
            $table->string('nik')->unique();
            $table->string('nama_lengkap');
            $table->enum('jk', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('status_hubungan', ['kepala_keluarga', 'istri', 'anak', 'lainnya']);
            $table->enum('status_perkawinan', ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati']);
            $table->string('agama');
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->string('kewarganegaraan')->default('WNI');
            $table->timestamps();

            $table->index(['keluarga_id', 'nik']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_keluargas');
    }
};
