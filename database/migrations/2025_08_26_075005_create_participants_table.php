<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_participants_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Peserta
            $table->string('bib_number')->unique(); // Nomor BIB, harus unik
            $table->enum('status', ['registered', 'checked_in'])->default('registered'); // Status
            $table->timestamp('checked_in_at')->nullable(); // Waktu check-in
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};