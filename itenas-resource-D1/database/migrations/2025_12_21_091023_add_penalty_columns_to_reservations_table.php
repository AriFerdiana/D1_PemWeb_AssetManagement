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
    Schema::table('reservations', function (Blueprint $table) {
        // Cek dulu apakah kolom sudah ada sebelum menambah (untuk keamanan)
        if (!Schema::hasColumn('reservations', 'penalty')) {
            $table->decimal('penalty', 10, 2)->default(0);
        }
        if (!Schema::hasColumn('reservations', 'payment_status')) {
            $table->enum('payment_status', ['none', 'unpaid', 'paid'])->default('none');
        }
        if (!Schema::hasColumn('reservations', 'payment_method')) {
            $table->string('payment_method')->nullable();
        }
    });
}

public function down()
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropColumn(['penalty', 'payment_status', 'payment_method']);
    });
}
};
