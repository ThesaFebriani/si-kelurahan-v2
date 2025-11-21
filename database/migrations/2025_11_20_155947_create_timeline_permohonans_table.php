<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timeline_permohonans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained()->onDelete('cascade');
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
            ]);
            $table->text('keterangan')->nullable();
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();

            $table->index(['permohonan_surat_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeline_permohonans');
    }
};
