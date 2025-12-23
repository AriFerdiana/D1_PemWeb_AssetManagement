<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// PERUBAHAN DI SINI: Kita pakai nama kelas spesifik, bukan 'return new class'
class AddCoordinatesToLabsTable extends Migration
{
    public function up()
    {
        Schema::table('labs', function (Blueprint $table) {
            // Cek dulu: Kalau kolom 'latitude' BELUM ada, baru buat.
            if (!Schema::hasColumn('labs', 'latitude')) {   
                $table->decimal('latitude', 10, 8)->nullable()->after('description');
            }

            // Cek dulu: Kalau kolom 'longitude' BELUM ada, baru buat.
            if (!Schema::hasColumn('labs', 'longitude')) {  
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
        });
    }

    public function down()
    {
        Schema::table('labs', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']); 
        });
    }
}