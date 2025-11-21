<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained()->onDelete('cascade');
            $table->enum('step', ['rt', 'kasi', 'lurah']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('approved_by')->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->integer('urutan')->default(1);
            $table->timestamps();

            $table->index(['permohonan_surat_id', 'step']);
            $table->index(['approved_by', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_flows');
    }
};
