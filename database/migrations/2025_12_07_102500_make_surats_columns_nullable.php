<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            $table->foreignId('signed_by')->nullable()->change();
            $table->string('file_path')->nullable()->change();
            $table->string('nomor_surat')->nullable()->change(); 
        });
    }

    public function down(): void
    {
        Schema::table('surats', function (Blueprint $table) {
            // Reverting requires knowing the original state (not nullable). 
            // In a real app, this might fail if there are nulls. 
            // We'll leave down() simplified or empty for this specific "development" context fix
            // or try to revert if sure.
        });
    }
};
