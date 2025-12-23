<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users'); // Siapa yang lapor rusak
            $table->text('description'); // Rusak kenapa
            $table->enum('status', ['pending', 'in_progress', 'fixed', 'unrepairable'])->default('pending');
            $table->decimal('repair_cost', 10, 2)->nullable();
            $table->date('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};