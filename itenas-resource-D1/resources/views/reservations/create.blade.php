<x-app-layout>
    @section('header', 'Form Peminjaman')

    <div class="max-w-6xl mx-auto">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-navy-800 dark:text-white">Pengajuan Peminjaman</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Lengkapi formulir di bawah ini untuk mengajukan peminjaman aset.</p>
        </div>

        <form action="{{ route('reservations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-navy-800 mb-4 pb-2 border-b border-gray-100">
                            <i class="fas fa-calendar-alt mr-2 text-teal-600"></i> Detail Waktu
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 @error('start_time') border-red-500 @enderror">
                                @error('start_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai</label>
                                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 @error('end_time') border-red-500 @enderror">
                                @error('end_time') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Peminjaman</label>
                            <textarea name="purpose" rows="3" placeholder="Contoh: Untuk keperluan Praktikum Jaringan Komputer Modul 3..." 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500 @error('purpose') border-red-500 @enderror">{{ old('purpose') }}</textarea>
                            @error('purpose') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-navy-800 mb-4 pb-2 border-b border-gray-100">
                            <i class="fas fa-file-upload mr-2 text-teal-600"></i> Dokumen Pendukung
                        </h3>
                        
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> surat pengantar</p>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX (Max. 2MB)</p>
                                </div>
                                <input id="dropzone-file" type="file" name="proposal_file" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0].name" />
                            </label>
                        </div>
                        <p id="file-name" class="text-sm text-teal-600 mt-2 font-medium text-center"></p>
                        @error('proposal_file') <span class="text-red-500 text-xs block text-center mt-1">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-card p-6 border border-gray-100 sticky top-24">
                        <h3 class="text-lg font-bold text-navy-800 mb-4 pb-2 border-b border-gray-100">
                            <i class="fas fa-shopping-cart mr-2 text-teal-600"></i> Barang Dipilih
                        </h3>

                        <div id="items-container" class="space-y-4 mb-6">
                            
                            <div class="item-row p-4 bg-gray-50 rounded-lg border border-gray-200 relative group">
                                <div class="mb-3">
                                    <label class="text-xs font-bold text-gray-500 uppercase">Nama Aset</label>
                                    <input type="hidden" name="items[0][asset_id]" value="{{ $asset->id }}">
                                    <p class="font-bold text-navy-800 text-sm mt-1">{{ $asset->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $asset->code }}</p>
                                </div>
                                
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase">Jumlah</label>
                                    <input type="number" name="items[0][quantity]" value="1" min="1" max="{{ $asset->stock }}" 
                                        class="w-full mt-1 px-3 py-1 border border-gray-300 rounded text-sm focus:ring-teal-500">
                                    <p class="text-[10px] text-gray-400 mt-1">Stok tersedia: {{ $asset->stock }}</p>
                                </div>
                            </div>

                        </div>

                        <button type="button" id="add-item-btn" class="w-full py-2 border-2 border-dashed border-gray-300 text-gray-500 rounded-lg hover:border-teal-500 hover:text-teal-600 transition text-sm font-bold mb-6 flex items-center justify-center">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Barang Lain
                        </button>

                        <div class="space-y-3">
                            <button type="submit" class="w-full py-3 bg-navy-700 hover:bg-navy-800 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                                Ajukan Sekarang
                            </button>
                            <a href="{{ route('assets.index') }}" class="block w-full py-3 text-center border border-gray-300 text-gray-600 font-bold rounded-lg hover:bg-gray-50 transition">
                                Batal
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </div>

    <template id="item-template">
        <div class="item-row p-4 bg-gray-50 rounded-lg border border-gray-200 relative mt-3">
            <button type="button" class="remove-item absolute top-2 right-2 text-gray-400 hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
            <div class="mb-3">
                <label class="text-xs font-bold text-gray-500 uppercase">Pilih Aset</label>
                <select name="items[INDEX][asset_id]" class="w-full mt-1 px-3 py-2 border border-gray-300 rounded text-sm focus:ring-teal-500 bg-white">
                    @foreach($allAssets as $a)
                        <option value="{{ $a->id }}">{{ $a->name }} (Stok: {{ $a->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase">Jumlah</label>
                <input type="number" name="items[INDEX][quantity]" value="1" min="1" class="w-full mt-1 px-3 py-1 border border-gray-300 rounded text-sm focus:ring-teal-500">
            </div>
        </div>
    </template>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemIndex = 1; // Mulai dari 1 karena 0 sudah dipakai aset utama

            // Fungsi Tambah Barang
            document.getElementById('add-item-btn').addEventListener('click', function() {
                const container = document.getElementById('items-container');
                const template = document.getElementById('item-template').innerHTML;
                
                // Ganti INDEX dengan angka urut
                const newItem = template.replace(/INDEX/g, itemIndex);
                
                // Tambahkan ke container
                container.insertAdjacentHTML('beforeend', newItem);
                itemIndex++;
            });

            // Fungsi Hapus Barang (Event Delegation)
            document.getElementById('items-container').addEventListener('click', function(e) {
                if (e.target.closest('.remove-item')) {
                    e.target.closest('.item-row').remove();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>