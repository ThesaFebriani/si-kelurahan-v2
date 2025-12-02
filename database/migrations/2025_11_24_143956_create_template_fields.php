<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('template_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained()->onDelete('cascade');
            $table->string('field_name'); // nik, nama, tempat_lahir, dll
            $table->string('field_label'); // NIK, Nama Lengkap, Tempat Lahir
            $table->enum('field_type', ['text', 'number', 'date', 'textarea', 'select', 'file']);
            $table->json('options')->nullable(); // Untuk select: ['Laki-laki','Perempuan']
            $table->boolean('required')->default(true);
            $table->string('validation_rules')->nullable(); // required|string|max:255
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('required_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained()->onDelete('cascade');
            $table->string('document_name'); // ktp, kk, surat_nikah
            $table->string('document_label'); // KTP, Kartu Keluarga, Surat Nikah
            $table->boolean('required')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('required_documents');
        Schema::dropIfExists('template_fields');
    }
};
