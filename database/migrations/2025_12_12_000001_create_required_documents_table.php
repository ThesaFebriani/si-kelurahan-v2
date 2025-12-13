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
        if (Schema::hasTable('required_documents')) {
            return;
        }

        Schema::create('required_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained()->onDelete('cascade');
            $table->string('document_name'); // e.g. "KTP", "KK"
            $table->boolean('is_required')->default(true);
            $table->string('description')->nullable(); // Optional help text for user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('required_documents');
    }
};
