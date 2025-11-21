<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // email, whatsapp, system
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Data tambahan
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_id')->nullable(); // ID terkait (permohonan_surat_id, dll)
            $table->string('related_type')->nullable(); // Model terkait
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['related_id', 'related_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
