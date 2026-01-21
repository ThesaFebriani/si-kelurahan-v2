<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rt;

class GisDummySeeder extends Seeder
{
    public function run()
    {
        // Bengkulu City Center approx coords
        $centerLat = -3.800444; 
        $centerLng = 102.265541;

        $rts = Rt::all();
        
        foreach ($rts as $index => $rt) {
            // Generate random small offset to scatter points around center
            $latOffset = (rand(-50, 50) / 10000); 
            $lngOffset = (rand(-50, 50) / 10000);
            
            $rt->update([
                'latitude' => $centerLat + $latOffset,
                'longitude' => $centerLng + $lngOffset,
                'warna_wilayah' => '#' . substr(md5(rand()), 0, 6) // Random Hex Color
            ]);
        }

        $this->command->info("Updated coordinates for {$rts->count()} RTs.");
    }
}
