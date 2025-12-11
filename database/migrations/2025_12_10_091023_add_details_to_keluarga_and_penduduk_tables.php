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
        // Add columns to keluarga table
        Schema::table('keluargas', function (Blueprint $table) {
            $table->string('desa_kelurahan')->nullable()->after('alamat_lengkap');
            $table->string('kecamatan')->nullable()->after('desa_kelurahan');
            $table->string('kabupaten_kota')->nullable()->after('kecamatan');
            $table->string('provinsi')->nullable()->after('kabupaten_kota');
        });

        // Add columns to anggota_keluargas table
        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->date('tanggal_perkawinan')->nullable()->after('status_perkawinan');
            $table->string('no_paspor')->nullable()->after('kewarganegaraan'); // Assuming kewarganegaraan exists, or append at end
            $table->string('no_kitap')->nullable()->after('no_paspor');
            $table->string('nama_ayah')->nullable()->after('no_kitap');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {
            $table->dropColumn(['desa_kelurahan', 'kecamatan', 'kabupaten_kota', 'provinsi']);
        });

        Schema::table('anggota_keluargas', function (Blueprint $table) {
            $table->dropColumn(['tanggal_perkawinan', 'no_paspor', 'no_kitap', 'nama_ayah', 'nama_ibu']);
        });
    }
};
