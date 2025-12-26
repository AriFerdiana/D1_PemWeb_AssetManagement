<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITENAS Resource Center</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        itenas: {
                            primary: '#F97316', 
                            dark: '#C2410C',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-72 fixed inset-y-0 left-0 bg-gradient-to-b from-orange-500 to-orange-700 text-white shadow-2xl overflow-y-auto z-50 flex flex-col">
            
            <div class="h-24 flex items-center justify-start px-6 border-b border-white/20 shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="bg-white p-1.5 rounded-lg shadow-sm">
                        <img src="https://uat-web.itenas.ac.id/assets/images/logo-7.png" alt="Logo Itenas" class="h-8 w-auto">
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-tight">ITENAS</h1>
                        <p class="text-xs text-orange-100 opacity-90">Resource Center</p>
                    </div>
                </div>
            </div>

            <nav class="mt-8 px-4 space-y-3 flex-grow">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 font-semibold group {{ request()->routeIs('admin.dashboard') ? 'bg-white text-orange-600 shadow-sm' : 'text-white hover:bg-white/10' }}">
                    <i class="fas fa-th-large text-xl mr-4"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.assets.index') }}" 
                   class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 font-medium group {{ request()->routeIs('admin.assets*') ? 'bg-white text-orange-600 shadow-sm' : 'text-white hover:bg-white/10' }}">
                    <i class="fas fa-box text-xl mr-4"></i>
                    Data Aset
                </a>
                
                <a href="{{ route('admin.maintenances.index') }}" 
                   class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-200 font-medium group {{ request()->routeIs('admin.maintenances*') ? 'bg-white text-orange-600 shadow-sm' : 'text-white hover:bg-white/10' }}">
                    <i class="fas fa-screwdriver-wrench text-xl mr-4"></i>
                    Log Perawatan
                </a>
            </nav>

             <div class="p-4 shrink-0 border-t border-white/10">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-white hover:bg-red-500/80 rounded-xl transition-all duration-200 font-medium">
                        <i class="fas fa-sign-out-alt mr-4 opacity-80"></i> Logout
                    </button>
                </form>
             </div>
        </aside>

        <div class="flex-1 ml-72 flex flex-col h-screen overflow-y-auto bg-gray-50">
            
            <header class="bg-white shadow-sm h-20 flex items-center justify-between px-8 sticky top-0 z-40">
                <div>
                     <h2 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-bold text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <img class="h-10 w-10 rounded-full border-2 border-orange-200" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'A' }}&background=F97316&color=fff">
                </div>
            </header>

            <main class="p-8 flex-grow">
                @yield('content')
            </main>

        </div>
    </div>
</body>
</html>