<x-app-layout>
    {{-- KIRIM JUDUL KE LAYOUT --}}
    @section('header')
        @hasrole('Superadmin|Laboran') DASHBOARD EKSEKUTIF @else BERANDA MAHASISWA @endhasrole
    @endsection

    <div class="space-y-6">
        
        {{-- ==================== TAMPILAN ADMIN ==================== --}}
        @hasrole('Superadmin|Laboran')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <a href="{{ route('admin.assets.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border-b-4 border-orange-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Total Aset</p>
                    <div class="flex justify-between items-end mt-1">
                        <p class="text-3xl font-black text-gray-800 dark:text-white">{{ $totalAssets }}</p>
                        <p class="text-[10px] text-orange-600 font-bold opacity-0 group-hover:opacity-100 transition-opacity">Kelola Aset →</p>
                    </div>
                </a>

                <a href="{{ route('admin.reservations.index', ['status' => 'borrowed']) }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border-b-4 border-blue-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Sedang Dipinjam</p>
                    <div class="flex justify-between items-end mt-1">
                        <p class="text-3xl font-black text-gray-800 dark:text-white">{{ $activeLoans }}</p>
                        <p class="text-[10px] text-blue-600 font-bold opacity-0 group-hover:opacity-100 transition-opacity">Cek Pinjaman →</p>
                    </div>
                </a>

                <a href="{{ route('admin.maintenances.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border-b-4 border-red-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Maintenance</p>
                    <div class="flex justify-between items-end mt-1">
                        <p class="text-3xl font-black text-red-500">{{ $totalMaintenance }}</p>
                        <p class="text-[10px] text-red-500 font-bold opacity-0 group-hover:opacity-100 transition-opacity">Lihat Log →</p>
                    </div>
                </a>

                <a href="{{ route('admin.reservations.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border-b-4 border-green-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                    <p class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">Total Transaksi</p>
                    <div class="flex justify-between items-end mt-1">
                        <p class="text-3xl font-black text-gray-800 dark:text-white">{{ $totalReservations }}</p>
                        <p class="text-[10px] text-green-600 font-bold opacity-0 group-hover:opacity-100 transition-opacity">Histori →</p>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6"><h3 class="font-bold mb-4 dark:text-white">📈 Tren Peminjaman</h3><div class="h-64"><canvas id="chartTren"></canvas></div></div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6"><h3 class="font-bold mb-4 dark:text-white">📊 Status Transaksi</h3><div class="h-64 flex justify-center"><canvas id="chartStatus"></canvas></div></div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6"><h3 class="font-bold mb-4 dark:text-white">🛠️ Kondisi Aset</h3><div class="h-64 flex justify-center"><canvas id="chartCondition"></canvas></div></div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6"><h3 class="font-bold mb-4 dark:text-white">📂 Kategori Aset</h3><div class="h-64 flex justify-center"><canvas id="chartCategory"></canvas></div></div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6"><h3 class="font-bold mb-4 dark:text-white">⏱️ Kepatuhan</h3><div class="h-64 flex justify-center"><canvas id="chartCompliance"></canvas></div></div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6"><h3 class="font-bold mb-4 dark:text-white">🎓 Top 5 Prodi</h3><div class="h-64"><canvas id="chartProdi"></canvas></div></div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6"><h3 class="font-bold mb-4 dark:text-white">🏆 Aset Terlaris</h3><div class="h-64"><canvas id="chartTopAsset"></canvas></div></div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    if(!document.getElementById('chartTren')) return;
                    const cfg = { responsive: true, maintainAspectRatio: false };
                    new Chart(document.getElementById('chartTren'), { type: 'line', data: { labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'], datasets: [{ label: 'Transaksi', data: {!! json_encode($monthlyData) !!}, borderColor: '#F97316', tension: 0.4, fill: true, backgroundColor: 'rgba(249, 115, 22, 0.1)' }] }, options: cfg });
                    new Chart(document.getElementById('chartStatus'), { type: 'doughnut', data: { labels: {!! json_encode($statusLabels) !!}, datasets: [{ data: {!! json_encode($statusData) !!}, backgroundColor: ['#3b82f6','#eab308','#22c55e','#ef4444','#a855f7'] }] }, options: cfg });
                    new Chart(document.getElementById('chartCondition'), { type: 'pie', data: { labels: {!! json_encode($conditionLabels) !!}, datasets: [{ data: {!! json_encode($conditionData) !!}, backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#64748b'] }] }, options: cfg });
                    new Chart(document.getElementById('chartCategory'), { type: 'pie', data: { labels: {!! json_encode($categoryLabels) !!}, datasets: [{ data: {!! json_encode($categoryData) !!}, backgroundColor: ['#6366f1', '#ec4899', '#8b5cf6', '#14b8a6', '#f59e0b'] }] }, options: cfg });
                    new Chart(document.getElementById('chartCompliance'), { type: 'doughnut', data: { labels: {!! json_encode($complianceLabels) !!}, datasets: [{ data: {!! json_encode($complianceData) !!}, backgroundColor: ['#22c55e', '#ef4444'] }] }, options: cfg });
                    new Chart(document.getElementById('chartProdi'), { type: 'bar', data: { labels: {!! json_encode($prodiLabels) !!}, datasets: [{ label: 'Peminjam', data: {!! json_encode($prodiData) !!}, backgroundColor: '#6366f1' }] }, options: cfg });
                    new Chart(document.getElementById('chartTopAsset'), { type: 'bar', data: { labels: {!! json_encode($topAssetLabels) !!}, datasets: [{ label: 'Dipinjam', data: {!! json_encode($topAssetData) !!}, backgroundColor: '#F97316' }] }, options: { indexAxis: 'y', ...cfg } });
                });
            </script>

        {{-- ==================== TAMPILAN MAHASISWA ==================== --}}
        @else
            <div class="bg-gray-800 rounded-2xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h2>
                    <p class="text-gray-300 mb-6">Selamat datang. Kelola peminjaman inventaris kampus kamu di sini.</p>
                    <div class="flex gap-3">
                        <a href="{{ route('assets.index') }}" class="px-5 py-2 bg-orange-500 hover:bg-orange-600 rounded-lg font-bold shadow-md transition">
                            <i class="fas fa-search mr-2"></i> Pinjam Alat
                        </a>
                        <a href="{{ route('reservations.assets') }}" class="px-5 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg border border-gray-500 transition">
                            <i class="fas fa-list mr-2"></i> Cek Status
                        </a>
                    </div>
                </div>
                <i class="fas fa-university absolute -right-10 -bottom-10 text-[200px] text-white/5 rotate-12"></i>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <a href="{{ route('reservations.assets') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase">Sedang Dipinjam</p>
                            <h3 class="text-3xl font-black text-gray-800 dark:text-white mt-1">
                                {{ \App\Models\Reservation::where('user_id', Auth::id())->where('status', 'borrowed')->count() }}
                            </h3>
                        </div>
                        <i class="fas fa-hand-holding text-blue-200 text-3xl group-hover:text-blue-500 transition-colors"></i>
                    </div>
                </a>

                <a href="{{ route('reservations.assets') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase">Menunggu Approval</p>
                            <h3 class="text-3xl font-black text-gray-800 dark:text-white mt-1">
                                {{ \App\Models\Reservation::where('user_id', Auth::id())->where('status', 'pending')->count() }}
                            </h3>
                        </div>
                        <i class="fas fa-clock text-yellow-200 text-3xl group-hover:text-yellow-500 transition-colors"></i>
                    </div>
                </a>

                <a href="{{ route('reservations.assets') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border-l-4 border-red-500 hover:shadow-md transition-all transform hover:-translate-y-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-xs font-bold uppercase">Tagihan Denda</p>
                            <h3 class="text-2xl font-black text-red-600 mt-1">
                                Rp {{ number_format(\App\Models\Reservation::where('user_id', Auth::id())->where('penalty_status', 'unpaid')->sum('penalty')) }}
                            </h3>
                        </div>
                        <i class="fas fa-exclamation-triangle text-red-200 text-3xl group-hover:text-red-500 transition-colors"></i>
                    </div>
                </a>
            </div>
        @endhasrole
    </div>
</x-app-layout>