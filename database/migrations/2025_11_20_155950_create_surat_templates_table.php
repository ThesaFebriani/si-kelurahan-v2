<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained()->onDelete('cascade');
            $table->string('nama_template');
            $table->string('file_path'); // Path ke file template blade
            $table->json('fields_mapping'); // Mapping field dari form ke template
            $table->text('template_content')->nullable(); // Konten template (opsional)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['jenis_surat_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_templates');
    }
};
