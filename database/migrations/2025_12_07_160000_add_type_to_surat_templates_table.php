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
            $table->enum('type', ['pengantar_rt', 'surat_kelurahan'])->default('surat_kelurahan')->after('jenis_surat_id');
            $table->string('file_path')->nullable()->change(); // Make nullable as we might use content directly
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_templates', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('file_path')->nullable(false)->change();
        });
    }
};
