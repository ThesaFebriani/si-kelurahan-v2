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
        Schema::table('permohonan_surats', function (Blueprint $table) {
            $table->string('nomor_surat_pengantar_rt')->nullable()->after('file_surat_pengantar_rt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_surats', function (Blueprint $table) {
            $table->dropColumn('nomor_surat_pengantar_rt');
        });
    }
};
