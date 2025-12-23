<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained('labs')->onDelete('cascade'); // Relasi ke Lab
            $table->string('name');
            $table->string('serial_number')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable(); // Untuk Storage Facade
            $table->integer('stock')->default(1);
            $table->enum('condition', ['good', 'maintenance', 'broken'])->default('good');
            $table->decimal('rental_price', 10, 2)->default(0); // Harga sewa/denda
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};