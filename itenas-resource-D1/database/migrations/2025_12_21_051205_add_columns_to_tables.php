<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
    {
        // Cek dulu: Kalau kolom 'description' BELUM ada di tabel 'labs', baru tambahkan
        if (!Schema::hasColumn('labs', 'description')) {
            Schema::table('labs', function (Blueprint $table) {
                $table->text('description')->nullable()->after('name');
            });
        }

        // Cek dulu: Kalau kolom 'kaprodi_name' BELUM ada di tabel 'prodis', baru tambahkan
        if (!Schema::hasColumn('prodis', 'kaprodi_name')) {
            Schema::table('prodis', function (Blueprint $table) {
                $table->string('kaprodi_name')->nullable()->after('name');
            });
        }
    }

public function down()
{
    Schema::table('labs', function (Blueprint $table) {
        $table->dropColumn('description');
    });
    Schema::table('prodis', function (Blueprint $table) {
        $table->dropColumn('kaprodi_name');
    });
}
};
