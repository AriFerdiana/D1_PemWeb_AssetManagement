<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laboratorium') }}
        </h2>
    </x-slot>

    {{-- LANGSUNG LOAD CSS PETA DISINI (SUPAYA PASTI JALAN) --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #map { z-index: 1; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- BAGIAN 1: INFO UTAMA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $lab->name }}</h1>
                        <p class="text-md text-gray-600 mt-1">
                            <span class="font-semibold">Prodi:</span> {{ $lab->prodi->name ?? '-' }} | 
                            <span class="font-semibold">Lokasi:</span> {{ $lab->building_name }} - Ruang {{ $lab->room_number }}
                        </p>
                        <p class="text-sm text-gray-500 mt-2 italic">
                            "{{ $lab->description ?? 'Tidak ada deskripsi' }}"
                        </p>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg text-sm font-bold">
                            Kapasitas: {{ $lab->capacity }} Orang
                        </span>
                        <a href="{{ route('admin.labs.edit', $lab->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900 underline">
                            Edit Data
                        </a>
                    </div>
                </div>
            </div>

            {{-- BAGIAN 2: PETA LOKASI --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Lokasi Gedung</h3>
                
                @if(!empty($lab->latitude) && !empty($lab->longitude))
                    {{-- Container Peta dengan Tinggi Manual (Style Inline) untuk memastikan tidak gepeng --}}
                    <div id="map" style="width: 100%; height: 400px; border-radius: 0.5rem; border: 1px solid #e5e7eb;"></div>
                    
                    <p class="text-xs text-gray-400 mt-2">
                        Koordinat: {{ $lab->latitude }}, {{ $lab->longitude }}
                    </p>
                @else
                    <div class="w-full h-40 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-500">
                        <p>Koordinat lokasi belum diatur.</p>
                        <a href="{{ route('admin.labs.edit', $lab->id) }}" class="text-blue-500 hover:underline text-sm mt-1">
                            Klik disini untuk atur lokasi
                        </a>
                    </div>
                @endif
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.labs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow">
                    &laquo; Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    {{-- LANGSUNG LOAD JS PETA DISINI (SUPAYA PASTI JALAN) --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        // Kita bungkus dalam try-catch supaya kalau error tidak merusak halaman lain
        try {
            var mapElement = document.getElementById('map');
            
            // Cek apakah elemen map ada di layar
            if (mapElement) {
                // Ambil data (Pastikan angka, bukan string kosong)
                var lat = parseFloat("{{ $lab->latitude }}");
                var lng = parseFloat("{{ $lab->longitude }}");

                // Cek apakah angkanya valid
                if (!isNaN(lat) && !isNaN(lng)) {
                    var map = L.map('map').setView([lat, lng], 18);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    L.marker([lat, lng]).addTo(map)
                        .bindPopup("<b>{{ $lab->name }}</b><br>{{ $lab->building_name }}")
                        .openPopup();
                    
                    // Paksa peta untuk refresh ukurannya setelah loading
                    setTimeout(function(){ map.invalidateSize(); }, 500);
                }
            }
        } catch (error) {
            console.error("Gagal memuat peta:", error);
        }
    </script>
</x-app-layout>