<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // RELASI
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->foreignId('rt_id')->nullable()->constrained('rt')->onDelete('cascade');

            // DATA PRIBADI
            $table->string('nik')->unique();
            $table->string('name');
            $table->enum('jk', ['laki-laki', 'perempuan']);
            $table->string('telepon')->nullable();
            $table->text('alamat');

            // AUTH
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // TAMBAHAN
            $table->string('foto')->default('profile.jpg');
            $table->string('jabatan')->nullable();
            $table->string('bidang')->nullable();
            $table->string('wilayah')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
