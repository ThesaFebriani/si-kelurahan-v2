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
        if (!Schema::hasTable('template_fields')) {
            Schema::create('template_fields', function (Blueprint $table) {
                $table->id();
                $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->onDelete('cascade');
                $table->string('field_key'); // e.g. nama_usaha
                $table->string('field_label'); // e.g. Nama Usaha
                $table->enum('field_type', ['text', 'number', 'date', 'textarea', 'dropdown'])->default('text');
                $table->text('options')->nullable(); // For dropdown options (JSON or comma separated)
                $table->boolean('is_required')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_fields_table_fixed');
    }
};
