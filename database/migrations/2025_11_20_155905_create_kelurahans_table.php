<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelurahan');
            $table->string('kode_kelurahan')->unique();
            $table->string('alamat_kantor');
            $table->string('telepon');
            $table->string('email');
            $table->string('logo')->default('logo-kelurahan.png');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('provinsi');
            $table->string('kodepos');
            $table->string('nama_lurah');
            $table->string('nip_lurah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelurahans');
    }
};
