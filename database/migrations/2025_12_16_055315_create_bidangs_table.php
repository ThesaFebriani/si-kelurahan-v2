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
        Schema::create('bidangs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // kesra, pemerintahan, pembangunan
            $table->timestamps();
        });

        // Seed initial data
        $bidangs = [
            ['name' => 'Pemerintahan', 'code' => 'pemerintahan'],
            ['name' => 'Kesejahteraan Rakyat', 'code' => 'kesra'],
            ['name' => 'Pembangunan', 'code' => 'pembangunan'],
        ];

        foreach ($bidangs as $bidang) {
            \Illuminate\Support\Facades\DB::table('bidangs')->insert([
                'name' => $bidang['name'],
                'code' => $bidang['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidangs');
    }
};
