<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Katalog Aset & Fasilitas') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <form action="{{ route('assets.index') }}" method="GET">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                            placeholder="Cari nama alat, gedung, atau prodi (Contoh: 'Oscilloscope' atau 'Informatika')...">
                        <button type="submit" class="text-white absolute right-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2">Cari</button>
                    </div>
                </form>
            </div>

            @if($assets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($assets as $asset)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
                            <div class="h-40 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                @if($asset->image_path && $asset->image_path != 'assets/dummy.jpg')
                                    <img src="{{ asset('storage/' . $asset->image_path) }}" alt="{{ $asset->name }}" class="h-full w-full object-cover">
                                @else
                                    <span class="text-4xl">🛠️</span>
                                @endif
                            </div>
                            
                            <div class="p-5">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-indigo-900 dark:text-indigo-300">
                                    {{ $asset->lab->prodi->code ?? 'UMUM' }}
                                </span>

                                <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white truncate">
                                    {{ $asset->name }}
                                </h5>
                                
                                <p class="mb-3 font-normal text-sm text-gray-700 dark:text-gray-400">
                                    <span class="block">📍 {{ $asset->lab->building_name }} - {{ $asset->lab->name }}</span>
                                    <span class="block mt-1">📦 Stok: {{ $asset->stock }} Unit</span>
                                </p>

                                <div class="flex justify-between items-center mt-4">
                                    <span class="text-sm font-semibold {{ $asset->rental_price > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                        {{ $asset->rental_price > 0 ? 'Rp '.number_format($asset->rental_price) : 'Gratis' }}
                                    </span>
                                    
                                    <a href="{{ route('reservations.create', $asset->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                        Pinjam
                                        <svg class="w-3.5 h-3.5 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $assets->links() }}
                </div>
            @else
                <div class="text-center py-10">
                    <p class="text-gray-500 text-lg">Aset yang kamu cari tidak ditemukan 😔</p>
                    <a href="{{ route('assets.index') }}" class="text-blue-600 hover:underline">Reset Pencarian</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>