<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_surats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('kode_surat')->unique();
            $table->text('persyaratan');
            $table->string('bidang'); // kesra, pemerintahan, pembangunan
            $table->string('template_name')->nullable();
            $table->integer('estimasi_hari')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['bidang', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_surats');
    }
};
