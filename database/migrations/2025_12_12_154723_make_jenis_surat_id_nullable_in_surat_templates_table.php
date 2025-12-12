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
        Schema::table('surat_templates', function (Blueprint $table) {
            $table->foreignId('jenis_surat_id')->nullable()->change();
            $table->string('nama_template')->nullable()->change();
            $table->json('fields_mapping')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_templates', function (Blueprint $table) {
            // Reverting might be tricky if nulls exist, but let's try strict mode
            $table->foreignId('jenis_surat_id')->nullable(false)->change();
            $table->string('nama_template')->nullable(false)->change();
            $table->json('fields_mapping')->nullable(false)->change();
        });
    }
};
