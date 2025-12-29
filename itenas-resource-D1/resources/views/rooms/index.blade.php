<x-app-layout>
    @section('header', 'Peminjaman Ruangan')

    {{-- Masukkan CSS Leaflet --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            .mini-map { height: 200px; width: 100%; z-index: 1; border-bottom: 1px solid #e5e7eb; }
            .leaflet-control-attribution { display: none !important; }
        </style>
    @endpush

    {{-- Masukkan JS Leaflet --}}
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endpush

    <div class="space-y-6">
        
        {{-- 1. SEARCH & FILTER --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            {{-- Ubah grid-cols-4 menjadi grid-cols-5 agar muat dropdown baru --}}
            <form method="GET" action="{{ route('rooms.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                
                {{-- [PERBAIKAN 1: DROPDOWN JUMLAH BARIS] --}}
                <div class="relative">
                    <select name="per_page" onchange="this.form.submit()" class="w-full pl-4 pr-8 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-[#E65100] appearance-none font-bold text-center cursor-pointer">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Data</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 Data</option>
                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30 Data</option>
                        <option value="40" {{ request('per_page') == 40 ? 'selected' : '' }}>40 Data</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Data</option>
                    </select>
                    <i class="fas fa-list-ol absolute right-3 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                </div>

                {{-- Input Search (Span 2 kolom) --}}
                <div class="md:col-span-2 relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama ruangan..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-[#E65100] transition">
                    <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                </div>

                {{-- Dropdown Filter Gedung --}}
                <div class="relative">
                    <select name="gedung" class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-[#E65100] appearance-none">
                        <option value="">-- Semua Gedung --</option>
                        @foreach($buildings as $gedung)
                            <option value="{{ $gedung }}" {{ request('gedung') == $gedung ? 'selected' : '' }}>
                                {{ $gedung }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fas fa-building absolute left-3 top-3.5 text-gray-400"></i>
                    <i class="fas fa-chevron-down absolute right-3 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-[#E65100] hover:bg-orange-700 text-white font-bold py-2.5 rounded-lg transition shadow-md">
                        Terapkan
                    </button>
                    @if(request('search') || request('gedung') || request('per_page'))
                        <a href="{{ route('rooms.index') }}" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition flex items-center justify-center" title="Reset Filter">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- 2. GRID RUANGAN (DENGAN PETA KECIL) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($rooms as $room)
                {{-- Kartu Ruangan (Wrapper) --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition duration-300 border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col h-full group">
                    
                    {{-- AREA PETA (MAP) --}}
                    @if($room->latitude && $room->longitude)
                        {{-- Container Peta Unik per ID --}}
                        <div id="map-{{ $room->id }}" class="mini-map bg-gray-100"></div>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Inisialisasi Peta untuk ID spesifik ini
                                var map{{ $room->id }} = L.map('map-{{ $room->id }}', {
                                    center: [{{ $room->latitude }}, {{ $room->longitude }}],
                                    zoom: 17, 
                                    zoomControl: false, // Hilangkan kontrol zoom agar bersih
                                    dragging: false,    // Matikan geser agar tidak mengganggu scroll halaman
                                    scrollWheelZoom: false, // Matikan scroll zoom
                                    doubleClickZoom: false,
                                    boxZoom: false
                                });

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: ''
                                }).addTo(map{{ $room->id }});

                                L.marker([{{ $room->latitude }}, {{ $room->longitude }}]).addTo(map{{ $room->id }});
                            });
                        </script>
                    @else
                        {{-- Fallback jika tidak ada koordinat --}}
                        <div class="mini-map bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            <span class="text-xs">Lokasi Peta Belum Diatur</span>
                        </div>
                    @endif

                    {{-- AREA KONTEN (JUDUL & TOMBOL) --}}
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-[#E65100] transition">
                                    {{ $room->name }}
                                </h3>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mt-1 flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-red-500"></i> {{ $room->building_name }}
                                </p>
                            </div>
                        </div>

                        <p class="text-gray-600 dark:text-gray-300 text-sm mt-3 mb-6 line-clamp-2">
                            {{ $room->description ?? 'Ruangan ini tersedia untuk dipinjam oleh civitas akademika.' }}
                        </p>

                        {{-- Tombol Booking (Full Width) --}}
                        <a href="{{ route('rooms.create', $room->id) }}" class="mt-auto block w-full text-center bg-[#1E1B4B] hover:bg-[#312E81] text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-md transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                            <i class="far fa-calendar-check"></i> Booking Ruangan
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white dark:bg-gray-800 rounded-xl border-dashed border-2 border-gray-300 dark:border-gray-700">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Belum ada data ruangan</h3>
                    <p class="text-gray-500">Silakan hubungi admin untuk menambahkan data.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-6">
            {{ $rooms->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>