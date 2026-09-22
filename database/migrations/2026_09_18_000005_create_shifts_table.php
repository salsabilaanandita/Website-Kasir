<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('waktu_buka');
            $table->dateTime('waktu_tutup')->nullable();
            $table->decimal('modal_awal', 15, 2)->default(0);
            $table->decimal('total_penjualan', 15, 2)->default(0);
            $table->decimal('total_tunai', 15, 2)->default(0);
            $table->decimal('kas_akhir', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->string('status')->default('buka'); // buka, tutup
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
