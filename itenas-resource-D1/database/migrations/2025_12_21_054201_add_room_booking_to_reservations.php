<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('reservations', function (Blueprint $table) {
        // Tipe booking: 'asset' (default) atau 'room'
        $table->string('type')->default('asset')->after('id');
        
        // Jika type='room', kolom ini akan terisi ID Lab
        $table->foreignId('lab_id')->nullable()->after('user_id')->constrained('labs')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('reservations', function (Blueprint $table) {
        $table->dropForeign(['lab_id']);
        $table->dropColumn(['type', 'lab_id']);
    });
}
};
