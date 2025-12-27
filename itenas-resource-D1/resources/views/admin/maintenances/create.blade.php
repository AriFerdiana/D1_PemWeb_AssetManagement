<x-app-layout>
    {{-- KIRIM JUDUL KE LAYOUT --}}
    @section('header', 'Catat Perawatan Aset')

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                
                {{-- HEADER FORM --}}
                <div class="bg-navy-700 p-6 text-white">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/10 rounded-lg">
                            <i class="fas fa-tools text-xl text-orange-400"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Formulir Maintenance</h3>
                            <p class="text-xs text-orange-200">Daftarkan aset yang memerlukan perbaikan atau perawatan rutin.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.maintenances.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- 1. PILIH ASET --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Aset</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                    <i class="fas fa-box text-sm"></i>
                                </span>
                                <select name="asset_id" required class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-lg text-sm focus:ring-navy-500 focus:border-navy-500">
                                    <option value="">-- Cari Aset Milik Prodi --</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}">
                                            {{ $asset->name }} ({{ $asset->code ?? $asset->serial_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1 italic">
                                *Hanya menampilkan aset yang berstatus 'Available' di prodi Anda.
                            </p>
                        </div>

                        {{-- 2. TANGGAL --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Kegiatan</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" required 
                                   class="w-full border-gray-300 rounded-lg text-sm focus:ring-navy-500">
                        </div>

                        {{-- 3. JENIS KEGIATAN --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kegiatan</label>
                            <select name="type" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-navy-500">
                                <option value="Perbaikan (Rusak)">Perbaikan (Rusak)</option>
                                <option value="Perawatan Rutin">Perawatan Rutin</option>
                                <option value="Upgrade">Upgrade Komponen</option>
                            </select>
                        </div>

                        {{-- 4. BIAYA --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Estimasi Biaya (Rp)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 text-sm">Rp</span>
                                <input type="number" name="cost" min="0" value="0" required
                                       class="w-full pl-10 border-gray-300 rounded-lg text-sm focus:ring-navy-500">
                            </div>
                        </div>

                        {{-- 5. SUMBER DANA (PENYEMPURNAAN) --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Sumber Dana</label>
                            <select name="funding_source" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-navy-500">
                                <option value="prodi_budget">Anggaran Operasional Prodi</option>
                                <option value="university_budget">Anggaran Sarpras Universitas</option>
                                <option value="student_compensation">Ganti Rugi Mahasiswa (Lalai)</option>
                                <option value="penalty_fund">Alokasi Dana Denda (Penalty Fund)</option>
                            </select>
                        </div>

                        {{-- 6. DESKRIPSI --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Kerusakan / Tindakan</label>
                            <textarea name="description" rows="3" placeholder="Jelaskan detail kerusakan atau apa yang perlu diperbaiki..."
                                      class="w-full border-gray-300 rounded-lg text-sm focus:ring-navy-500"></textarea>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="mt-8 flex items-center gap-3 border-t pt-6">
                        <button type="submit" class="flex-1 bg-navy-700 text-white py-3 rounded-xl font-bold text-sm hover:bg-navy-800 transition shadow-lg shadow-navy-200">
                            <i class="fas fa-save mr-2"></i> Simpan Log Maintenance
                        </button>
                        <a href="{{ route('admin.maintenances.index') }}" class="px-6 py-3 border border-gray-300 text-gray-600 rounded-xl font-bold text-sm hover:bg-gray-50 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>