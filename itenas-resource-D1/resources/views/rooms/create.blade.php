<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Booking: {{ $lab->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('error'))
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4 font-bold">⚠️ {{ session('error') }}</div>
                @endif

                <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="lab_id" value="{{ $lab->id }}">
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2 font-bold">Mulai</label>
                            <input type="datetime-local" name="start_time" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 dark:text-gray-300 mb-2 font-bold">Selesai</label>
                            <input type="datetime-local" name="end_time" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-bold">Keperluan Acara</label>
                        <textarea name="purpose" rows="3" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white" placeholder="Contoh: Rapat Himpunan, Seminar Tech, Workshop..." required></textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-bold">Upload Proposal (PDF)</label>
                        <input type="file" name="proposal" accept="application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                        <p class="text-xs text-gray-500 mt-1">*Maksimal 2MB.</p>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('rooms.index') }}" class="text-gray-500 hover:text-gray-700 px-4 py-2 mr-2">Batal</a>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-bold shadow-lg">
                            🚀 Ajukan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>