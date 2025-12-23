<x-app-layout>
    @section('header')
        @hasrole('Superadmin|Laboran')
            Dashboard Eksekutif
        @else
            Beranda Mahasiswa
        @endhasrole
    @endsection

    <div class="space-y-6">

        {{-- ================================================================= --}}
        {{-- BAGIAN 1: TAMPILAN KHUSUS MAHASISWA (USER BIASA)                 --}}
        {{-- Hanya muncul jika user TIDAK punya role Superadmin/Laboran       --}}
        {{-- ================================================================= --}}
        @unlessrole('Superadmin|Laboran')
            
            <div class="bg-gray-800 dark:bg-gray-900 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-gray-300 mb-6 max-w-xl">Selamat datang. Cek status peminjaman dan denda aset Anda di sini.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('assets.index') }}" class="px-5 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-bold transition shadow-md border border-teal-400">
                            <i class="fas fa-search mr-2"></i> Pinjam Alat
                        </a>
                        <a href="{{ route('reservations.assets') }}" class="px-5 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-bold transition border border-gray-600">
                            <i class="fas fa-list mr-2"></i> Cek Status
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-card border-l-4 border-blue-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Sedang Saya Pinjam</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                {{ \App\Models\Reservation::where('user_id', Auth::id())->where('status', 'borrowed')->count() }}
                            </h3>
                            <p class="text-xs text-blue-500 mt-2">Barang belum kembali</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/30 p-3 rounded-lg text-blue-600 dark:text-blue-400">
                            <i class="fas fa-hand-holding"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-card border-l-4 border-yellow-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Menunggu Approval</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                                {{ \App\Models\Reservation::where('user_id', Auth::id())->where('status', 'pending')->count() }}
                            </h3>
                            <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-2">Pengajuan baru</p>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 p-3 rounded-lg text-yellow-600 dark:text-yellow-400">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-card border-l-4 border-red-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Tagihan Denda</p>
                            @php
                                // Menggunakan null coalescing operator ?? 0 untuk mencegah error jika kolom belum ada
                                $totalDenda = \App\Models\Reservation::where('user_id', Auth::id())
                                    ->where('penalty_status', 'unpaid')
                                    ->sum('penalty_amount') ?? 0;
                            @endphp
                            <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                                Rp {{ number_format($totalDenda) }}
                            </h3>
                            <p class="text-xs text-red-500 dark:text-red-400 mt-2">Harap lunasi</p>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/30 p-3 rounded-lg text-red-600 dark:text-red-400">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white">Peminjaman Aktif Saya</h3>
                    <a href="{{ route('reservations.assets') }}" class="text-sm text-teal-600 dark:text-teal-400 hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">Barang</th>
                                <th class="px-6 py-3">Tgl Pinjam</th>
                                <th class="px-6 py-3">Tenggat</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse(\App\Models\Reservation::where('user_id', Auth::id())->whereIn('status', ['borrowed', 'pending', 'approved'])->latest()->take(3)->get() as $item)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $item->asset->name ?? 'Item Terhapus' }}</td>
                                    <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $item->start_time ? $item->start_time->format('d M Y') : '-' }}</td>
                                    <td class="px-6 py-4 text-red-500 font-bold">{{ $item->end_time ? $item->end_time->format('d M Y') : '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs font-bold 
                                            {{ $item->status == 'borrowed' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300' : 
                                              ($item->status == 'approved' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada peminjaman aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endunlessrole


        {{-- ================================================================= --}}
        {{-- BAGIAN 2: TAMPILAN KHUSUS ADMIN (SUPERADMIN & LABORAN)           --}}
        {{-- ================================================================= --}}
        @hasrole('Superadmin|Laboran')
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card p-6 border-l-4 border-teal-500 hover:shadow-lg transition">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Aset</p>
                            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-white">{{ \App\Models\Asset::count() }}</p>
                        </div>
                        <div class="bg-teal-50 dark:bg-teal-900/30 p-3 rounded-lg text-teal-600 dark:text-teal-400"><i class="fas fa-cube text-2xl"></i></div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Sedang Dipinjam</p>
                            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-white">{{ \App\Models\Reservation::where('status', 'borrowed')->count() }}</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/30 p-3 rounded-lg text-blue-600 dark:text-blue-400"><i class="fas fa-hand-holding text-2xl"></i></div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card p-6 border-l-4 border-purple-500 hover:shadow-lg transition">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Maintenance</p>
                            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-white">{{ \App\Models\Asset::where('status', 'maintenance')->count() }}</p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/30 p-3 rounded-lg text-purple-600 dark:text-purple-400"><i class="fas fa-tools text-2xl"></i></div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card p-6 border-l-4 border-orange-500 hover:shadow-lg transition">
                    <div class="flex justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pengguna</p>
                            <p class="text-3xl font-bold mt-2 text-gray-800 dark:text-white">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="bg-orange-50 dark:bg-orange-900/30 p-3 rounded-lg text-orange-600 dark:text-orange-400"><i class="fas fa-users text-2xl"></i></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-card p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Tren Peminjaman</h3>
                    <div class="relative h-72"><canvas id="lineChart"></canvas></div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-card p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Status Aset</h3>
                    <div class="relative h-60 flex justify-center"><canvas id="doughnutChart"></canvas></div>
                </div>
            </div>

            @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // CONFIG FONT & WARNA
                Chart.defaults.font.family = "'Inter', sans-serif";
                Chart.defaults.color = '#94a3b8'; // Slate 400
                Chart.defaults.scale.grid.color = '#f1f5f9';

                // 1. LINE CHART
                const ctxLine = document.getElementById('lineChart').getContext('2d');
                let gradientLine = ctxLine.createLinearGradient(0, 0, 0, 400);
                gradientLine.addColorStop(0, 'rgba(20, 184, 166, 0.5)');
                gradientLine.addColorStop(1, 'rgba(20, 184, 166, 0.0)');

                new Chart(ctxLine, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [{
                            label: 'Total',
                            data: [12, 19, 8, 15, 10, 22],
                            borderColor: '#0d9488',
                            backgroundColor: gradientLine,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { border: { display: false } } } }
                });

                // 2. DOUGHNUT CHART
                const ctxDonut = document.getElementById('doughnutChart').getContext('2d');
                new Chart(ctxDonut, {
                    type: 'doughnut',
                    data: {
                        labels: ['Tersedia', 'Maintenance', 'Dipinjam'],
                        datasets: [{
                            data: [
                                {{ \App\Models\Asset::where('status', 'available')->count() }}, 
                                {{ \App\Models\Asset::where('status', 'maintenance')->count() }}, 
                                {{ \App\Models\Asset::where('status', 'borrowed')->count() }}
                            ],
                            backgroundColor: ['#10b981', '#f59e0b', '#6366f1'],
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } } }
                });
            </script>
            @endpush

        @endhasrole

    </div>
    
</x-app-layout>

