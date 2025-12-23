<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('maintenances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('asset_id')->constrained()->onDelete('cascade'); // Relasi ke Aset
        $table->date('date'); // Tanggal perawatan
        $table->string('type'); // Perbaikan / Perawatan Rutin
        $table->text('description'); // Detail kerusakan
        $table->decimal('cost', 15, 2)->default(0); // Biaya
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
