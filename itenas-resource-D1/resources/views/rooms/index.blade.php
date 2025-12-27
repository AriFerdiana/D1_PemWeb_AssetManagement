<x-app-layout>
    {{-- JUDUL DIPERBAIKI DISINI --}}
    @section('header', 'Peminjaman Ruangan')

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            .map-container { height: 180px; width: 100%; border-radius: 0.5rem; z-index: 0; }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endpush

    <div class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($rooms as $room)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-lg transition border border-gray-200 dark:border-gray-700 flex flex-col h-full">
                
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">{{ $room->name }}</h3>
                        <p class="text-xs text-gray-500 uppercase tracking-wide mt-1">📍 {{ $room->building_name }}</p>
                    </div>
                    <div class="text-2xl">🏢</div>
                </div>
                
                <div class="mb-4 w-full">
                    @if($room->latitude && $room->longitude)
                        <div id="map-{{ $room->id }}" class="map-container border border-gray-200 dark:border-gray-600"></div>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var map{{ $room->id }} = L.map('map-{{ $room->id }}', {
                                    center: [{{ $room->latitude }}, {{ $room->longitude }}],
                                    zoom: 16,
                                    zoomControl: false, 
                                    dragging: false,    
                                    scrollWheelZoom: false
                                });

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap'
                                }).addTo(map{{ $room->id }});

                                L.marker([{{ $room->latitude }}, {{ $room->longitude }}]).addTo(map{{ $room->id }})
                                    .bindPopup("<b>{{ $room->name }}</b><br>{{ $room->building_name }}");
                            });
                        </script>
                    @else
                        <div class="h-[180px] bg-gray-100 dark:bg-gray-700 rounded-md flex flex-col items-center justify-center text-gray-400 border border-gray-200 dark:border-gray-600">
                            <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            <span class="text-xs">Lokasi Peta Belum Diatur</span>
                        </div>
                    @endif
                </div>
                
                <div class="text-gray-600 dark:text-gray-300 text-sm mb-6 flex-grow">
                    {{ Str::limit($room->description, 80) ?? 'Fasilitas lengkap untuk kegiatan akademik dan organisasi.' }}
                </div>
                
                <a href="{{ route('rooms.create', $room->id) }}" class="mt-auto block w-full text-center bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 font-bold shadow transition transform hover:scale-[1.02]">
                    📅 Booking Sekarang
                </a>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Belum ada data ruangan</h3>
                <p class="text-gray-500">Silakan hubungi admin untuk menambahkan data.</p>
            </div>
            @endforelse
        </div>
        
        <div class="mt-6">
            {{ $rooms->links() }}
        </div>
    </div>
</x-app-layout>