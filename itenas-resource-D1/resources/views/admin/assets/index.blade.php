<x-app-layout>
    @section('header', 'Kelola Aset (Administrator)')

    {{-- Container utama dengan Alpine.js untuk kontrol Modal dan Nama File --}}
    <div x-data="{ showImportModal: false, fileName: '' }" class="space-y-6">

        {{-- === ALERT NOTIFIKASI === --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                <span class="block sm:inline font-bold"><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                <span class="block sm:inline font-bold"><i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}</span>
            </div>
        @endif

        {{-- === DASHBOARD STATS & ACTIONS === --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span class="flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-full font-medium">
                    <i class="fas fa-cube text-navy-600"></i> Total: <strong>{{ $assets->total() }}</strong>
                </span>
                <span class="flex items-center gap-2 px-3 py-1 bg-red-50 text-red-600 rounded-full font-medium border border-red-100">
                    <i class="fas fa-tools"></i> Rusak: <strong>{{ \App\Models\Asset::where('status', 'maintenance')->count() }}</strong>
                </span>
            </div>

            <div class="flex flex-wrap gap-2">
                <div class="flex gap-2 mr-2 border-r pr-4 border-gray-200">
                    {{-- TOMBOL IMPORT (BIRU) --}}
                    <button @click="showImportModal = true; fileName = ''" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition flex items-center gap-2 shadow-md transform hover:-translate-y-0.5">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                </div>

                <a href="{{ route('admin.assets.create') }}" class="px-5 py-2.5 bg-navy-700 hover:bg-navy-800 text-white rounded-xl text-sm font-bold transition flex items-center gap-2 shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-plus"></i> Tambah Aset Baru
                </a>
            </div>
        </div>

        {{-- === SEARCH & FILTER BAR === --}}
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.assets.index') }}" class="flex flex-col md:flex-row gap-4">
                
                <div class="w-full md:w-1/4">
                    <select name="category_id" onchange="this.form.submit()" class="w-full rounded-lg border-gray-300 text-sm focus:ring-navy-500 focus:border-navy-500 cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-3/4 flex gap-2">
                    <div class="relative w-full group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 transition-colors"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="w-full pl-10 rounded-lg border-gray-300 text-sm focus:ring-navy-500 focus:border-navy-500 transition-all" 
                               placeholder="Cari nama aset atau kode barang...">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition font-bold shadow-sm">
                        Cari
                    </button>
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('admin.assets.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center shadow-sm">
                            <i class="fas fa-undo"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- === TABEL ASET === --}}
        <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs tracking-wider border-b">
                        <tr>
                            <th class="px-6 py-4">Aset</th>
                            <th class="px-6 py-4">Kategori & Lokasi</th>
                            <th class="px-6 py-4">Kode & QR</th>
                            <th class="px-6 py-4 text-center">Stok</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($assets as $asset)
                        <tr class="hover:bg-gray-50 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200 group-hover:scale-105 transition-transform">
                                        @if($asset->image)
                                            <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <i class="fas fa-cube text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-navy-800 text-base">{{ $asset->name }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Ditambahkan: {{ $asset->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-navy-700 font-bold">{{ $asset->category->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-map-marker-alt text-teal-500"></i>
                                    {{ $asset->lab->name ?? 'Gudang Utama' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded border border-gray-200 font-bold text-navy-600">{{ $asset->code }}</span>
                                    <button onclick="showQrModal('{{ $asset->code }}', '{{ $asset->name }}')" class="p-1.5 text-gray-400 hover:text-navy-700 rounded-lg transition">
                                        <i class="fas fa-qrcode"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-navy-50 text-navy-700 rounded-lg font-bold">{{ $asset->stock }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($asset->status == 'available')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold border border-green-200">Available</span>
                                @elseif($asset->status == 'maintenance')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-bold border border-yellow-200">Maintenance</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold border border-red-200">Rusak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.assets.edit', $asset->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 border border-yellow-100 transition"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition border border-red-100"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-bold">Data tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $assets->withQueryString()->links() }}
            </div>
        </div>

        {{-- === MODAL IMPORT EXCEL === --}}
        <div x-show="showImportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6" @click.away="showImportModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-navy-800">Import Data Aset</h3>
                    <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times"></i></button>
                </div>

                {{-- Ganti rute ini --}}
<form action="{{ route('admin.assets.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-6">
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-4">
                            <p class="text-xs font-bold text-blue-700 uppercase mb-2">Format Header Wajib:</p>
                            <code class="text-[10px] text-blue-600 bg-white p-1.5 rounded border border-blue-200 block overflow-x-auto whitespace-nowrap">
                                nama_aset, kode_aset, id_kategori, id_lab, stok, prodi
                            </code>
                        </div>

                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel (.xlsx / .csv)</label>
                        <div class="relative border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-blue-400 transition cursor-pointer bg-gray-50">
                            <input type="file" name="file" required 
                                   @change="fileName = $event.target.files[0].name"
                                   class="absolute inset-0 opacity-0 cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-blue-500 mb-3"></i>
                            <p class="text-sm font-bold" 
                               :class="fileName ? 'text-blue-600' : 'text-gray-500'" 
                               x-text="fileName ? fileName : 'Klik atau tarik file ke sini'"></p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="showImportModal = false" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg font-bold">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-bold">Unggah Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL QR CODE --}}
    <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity" onclick="document.getElementById('qrModal').classList.add('hidden')"></div>
            <div class="bg-white rounded-2xl shadow-2xl transform transition-all max-w-sm w-full p-6 relative z-10 text-center">
                <h3 class="text-xl font-bold text-navy-800 mb-2" id="qrAssetName">Nama Aset</h3>
                <p class="text-sm text-gray-500 mb-6 font-mono font-bold bg-gray-100 inline-block px-3 py-1 rounded" id="qrAssetCode">CODE-123</p>
                <div class="bg-white p-4 border-2 border-dashed border-gray-200 rounded-2xl inline-block mb-6 shadow-inner">
                    <div id="qrContainer"></div> 
                </div>
                <div class="flex justify-center gap-3">
                    <button onclick="window.print()" class="flex-1 px-4 py-2 bg-navy-700 text-white rounded-xl hover:bg-navy-800 flex items-center justify-center gap-2 font-bold">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <button onclick="document.getElementById('qrModal').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-bold transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function showQrModal(code, name) {
            document.getElementById('qrAssetName').innerText = name;
            document.getElementById('qrAssetCode').innerText = code;
            const container = document.getElementById('qrContainer');
            container.innerHTML = ""; 
            new QRCode(container, {
                text: code,
                width: 180,
                height: 180,
                colorDark : "#0a192f",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
            document.getElementById('qrModal').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>