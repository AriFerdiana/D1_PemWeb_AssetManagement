<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Laboratorium') }}
        </h2>
    </x-slot>

    {{-- LOAD LEAFLET CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #map { height: 400px; width: 100%; border-radius: 0.5rem; z-index: 1; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Form Edit --}}
                <form action="{{ route('admin.labs.update', $lab->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- 1. Nama Laboratorium --}}
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nama Laboratorium</label>
                            <input type="text" name="name" value="{{ old('name', $lab->name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- 2. Program Studi --}}
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Program Studi</label>
                            <select name="prodi_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" {{ $lab->prodi_id == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Nama Gedung --}}
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nama Gedung</label>
                            <input type="text" name="building_name" value="{{ old('building_name', $lab->building_name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- 4. Nomor Ruangan --}}
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nomor Ruangan</label>
                            <input type="text" name="room_number" value="{{ old('room_number', $lab->room_number) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- 5. Kapasitas --}}
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Kapasitas (Orang)</label>
                            <input type="number" name="capacity" value="{{ old('capacity', $lab->capacity) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- 6. Deskripsi --}}
                        <div class="md:col-span-2">
                            <label class="block font-medium text-sm text-gray-700">Deskripsi</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $lab->description) }}</textarea>
                        </div>
                    </div>

                    <hr class="my-6">

                    {{-- BAGIAN PETA --}}
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Lokasi (Geser Pin untuk Mengubah)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                        {{-- Input Latitude --}}
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Latitude</label>
                            <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $lab->latitude) }}" readonly class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-gray-500 cursor-not-allowed">
                        </div>
                        
                        {{-- Input Longitude --}}
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Longitude</label>
                            <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $lab->longitude) }}" readonly class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm text-gray-500 cursor-not-allowed">
                        </div>
                        
                        <div class="flex items-end text-sm text-gray-500 pb-2">
                            *Otomatis terisi saat pin digeser
                        </div>
                    </div>

                    {{-- Container Peta --}}
                    <div id="map" class="mb-6 border border-gray-300"></div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('admin.labs.index') }}" class="text-gray-600 hover:text-gray-900">Batal</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- LOAD LEAFLET JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Tentukan Posisi Awal
            // Jika data ada di database, pakai itu. Jika tidak, pakai default (misal: Bandung)
            var savedLat = "{{ $lab->latitude }}";
            var savedLng = "{{ $lab->longitude }}";
            
            // Default ke Bandung (Gedung Sate / Kampus) jika kosong
            var initialLat = savedLat ? parseFloat(savedLat) : -6.896258; 
            var initialLng = savedLng ? parseFloat(savedLng) : 107.636172;

            // 2. Inisialisasi Peta
            var map = L.map('map').setView([initialLat, initialLng], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // 3. Buat Marker yang Bisa Digeser (Draggable)
            var marker = L.marker([initialLat, initialLng], {
                draggable: true // INI KUNCINYA
            }).addTo(map);

            // 4. Fungsi Update Input saat Pin Digeser
            function updateInputs(lat, lng) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }

            // Event: Saat marker selesai digeser (dragend)
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateInputs(position.lat, position.lng);
            });

            // Event: Saat peta diklik (pindahkan marker ke sana)
            map.on('click', function(e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;
                
                marker.setLatLng([lat, lng]); // Pindahkan marker
                updateInputs(lat, lng); // Update input form
            });
        });
    </script>
</x-app-layout>