<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lampirans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained()->onDelete('cascade');
            $table->string('nama_file');
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->string('file_type')->nullable(); // pdf, jpg, png, dll
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('permohonan_surat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampirans');
    }
};
