<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonan_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jenis_surat_id')->constrained()->onDelete('cascade');

            // Tracking
            $table->string('nomor_tiket')->unique();
            $table->enum('status', [
                'menunggu_rt',
                'disetujui_rt',
                'ditolak_rt',
                'menunggu_kasi',
                'disetujui_kasi',
                'ditolak_kasi',
                'menunggu_lurah',
                'selesai',
                'dibatalkan'
            ])->default('menunggu_rt');

            // Data dynamic dari form
            $table->json('data_pemohon');
            $table->text('keterangan_tolak')->nullable();

            // Nomor surat
            $table->string('nomor_surat_final')->nullable();
            $table->string('file_surat_pengantar_rt')->nullable();

            $table->timestamp('tanggal_pengajuan')->useCurrent();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['jenis_surat_id', 'status']);
            $table->index('nomor_tiket');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_surats');
    }
};
