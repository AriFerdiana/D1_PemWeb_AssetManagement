<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm uppercase font-bold">Total Aset</div>
                    <div class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $totalAssets }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm uppercase font-bold">Mahasiswa Aktif</div>
                    <div class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $totalUsers }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 text-sm uppercase font-bold">Total Peminjaman</div>
                    <div class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $totalReservations }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-gray-500 text-sm uppercase font-bold">Sedang Dipinjam</div>
                    <div class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ $activeLoans }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500 flex flex-col justify-center">
                    <div class="text-gray-500 text-sm uppercase font-bold">Pendapatan Denda ({{ date('F') }})</div>
                    <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
                    <p class="text-xs text-gray-400 mt-2 italic">*Hanya dari denda yang sudah dilunaskan</p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-4 uppercase text-center">Status Pembayaran Denda</h3>
                    <div class="relative h-40">
                        <canvas id="penaltyChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-4 uppercase">Top Aset "Penyumbang" Denda</h3>
                    <ul class="space-y-3">
                        @forelse($lateAssets as $asset)
                        <li class="flex justify-between items-center text-sm border-b border-gray-100 dark:border-gray-700 pb-1">
                            <span class="text-gray-600 dark:text-gray-400 truncate mr-2">{{ $asset->name }}</span>
                            <span class="font-bold text-red-500 text-xs shrink-0">Rp {{ number_format($asset->total_penalty, 0, ',', '.') }}</span>
                        </li>
                        @empty
                        <li class="text-gray-400 italic text-xs text-center py-4">Belum ada data denda terkumpul.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Status Reservasi</h3>
                    <div class="relative h-64 flex justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Tren Bulanan Tahun {{ date('Y') }}</h3>
                    <div class="relative h-64">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Top 5 Prodi Teraktif</h3>
                    <div class="relative h-64">
                        <canvas id="prodiChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Top 5 Aset Terlaris</h3>
                    <div class="relative h-64">
                        <canvas id="topAssetsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // --- 1. STATUS CHART (DOUGHNUT) ---
            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected', 'Returned'],
                    datasets: [{
                        data: [
                            {{ $reservationStats['pending'] ?? 0 }},
                            {{ $reservationStats['approved'] ?? 0 }},
                            {{ $reservationStats['rejected'] ?? 0 }},
                            {{ $reservationStats['returned'] ?? 0 }}
                        ],
                        backgroundColor: ['#F59E0B', '#10B981', '#EF4444', '#3B82F6'],
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // --- 2. MONTHLY TREND (LINE) ---
            new Chart(document.getElementById('monthlyTrendChart'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Total Transaksi',
                        data: {!! json_encode($monthlyData) !!},
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // --- 3. PRODI CHART (BAR) ---
            new Chart(document.getElementById('prodiChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode(array_keys($prodiStats)) !!},
                    datasets: [{
                        label: 'Peminjaman',
                        data: {!! json_encode(array_values($prodiStats)) !!},
                        backgroundColor: '#8B5CF6',
                        borderRadius: 5
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // --- 4. TOP ASSETS (HORIZONTAL BAR) ---
            new Chart(document.getElementById('topAssetsChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($assetLabels) !!},
                    datasets: [{
                        label: 'Total Unit',
                        data: {!! json_encode($assetData) !!},
                        backgroundColor: '#EC4899',
                        borderRadius: 5
                    }]
                },
                options: { 
                    indexAxis: 'y', 
                    responsive: true, 
                    maintainAspectRatio: false 
                }
            });

            // --- 5. PENALTY STATUS CHART (PIE) ---
            new Chart(document.getElementById('penaltyChart'), {
                type: 'pie',
                data: {
                    labels: ['Lunas', 'Belum Bayar'],
                    datasets: [{
                        data: [{{ $penaltyStats['paid'] }}, {{ $penaltyStats['unpaid'] }}],
                        backgroundColor: ['#10B981', '#EF4444'],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                    }
                }
            });
        });
    </script>
</x-app-layout>