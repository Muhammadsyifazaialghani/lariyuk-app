<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('last_bib_searches', function (Blueprint $table) {
            $table->id();
            $table->string('bib_number');
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('last_bib_searches');
    }
};
