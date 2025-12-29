<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - ITENAS Resource Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { itenas: { orange: '#E65100', navy: '#1E1B4B', softNavy: '#312E81' } },
                    fontFamily: { sans: ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#E65100 0.5px, transparent 0.5px), radial-gradient(#E65100 0.5px, #f8fafc 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.6;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(229, 231, 235, 0.5);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 relative overflow-x-hidden">

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative bg-white overflow-hidden">
        <div class="absolute inset-0 bg-pattern z-0"></div>
        <a href="{{ url('/') }}" class="absolute top-8 left-8 flex items-center text-sm font-bold text-gray-500 hover:text-itenas-navy z-20 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>

        <div class="max-w-lg w-full space-y-8 glass-card p-10 rounded-3xl relative z-10">
            <div class="text-center">
                <img src="https://uat-web.itenas.ac.id/assets/images/logo-7.png" alt="Logo" class="mx-auto h-12 w-auto">
                <h2 class="mt-6 text-3xl font-black text-itenas-navy">Buat Akun</h2>
                <p class="mt-2 text-sm text-gray-500">Registrasi untuk mahasiswa Itenas.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-3 rounded-xl text-xs font-bold">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="mt-8 space-y-4" method="POST" action="{{ route('register') }}">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="name" value="{{ old('name') }}" required class="px-4 py-3 border border-gray-300 rounded-xl sm:text-sm" placeholder="Nama Lengkap">
                    <input type="text" name="nim" value="{{ old('nim') }}" required class="px-4 py-3 border border-gray-300 rounded-xl sm:text-sm" placeholder="NIM">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Program Studi</label>
                    <div class="relative">
                        <select name="prodi_id" required class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-itenas-orange focus:border-itenas-orange sm:text-sm appearance-none bg-white truncate">
                            <option value="">-- Pilih Program Studi --</option>
                            
                            <optgroup label="Fakultas Teknologi Industri">
                                <option value="1">Informatika</option>
                                <option value="2">Teknik Elektro</option>
                                <option value="3">Teknik Mesin</option>
                                <option value="4">Teknik Industri</option>
                                <option value="5">Teknik Kimia</option>
                                <option value="6">Sistem Informasi</option>
                            </optgroup>

                            <optgroup label="Fakultas Teknik Sipil dan Perencanaan">
                                <option value="7">Teknik Sipil</option>
                                <option value="8">Teknik Geodesi</option>
                                <option value="9">Perencanaan Wilayah dan Kota</option>
                                <option value="10">Teknik Lingkungan</option>
                            </optgroup>

                            <optgroup label="Fakultas Arsitektur dan Desain">
                                <option value="11">Desain Komunikasi Visual (DKV)</option>
                                <option value="12">Desain Produk</option>
                                <option value="13">Arsitektur</option>
                                <option value="14">Desain Interior</option>
                            </optgroup>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl sm:text-sm" placeholder="Email Institusi (@mhs.itenas.ac.id)">
                
                <div class="grid grid-cols-2 gap-4">
                    <input type="password" name="password" required class="px-4 py-3 border border-gray-300 rounded-xl sm:text-sm" placeholder="Password">
                    <input type="password" name="password_confirmation" required class="px-4 py-3 border border-gray-300 rounded-xl sm:text-sm" placeholder="Konfirmasi">
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-itenas-orange hover:bg-orange-700 text-white font-bold transition shadow-lg shadow-orange-200">Daftar Akun</button>

                <div class="relative flex py-2 items-center">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-400 text-xs uppercase font-bold">Atau daftar dengan</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>
                
                <div class="flex gap-4">
                    <button type="button" class="flex-1 py-2.5 border border-gray-300 rounded-xl flex items-center justify-center gap-2 hover:bg-gray-50 transition">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="h-5 w-5" alt="Google">
                        <span class="text-sm font-bold text-gray-600">Google</span>
                    </button>
                    <button type="button" class="flex-1 py-2.5 border border-gray-300 rounded-xl flex items-center justify-center gap-2 hover:bg-gray-50 transition text-gray-800">
                        <i class="fab fa-github text-xl"></i>
                        <span class="text-sm font-bold">GitHub</span>
                    </button>
                </div>

                <p class="mt-2 text-center text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-bold text-itenas-navy hover:text-navy-900">Masuk di sini</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>