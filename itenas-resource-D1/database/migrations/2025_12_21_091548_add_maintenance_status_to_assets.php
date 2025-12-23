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
    Schema::table('assets', function (Blueprint $table) {
        // Cek dulu, kalau kolom 'status' belum ada, baru kita tambah
        if (!Schema::hasColumn('assets', 'status')) {
            $table->string('status')->default('available')->after('name'); 
        } else {
            // Kalau sudah ada tapi mau diupdate panjangnya
            $table->string('status')->default('available')->change();
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            //
        });
    }
};
