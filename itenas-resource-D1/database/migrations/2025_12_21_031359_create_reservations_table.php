<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Peminjam
            $table->string('transaction_code')->unique(); // KODE-2025001
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('purpose'); // Keperluan
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned', 'overdue'])->default('pending');
            $table->text('rejection_note')->nullable();
            $table->string('proposal_file')->nullable(); // Upload Proposal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};