<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// PERUBAHAN DI SINI: Kita pakai nama kelas spesifik, bukan 'return new class'
class AddCoordinatesToLabsTable extends Migration
{
public function up(): void
{
    Schema::table('labs', function (Blueprint $table) {
        $table->string('latitude')->nullable()->after('description');
        $table->string('longitude')->nullable()->after('latitude');
    });
}

public function down(): void
{
    Schema::table('labs', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude']);
    });
}
}