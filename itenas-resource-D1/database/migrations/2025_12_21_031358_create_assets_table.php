<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            
            // 1. Relasi (Wajib ada Lab & Kategori)
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            // 2. Data Identitas
            $table->string('name');
            $table->string('code')->unique(); // Kode Aset (misal: IF-001)
            
            // 3. Data Fisik (PERBAIKAN DI SINI)
            // Ubah 'stock' jadi 'quantity' agar sesuai Seeder
            $table->integer('quantity')->default(1); 
            
            // Status Peminjaman (Available, Borrowed, Maintenance)
            $table->string('status')->default('available'); 
            
            // Kondisi Fisik (Good, Damaged, Lost) - INI DITAMBAHKAN
            $table->string('condition')->default('good');   
            
            // Gambar (PERBAIKAN TIPE DATA)
            // Ubah jadi TEXT agar muat link panjang dari internet
            $table->text('image')->nullable();
            
            // 4. Data Tambahan
            $table->text('description')->nullable();
            // Kolom 'prodi' saya hapus karena redundan (sudah bisa diambil via lab_id -> prodi)
            // Kolom 'rental_price' opsional, boleh dibiarkan jika butuh
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};