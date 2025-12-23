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
            $table->foreignId('prodi_id')->constrained('prodis')->onDelete('cascade'); // Relasi ke Prodi
            $table->string('name'); // Nama Lab
            $table->string('building_name'); // Gedung 14, dll
            $table->string('room_number')->nullable();
            $table->integer('capacity')->default(30);
            $table->decimal('latitude', 10, 8)->nullable(); // Untuk API Maps
            $table->decimal('longitude', 11, 8)->nullable(); // Untuk API Maps
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labs');
    }
};