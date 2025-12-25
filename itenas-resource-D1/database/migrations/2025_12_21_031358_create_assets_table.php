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
            
            // 1. Relasi ke Lab (Wajib)
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade');
            
            // 2. Relasi ke Category (INI YANG TADI ERROR "Unknown Column")
            // Kita buat nullable() jaga-jaga kalau ada aset tanpa kategori, tapi sebaiknya diisi.
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            // 3. Data Identitas Aset
            $table->string('name');
            
            // Ganti 'serial_number' jadi 'code' agar sesuai dengan Seeder (IF-7590-1)
            $table->string('code')->unique(); 
            
            // 4. Data Fisik
            $table->integer('stock')->default(1);
            
            // Ganti 'condition' jadi 'status' agar sesuai logika Controller (available, maintenance)
            $table->string('status')->default('available'); 
            
            // Ganti 'image_path' jadi 'image' agar sesuai Controller storage
            $table->string('image')->nullable();
            
            // 5. Data Tambahan
            $table->string('prodi')->nullable(); // Penting untuk filter per jurusan
            $table->text('description')->nullable();
            $table->decimal('rental_price', 10, 2)->default(0); // Opsional untuk denda/sewa

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