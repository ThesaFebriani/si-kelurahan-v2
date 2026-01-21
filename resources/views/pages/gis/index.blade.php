@extends('components.layout')

@section('title', 'Peta Persebaran Penduduk & Wilayah')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    #map { height: 600px; width: 100%; border-radius: 0.75rem; z-index: 1; }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-map-marked-alt text-blue-600 mr-2"></i> Peta Digital Wilayah
                </h2>
                <p class="text-slate-500 text-sm mt-1">
                    Visualisasi sebaran RT, kepadatan penduduk, dan infrastruktur wilayah.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-lg border border-blue-100">
                    <i class="fas fa-layer-group mr-1"></i> {{ count($locations) }} Titik RT Terdata
                </span>
            </div>
        </div>

        <!-- Map Container -->
        <div class="relative shadow-inner rounded-xl border border-slate-200 overflow-hidden">
            <div id="map"></div>
        </div>
    </div>
    
    <!-- Legend / Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($locations->take(3) as $loc)
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-start gap-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold" style="background-color: {{ $loc['color'] }}">
                {{ $loc['nomor_rt'] }}
            </div>
            <div>
                <h4 class="font-bold text-slate-800">RT {{ $loc['nomor_rt'] }} / RW {{ $loc['nomor_rw'] }}</h4>
                <p class="text-xs text-slate-500">Ketua: {{ $loc['ketua'] ?? '-' }}</p>
                <div class="mt-2 flex gap-3 text-xs font-medium text-slate-600">
                    <span><i class="fas fa-users text-blue-400 mr-1"></i> {{ $loc['penduduk'] }} Jiwa</span>
                    <span><i class="fas fa-home text-green-400 mr-1"></i> {{ $loc['keluarga'] }} KK</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const center = [{{ $center['lat'] }}, {{ $center['lng'] }}];
        const map = L.map('map').setView(center, 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        const locations = @json($locations);

        locations.forEach(loc => {
            const marker = L.circleMarker([loc.lat, loc.lng], {
                radius: 10,
                fillColor: loc.color,
                color: "#fff",
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(map);

            const popupContent = `
                <div class="p-2 min-w-[200px]">
                    <h3 class="font-bold text-base mb-1">RT ${loc.nomor_rt} / RW ${loc.nomor_rw}</h3>
                    <p class="text-xs text-gray-500 mb-2 font-semibold">Ketua: ${loc.ketua || '-'}</p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-blue-50 p-2 rounded">
                            <span class="block text-blue-600 font-bold">${loc.penduduk}</span>
                            <span class="text-gray-500">Penduduk</span>
                        </div>
                        <div class="bg-green-50 p-2 rounded">
                            <span class="block text-green-600 font-bold">${loc.keluarga}</span>
                            <span class="text-gray-500">Keluarga</span>
                        </div>
                    </div>
                </div>
            `;

            marker.bindPopup(popupContent);
        });
    });
</script>
@endpush
