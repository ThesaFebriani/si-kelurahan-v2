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
        // Index untuk tabel permohonan_surats
        Schema::table('permohonan_surats', function (Blueprint $table) {
            $table->index(['created_at', 'status'], 'idx_ps_date_status');
            $table->index(['tanggal_selesai'], 'idx_ps_tgl_selesai');
        });
        
        // Index untuk approval_flows
        Schema::table('approval_flows', function (Blueprint $table) {
            $table->index(['created_at', 'approved_at'], 'idx_af_times');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_surats', function (Blueprint $table) {
            $table->dropIndex('idx_ps_date_status');
            $table->dropIndex('idx_ps_tgl_selesai');
        });
        
         Schema::table('approval_flows', function (Blueprint $table) {
            $table->dropIndex('idx_af_times');
        });
    }
};
