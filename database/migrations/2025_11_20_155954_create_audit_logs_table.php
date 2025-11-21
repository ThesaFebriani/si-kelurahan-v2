<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // create, update, delete, approve, reject
            $table->string('description');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('model_type'); // Model yang di-audit
            $table->unsignedBigInteger('model_id'); // ID model yang di-audit
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
