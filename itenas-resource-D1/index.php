<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Logistik Itenas</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* --- 1. GLOBAL STYLE --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        html { scroll-behavior: smooth; }

        body {
            background-color: #f8fafc;
            color: #333;
            padding-top: 80px;
        }

        :root {
            --itenas-orange: #F26522; 
            --itenas-dark: #c44d15;
            --itenas-black: #2d2d2d;
            --itenas-gray: #64748b;
        }

        /* --- 2. NAVBAR --- */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            height: 80px;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
        }

        nav {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* LOGO STYLE DIPERBARUI */
        .logo {
            display: flex;
            align-items: center;
            gap: 12px; /* Jarak antara logo dan teks */
            text-decoration: none; /* Hilangkan garis bawah jika diklik */
        }

        /* Mengatur ukuran gambar logo */
        .logo img {
            height: 45px; /* Tinggi logo disesuaikan dengan navbar */
            width: auto;  /* Lebar menyesuaikan otomatis */
            object-fit: contain;
        }

        .logo span {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--itenas-black);
            letter-spacing: -0.5px;
        }

        .nav-menu { display: flex; gap: 2.5rem; align-items: center; list-style: none; }

        .nav-link {
            font-weight: 600;
            color: var(--itenas-gray);
            font-size: 0.95rem;
            text-decoration: none;
            transition: color 0.3s;
        }

        .nav-link:hover { color: var(--itenas-orange); }

        .btn-login-nav {
            background-color: var(--itenas-orange);
            color: white;
            padding: 0.6rem 1.8rem;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-login-nav:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(242, 101, 34, 0.3); }

        /* --- 3. SECTIONS UMUM --- */
        section {
            padding: 5rem 1rem;
            scroll-margin-top: 60px; 
        }

        .container { max-width: 1200px; margin: 0 auto; }

        .section-header { text-align: center; margin-bottom: 3rem; }
        .section-header h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 1rem; color: var(--itenas-black); }
        .section-header p { color: var(--itenas-gray); max-width: 600px; margin: 0 auto; line-height: 1.6; }

        /* --- 4. SECTION: BERANDA (HERO) --- */
        #beranda {
            text-align: center;
            padding: 10rem 1rem; 
            /* Background Gambar Gedung + Overlay Gelap */
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.6)), url('itenas-bg.jpg');
            background-size: cover;      
            background-position: center; 
            border-bottom: 1px solid #eee;
        }
        
        #beranda h1 { 
            font-size: 3.5rem; 
            font-weight: 800; 
            color: #ffffff; 
            margin-bottom: 1.5rem; 
            line-height: 1.2; 
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        #beranda span { color: var(--itenas-orange); }
        
        #beranda p { 
            font-size: 1.25rem; 
            color: #e2e8f0; 
            max-width: 700px; 
            margin: 0 auto 2.5rem; 
            font-weight: 500;
        }
        
        .btn-cta {
            padding: 1rem 2.5rem; 
            font-size: 1.1rem; 
            background: var(--itenas-orange); 
            color: white;
            border-radius: 8px; 
            border: none; 
            font-weight: 600; 
            cursor: pointer; 
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(242, 101, 34, 0.4);
        }
        .btn-cta:hover { 
            background: white; 
            color: var(--itenas-orange); 
            transform: translateY(-3px);
        }

        /* --- 5. SECTION: TENTANG --- */
        #tentang { background: white; }
        .about-content {
            display: flex; flex-wrap: wrap; align-items: center; gap: 3rem; justify-content: center;
        }
        .about-text { flex: 1; min-width: 300px; }
        
        .about-image { 
            flex: 1; 
            min-width: 300px; 
            height: 350px; 
            border-radius: 16px; 
            overflow: hidden; 
            box-shadow: 0 15px 40px rgba(0,0,0,0.1); 
            position: relative;
        }

        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* --- 6. SECTION: LAYANAN --- */
        #layanan { background: #f8fafc; }
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
        .card {
            background: white; padding: 2.5rem; border-radius: 16px; border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: 0.3s; position: relative;
        }
        .card:hover { transform: translateY(-5px); border-color: var(--itenas-orange); }
        .card-icon { 
            width: 50px; height: 50px; background: #fff7ed; color: var(--itenas-orange); 
            border-radius: 10px; display: flex; align-items: center; justify-content: center; 
            font-size: 1.5rem; margin-bottom: 1.5rem;
        }
        .card h3 { margin-bottom: 1rem; }
        .card li { list-style: none; margin-bottom: 0.5rem; font-size: 0.9rem; color: #555; }
        .card li::before { content: "✔"; color: var(--itenas-orange); margin-right: 8px; }

        /* --- 7. FOOTER --- */
        #kontak { background: #1e293b; color: white; padding: 4rem 1rem; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; max-width: 1200px; margin: 0 auto; }
        .footer-item h3 { color: var(--itenas-orange); margin-bottom: 1.2rem; }
        .footer-item p { color: #94a3b8; line-height: 1.6; margin-bottom: 0.8rem; }
        
        /* --- MODAL LOGIN --- */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); z-index: 2000;
            justify-content: center; align-items: center;
        }
        .modal-box { background: white; width: 100%; max-width: 400px; padding: 2.5rem; border-radius: 16px; position: relative; }
        .close-icon { position: absolute; top: 15px; right: 20px; font-size: 1.5rem; cursor: pointer; }
        .input-group { margin-bottom: 1rem; }
        .input-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; }
        .btn-submit { width: 100%; padding: 12px; background: var(--itenas-orange); color: white; border: none; border-radius: 8px; cursor: pointer; }

    </style>
</head>
<body>

    <header>
        <nav>
            <a href="#" class="logo">
                <img src="logo-itenas.jpg" alt="Logo Itenas">
                <span>ITENAS LOGISTIK</span>
            </a>

            <ul class="nav-menu">
                <li><a href="#beranda" class="nav-link">Beranda</a></li>
                <li><a href="#tentang" class="nav-link">Tentang</a></li>
                <li><a href="#layanan" class="nav-link">Layanan</a></li>
                <li><a href="#kontak" class="nav-link">Kontak</a></li>
                <li><button class="btn-login-nav" onclick="openModal()">Login</button></li>
            </ul>
        </nav>
    </header>

    <section id="beranda">
        <div class="container">
            <h1>Sistem Terpadu<br><span>Fasilitas & Logistik</span></h1>
            <p>Platform satu pintu untuk manajemen peminjaman ruang, barang, dan kendaraan operasional di lingkungan Institut Teknologi Nasional.</p>
            <button class="btn-cta" onclick="location.href='#layanan'">Lihat Katalog</button>
        </div>
    </section>

    <section id="tentang">
        <div class="container">
            <div class="section-header">
                <h2>Tentang Biro Logistik</h2>
            </div>
            <div class="about-content">
                <div class="about-image">
                    <img src="gedung_itenas.jpg" alt="Gedung Itenas">
                </div>
                
                <div class="about-text">
                    <h3 style="margin-bottom: 1rem; font-size: 1.5rem;">Melayani Civitas Akademika</h3>
                    <p style="line-height: 1.8; color: #555; margin-bottom: 1rem;">
                        Biro Sarana dan Prasarana Itenas berkomitmen untuk menyediakan layanan peminjaman fasilitas yang transparan, cepat, dan mudah diakses.
                    </p>
                    <p style="line-height: 1.8; color: #555;">
                        Sistem ini dibuat untuk mempermudah Mahasiswa, Dosen, dan Karyawan dalam mengajukan permohonan penggunaan aset kampus tanpa perlu birokrasi manual yang rumit.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan">
        <div class="container">
            <div class="section-header">
                <h2>Layanan & Fasilitas</h2>
                <p>Pilih kategori peminjaman yang Anda butuhkan.</p>
            </div>
            <div class="card-grid">
                <div class="card">
                    <div class="card-icon">🏢</div>
                    <h3>Ruang Kelas & Aula</h3>
                    <p style="color:#666; margin-bottom:1rem;">Untuk kegiatan perkuliahan pengganti, seminar, atau rapat organisasi.</p>
                    <ul>
                        <li>Gedung Serba Guna (GSG)</li>
                        <li>Ruang Audio Visual</li>
                        <li>Aula Student Center</li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-icon">🎥</div>
                    <h3>Alat Multimedia</h3>
                    <p style="color:#666; margin-bottom:1rem;">Dukungan peralatan dokumentasi dan presentasi.</p>
                    <ul>
                        <li>LCD Proyektor</li>
                        <li>Sound System</li>
                        <li>Kamera & Tripod</li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-icon">🚌</div>
                    <h3>Transportasi</h3>
                    <p style="color:#666; margin-bottom:1rem;">Peminjaman kendaraan dinas untuk kegiatan operasional.</p>
                    <ul>
                        <li>Bus Kampus</li>
                        <li>Mobil Operasional</li>
                        <li>Kendaraan Barang</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak">
        <div class="footer-grid">
            <div class="footer-item">
                <h3>Hubungi Kami</h3>
                <p><strong>Biro Sarana & Prasarana</strong><br>Gedung Rektorat Lt. 1<br>Institut Teknologi Nasional</p>
                <p>Jl. PH.H. Mustofa No. 23<br>Bandung, 40124</p>
            </div>
            <div class="footer-item">
                <h3>Jam Operasional</h3>
                <p>Senin - Jumat: 08.00 - 16.00 WIB</p>
                <p>Sabtu - Minggu: Tutup</p>
                <br>
                <p>Email: logistik@itenas.ac.id</p>
                <p>Telp: (022) 7272215</p>
            </div>
            <div class="footer-item">
                <h3>Bantuan Cepat</h3>
                <p><a href="#" style="color:white; text-decoration:underline;">Panduan Peminjaman</a></p>
                <p><a href="#" style="color:white; text-decoration:underline;">Cek Status Tiket</a></p>
            </div>
        </div>
    </section>

    <div class="modal-overlay" id="authModal">
        <div class="modal-box">
            <span class="close-icon" onclick="closeModal()">&times;</span>
            <h2 style="margin-bottom: 1rem; text-align: center;">Login</h2>
            <form>
                <div class="input-group">
                    <label style="display:block; margin-bottom:8px;">Email / NIP / NRP</label>
                    <input type="text" placeholder="Masukkan ID">
                </div>
                <div class="input-group">
                    <label style="display:block; margin-bottom:8px;">Password</label>
                    <input type="password" placeholder="********">
                </div>
                <button type="submit" class="btn-submit">Masuk</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('authModal');
        function openModal() { modal.style.display = 'flex'; }
        function closeModal() { modal.style.display = 'none'; }
        window.onclick = function(e) { if (e.target == modal) closeModal(); }
    </script>
</body>
</html>