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
            $table->string('tempat_lahir')->nullable()->after('name');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('agama')->nullable()->after('jk');
            $table->string('pekerjaan')->nullable()->after('agama');
            $table->string('status_perkawinan')->nullable()->after('pekerjaan');
            $table->string('golongan_darah')->nullable()->after('status_perkawinan');
            $table->string('kewarganegaraan')->default('WNI')->after('golongan_darah');
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
                'agama',
                'pekerjaan',
                'status_perkawinan',
                'golongan_darah',
                'kewarganegaraan'
            ]);
        });
    }
};
