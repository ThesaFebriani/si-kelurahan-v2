<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keluargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rt_id')->constrained('rt')->onDelete('cascade');
            $table->string('no_kk')->unique();
            $table->string('kepala_keluarga');
            $table->text('alamat_lengkap');
            $table->string('kodepos');
            $table->timestamps();

            $table->index('rt_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
