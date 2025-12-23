<x-app-layout>
    @section('header', 'Kelola Aset (Administrator)')

    <div class="space-y-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <span class="flex items-center gap-2 px-3 py-1 bg-gray-100 rounded-full">
                    <i class="fas fa-cube"></i> Total: <strong>{{ $assets->total() }}</strong>
                </span>
                <span class="flex items-center gap-2 px-3 py-1 bg-red-50 text-red-600 rounded-full">
                    <i class="fas fa-tools"></i> Rusak: <strong>{{ \App\Models\Asset::where('status', 'maintenance')->count() }}</strong>
                </span>
            </div>

            <div class="flex flex-wrap gap-2">
                <div class="flex gap-2 mr-2 border-r pr-4 border-gray-200">
                    <a href="{{ route('assets.export') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition flex items-center gap-2">
                        <i class="fas fa-file-upload"></i> Import
                    </button>
                </div>

                <a href="{{ route('admin.assets.create') }}" class="px-4 py-2 bg-navy-700 hover:bg-navy-800 text-white rounded-lg text-sm font-medium transition flex items-center gap-2 shadow-lg">
                    <i class="fas fa-plus"></i> Tambah Aset Baru
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-card overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase font-bold text-xs">
                        <tr>
                            <th class="px-6 py-4">Aset</th>
                            <th class="px-6 py-4">Kategori & Lokasi</th>
                            <th class="px-6 py-4">Kode & QR</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($assets as $asset)
                        <tr class="hover:bg-gray-50 transition">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                        @if($asset->image)
                                            <img src="{{ asset('storage/' . $asset->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-navy-800">{{ $asset->name }}</div>
                                        <div class="text-xs text-gray-500">Added: {{ $asset->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-gray-700 font-medium">{{ $asset->category->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-teal-500"></i>
                                    {{ $asset->lab->name ?? 'Gudang Utama' }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded border">{{ $asset->code }}</span>
                                    <button onclick="showQrModal('{{ $asset->code }}', '{{ $asset->name }}')" class="text-gray-400 hover:text-navy-700 transition" title="Lihat QR Code">
                                        <i class="fas fa-qrcode"></i>
                                    </button>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-700">{{ $asset->stock }}</span>
                                <span class="text-xs text-gray-400">unit</span>
                            </td>

                            <td class="px-6 py-4">
                                @if($asset->status == 'available')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">Available</span>
                                @elseif($asset->status == 'maintenance')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200">Maintenance</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200">Rusak</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.assets.edit', $asset->id) }}" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.assets.destroy', $asset->id) }}" method="POST" onsubmit="return confirm('Yakin hapus aset ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $assets->links() }}
            </div>
        </div>
    </div>

    <div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('assets.import') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Import Data Aset (Excel)</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File Excel (.xlsx)</label>
                        <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 border border-gray-300 rounded-lg cursor-pointer">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Upload & Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity" onclick="document.getElementById('qrModal').classList.add('hidden')"></div>
            <div class="bg-white rounded-2xl shadow-2xl transform transition-all max-w-sm w-full p-6 relative z-10 text-center">
                <h3 class="text-xl font-bold text-navy-800 mb-2" id="qrAssetName">Nama Aset</h3>
                <p class="text-sm text-gray-500 mb-6" id="qrAssetCode">CODE-123</p>
                
                <div class="bg-white p-4 border-2 border-dashed border-gray-300 rounded-xl inline-block mb-6">
                    <div id="qrContainer"></div> 
                </div>

                <div class="flex justify-center gap-2">
                    <button onclick="window.print()" class="px-4 py-2 bg-navy-700 text-white rounded-lg hover:bg-navy-800 flex items-center gap-2">
                        <i class="fas fa-print"></i> Cetak Label
                    </button>
                    <button onclick="document.getElementById('qrModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Tutup
                    </button>
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
            
            // Generate QR
            const container = document.getElementById('qrContainer');
            container.innerHTML = ""; // Clear previous
            new QRCode(container, {
                text: code,
                width: 150,
                height: 150,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });

            document.getElementById('qrModal').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>