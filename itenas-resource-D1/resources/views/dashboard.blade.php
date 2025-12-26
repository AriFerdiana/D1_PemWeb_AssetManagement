<x-app-layout>
    @section('header')
        @hasrole('Superadmin|Laboran') Dashboard Eksekutif @else Beranda Mahasiswa @endhasrole
    @endsection

    @hasrole('Superadmin|Laboran')
        {{-- ========================================== --}}
        {{-- ADMIN: 4 STATS CARDS                       --}}
        {{-- ========================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border-b-4 border-teal-500 hover:-translate-y-1 transition">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Aset</p>
                <p class="text-3xl font-black text-gray-800 mt-1">{{ $totalAssets }}</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border-b-4 border-blue-500 hover:-translate-y-1 transition">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Sedang Dipinjam</p>
                <p class="text-3xl font-black text-gray-800 mt-1">{{ $activeLoans }}</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border-b-4 border-purple-500 hover:-translate-y-1 transition">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Maintenance</p>
                <p class="text-3xl font-black text-red-500 mt-1">{{ $totalMaintenance }}</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border-b-4 border-orange-500 hover:-translate-y-1 transition">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Transaksi</p>
                <p class="text-3xl font-black text-gray-800 mt-1">{{ $totalReservations }}</p>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- ADMIN: 7 GRAFIK LENGKAP                    --}}
        {{-- ========================================== --}}
        
        {{-- BARIS 1: TREN & STATUS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">📈 Tren Peminjaman</h3>
                <div class="h-64"><canvas id="chartTren"></canvas></div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">📊 Status Transaksi</h3>
                <div class="h-64 flex justify-center"><canvas id="chartStatus"></canvas></div>
            </div>
        </div>

        {{-- BARIS 2: KONDISI, KATEGORI, KEPATUHAN --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">🛠️ Kondisi Aset</h3>
                <div class="h-64 flex justify-center"><canvas id="chartCondition"></canvas></div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">📂 Kategori Aset</h3>
                <div class="h-64 flex justify-center"><canvas id="chartCategory"></canvas></div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">⏱️ Kepatuhan</h3>
                <div class="h-64 flex justify-center"><canvas id="chartCompliance"></canvas></div>
            </div>
        </div>

        {{-- BARIS 3: TOP PRODI & TOP ASET --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">🎓 Top 5 Prodi Peminjam</h3>
                <div class="h-64"><canvas id="chartProdi"></canvas></div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">🏆 Aset Terlaris</h3>
                <div class="h-64"><canvas id="chartTopAsset"></canvas></div>
            </div>
        </div>

        {{-- SCRIPTS SEMUA GRAFIK --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // 1. TREN
                new Chart(document.getElementById('chartTren'), {
                    type: 'line',
                    data: {
                        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                        datasets: [{ label: 'Transaksi', data: {!! json_encode($monthlyData) !!}, borderColor: '#F97316', tension: 0.4, fill: true, backgroundColor: 'rgba(249, 115, 22, 0.1)' }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
                // 2. STATUS
                new Chart(document.getElementById('chartStatus'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($statusLabels) !!},
                        datasets: [{ data: {!! json_encode($statusData) !!}, backgroundColor: ['#3b82f6','#eab308','#22c55e','#ef4444','#a855f7'] }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
                // 3. KONDISI
                new Chart(document.getElementById('chartCondition'), {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($conditionLabels) !!},
                        datasets: [{ data: {!! json_encode($conditionData) !!}, backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#64748b'] }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
                // 4. KATEGORI
                new Chart(document.getElementById('chartCategory'), {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode($categoryLabels) !!},
                        datasets: [{ data: {!! json_encode($categoryData) !!}, backgroundColor: ['#6366f1', '#ec4899', '#8b5cf6', '#14b8a6', '#f59e0b'] }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
                // 5. KEPATUHAN
                new Chart(document.getElementById('chartCompliance'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($complianceLabels) !!},
                        datasets: [{ data: {!! json_encode($complianceData) !!}, backgroundColor: ['#22c55e', '#ef4444'] }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
                // 6. PRODI
                new Chart(document.getElementById('chartProdi'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($prodiLabels) !!},
                        datasets: [{ label: 'Peminjam', data: {!! json_encode($prodiData) !!}, backgroundColor: '#6366f1' }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
                // 7. TOP ASET
                new Chart(document.getElementById('chartTopAsset'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($topAssetLabels) !!},
                        datasets: [{ label: 'Dipinjam', data: {!! json_encode($topAssetData) !!}, backgroundColor: '#F97316' }]
                    },
                    options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false }
                });
            });
        </script>

    @else
        {{-- ========================================== --}}
        {{-- MAHASISWA: FITUR LENGKAP                   --}}
        {{-- ========================================== --}}
        <div class="space-y-6">
            <div class="bg-gray-800 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-gray-300 mb-6 max-w-xl">Selamat datang. Cek status peminjaman dan denda aset Anda di sini.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('assets.index') }}" class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-bold shadow-md">
                            <i class="fas fa-search mr-2"></i> Pinjam Alat
                        </a>
                        <a href="{{ route('reservations.assets') }}" class="px-5 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-bold border border-gray-600">
                            <i class="fas fa-list mr-2"></i> Cek Status
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <p class="text-gray-500 text-sm">Sedang Saya Pinjam</p>
                    <h3 class="text-3xl font-bold mt-1">{{ \App\Models\Reservation::where('user_id', Auth::id())->where('status', 'borrowed')->count() }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                    <p class="text-gray-500 text-sm">Menunggu Approval</p>
                    <h3 class="text-3xl font-bold mt-1">{{ \App\Models\Reservation::where('user_id', Auth::id())->where('status', 'pending')->count() }}</h3>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                    <p class="text-gray-500 text-sm">Tagihan Denda</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format(\App\Models\Reservation::where('user_id', Auth::id())->where('penalty_status', 'unpaid')->sum('penalty')) }}</h3>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-6 border-b flex justify-between items-center text-gray-800">
                    <h3 class="font-bold">Peminjaman Aktif Saya</h3>
                    <a href="{{ route('reservations.assets') }}" class="text-sm text-orange-500 hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto text-gray-800">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs font-bold">
                            <tr>
                                <th class="px-6 py-4">Barang/Lab</th>
                                <th class="px-6 py-4">Tenggat</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse(\App\Models\Reservation::with(['asset','lab'])->where('user_id', Auth::id())->whereIn('status', ['borrowed', 'pending', 'approved'])->latest()->take(3)->get() as $item)
                                <tr>
                                    <td class="px-6 py-4 font-bold">{{ $item->asset->name ?? $item->lab->name ?? 'Item' }}</td>
                                    <td class="px-6 py-4 text-red-500">{{ $item->end_time ? $item->end_time->format('d M Y') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700">{{ $item->status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">Tidak ada peminjaman aktif.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endhasrole
</x-app-layout>