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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'tempat_lahir')) {
                $table->string('tempat_lahir')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('users', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            }
            if (!Schema::hasColumn('users', 'pekerjaan')) {
                $table->string('pekerjaan')->nullable()->after('tanggal_lahir');
            }
            if (!Schema::hasColumn('users', 'agama')) {
                $table->string('agama')->nullable()->after('pekerjaan');
            }
            if (!Schema::hasColumn('users', 'status_perkawinan')) {
                $table->string('status_perkawinan')->nullable()->after('agama');
            }
            if (!Schema::hasColumn('users', 'kewarganegaraan')) {
                $table->string('kewarganegaraan')->default('WNI')->after('status_perkawinan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir', 
                'tanggal_lahir', 
                'pekerjaan', 
                'agama', 
                'status_perkawinan', 
                'kewarganegaraan'
            ]);
        });
    }
};
