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
        Schema::table('reservations', function (Blueprint $table) {
            // Kita tambahkan 2 kolom baru: Jumlah Denda & Status Denda
            $table->integer('penalty_amount')->default(0)->after('status'); 
            $table->enum('penalty_status', ['paid', 'unpaid'])->default('unpaid')->after('penalty_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['penalty_amount', 'penalty_status']);
        });
    }
};