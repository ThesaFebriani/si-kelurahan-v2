<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column exists first to avoid duplicate column error
        if (!Schema::hasColumn('surat_templates', 'rt_id')) {
            Schema::table('surat_templates', function (Blueprint $table) {
                // Add rt_id column
                $table->unsignedBigInteger('rt_id')->nullable()->after('type');
                
                // Add foreign key manually to avoid issues
                $table->foreign('rt_id')->references('id')->on('rts')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('surat_templates', 'rt_id')) {
            Schema::table('surat_templates', function (Blueprint $table) {
                // Drop foreign key first (Standard naming convention)
                $table->dropForeign(['rt_id']);
                $table->dropColumn('rt_id');
            });
        }
    }
};
