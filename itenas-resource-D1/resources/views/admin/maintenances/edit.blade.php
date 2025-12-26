<x-app-layout>
    @section('header', 'Edit Laporan Maintenance')

    <div class="max-w-3xl mx-auto space-y-6">
        
        {{-- Tombol Kembali --}}
        <a href="{{ route('admin.maintenances.index') }}" class="inline-flex items-center text-gray-500 hover:text-teal-600 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 p-8">
            
            <h2 class="text-xl font-bold text-navy-800 dark:text-white mb-6">
                Form Edit Maintenance
            </h2>

            <form action="{{ route('admin.maintenances.update', $maintenance->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Wajib untuk proses Update --}}

                <div class="space-y-5">
                    
                    {{-- INFO ASET (READ ONLY - Tidak bisa diubah agar data konsisten) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Aset yang Dilaporkan</label>
                        <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                            <div class="bg-teal-100 text-teal-600 w-10 h-10 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-white">{{ $maintenance->asset->name }}</p>
                                <p class="text-xs text-gray-500">{{ $maintenance->asset->code }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">* Aset tidak dapat diubah dari menu edit ini.</p>
                    </div>

                    {{-- STATUS --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Pengerjaan</label>
                        <select name="status" id="status" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:border-teal-500 focus:ring-teal-500 transition">
                            <option value="pending" {{ $maintenance->status == 'pending' ? 'selected' : '' }}>🕒 Pending (Menunggu)</option>
                            <option value="in_progress" {{ $maintenance->status == 'in_progress' ? 'selected' : '' }}>🔧 In Progress (Sedang Dikerjakan)</option>
                            <option value="completed" {{ $maintenance->status == 'completed' ? 'selected' : '' }}>✅ Completed (Selesai)</option>
                            <option value="cancelled" {{ $maintenance->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled (Dibatalkan)</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- DESKRIPSI KERUSAKAN --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi Kerusakan / Tindakan</label>
                        <textarea name="description" id="description" rows="5" 
                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:border-teal-500 focus:ring-teal-500 transition"
                            placeholder="Jelaskan detail kerusakan atau tindakan perbaikan yang dilakukan...">{{ old('description', $maintenance->description) }}</textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                </div>

                {{-- TOMBOL AKSI --}}
                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('admin.maintenances.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 transition font-bold">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-teal-600 text-white hover:bg-teal-700 transition font-bold shadow-lg shadow-teal-500/30">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>