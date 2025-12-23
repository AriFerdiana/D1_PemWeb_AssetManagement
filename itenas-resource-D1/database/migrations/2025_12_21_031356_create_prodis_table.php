<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Informatika
            $table->string('code')->unique(); // Contoh: IF
            $table->string('faculty'); // Contoh: FTI
            $table->string('head_of_prodi')->nullable(); // Kaprodi
            $table->string('contact_email')->nullable();
            $table->string('location_office')->nullable(); // Lokasi kantor prodi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};