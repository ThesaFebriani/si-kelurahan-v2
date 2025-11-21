<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained()->onDelete('cascade');
            $table->string('nomor_surat')->unique();
            $table->string('file_path'); // Path file PDF
            $table->string('file_size')->nullable();
            $table->string('checksum')->nullable(); // Untuk verifikasi integritas file
            $table->foreignId('signed_by')->constrained('users'); // Lurah yang menandatangani
            $table->timestamp('signed_at')->nullable();
            $table->text('qr_code_data')->nullable(); // Data untuk QR code
            $table->timestamps();

            $table->index(['permohonan_surat_id', 'nomor_surat']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
