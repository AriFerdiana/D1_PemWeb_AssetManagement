<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labs', function (Blueprint $table) {
            $table->id();
            
            // 1. Ubah jadi nullable() agar Seeder Ruangan Umum tidak error
            $table->foreignId('prodi_id')->nullable()->constrained('prodis')->onDelete('cascade'); 
            
            $table->string('name');
            $table->string('building_name')->nullable();
            $table->string('room_number')->nullable();
            $table->integer('capacity')->default(30);
            
            // 2. INI YANG SEBELUMNYA KURANG (Penyebab Error Seeder)
            $table->string('status')->default('available'); 

            // 3. Koordinat (Gunakan string agar aman saat import data seeder)
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labs');
    }
};