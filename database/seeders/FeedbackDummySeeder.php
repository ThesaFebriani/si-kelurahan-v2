<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermohonanSurat;
use App\Models\SurveiKepuasan;

class FeedbackDummySeeder extends Seeder
{
    public function run()
    {
        // Get all completed surats that don't have feedback yet
        $completedSurats = PermohonanSurat::where('status', PermohonanSurat::SELESAI)->get();

        if ($completedSurats->isEmpty()) {
            $this->command->info('No completed surats found to seed feedback.');
            return;
        }

        $count = 0;
        foreach ($completedSurats as $surat) {
            // Check if feedback already exists
            if (SurveiKepuasan::where('permohonan_surat_id', $surat->id)->exists()) {
                continue;
            }

            // Create Dummy Feedback (Mostly good ratings 4-5)
            SurveiKepuasan::create([
                'permohonan_surat_id' => $surat->id,
                'rating' => rand(4, 5), // High satisfaction
                'kritik_saran' => 'Pelayanan sangat cepat dan memuaskan. Terima kasih!',
                'created_at' => $surat->updated_at->addHour(), // Feedback 1 hour after finish
            ]);
            $count++;
        }

        $this->command->info("Seeded {$count} feedback entries.");
    }
}
