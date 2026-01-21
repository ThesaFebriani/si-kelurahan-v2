<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rt;
use App\Models\SystemSetting;

class GisController extends Controller
{
    public function index()
    {
        // Ambil Data RT yang memiliki koordinat
        $locations = Rt::with('rw')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function($rt) {
                return [
                    'id' => $rt->id,
                    'nomor_rt' => $rt->nomor_rt,
                    'nomor_rw' => $rt->rw->nomor_rw ?? '-',
                    'penduduk' => $rt->jumlah_penduduk,
                    'keluarga' => $rt->jumlah_keluarga,
                    'lat' => $rt->latitude,
                    'lng' => $rt->longitude,
                    'color' => $rt->warna_wilayah ?? '#3b82f6',
                    'ketua' => $rt->nama_ketua_rt
                ];
            });

        // Setting pusat peta (bisa dari setting instansi atau hardcode Default Bengkulu)
        $center = [
            'lat' => -3.800444, 
            'lng' => 102.265541
        ];

        return view('pages.gis.index', compact('locations', 'center'));
    }
}
