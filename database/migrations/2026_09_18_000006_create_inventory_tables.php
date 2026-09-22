<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('action'); // login, logout, create, update, delete
            $table->string('modul')->nullable(); // products, users, penjualan, dll
            $table->text('deskripsi')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('tipe'); // masuk, keluar, adjustment
            $table->integer('qty');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->string('referensi')->nullable(); // no transaksi / no pembelian
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('no_adjustment')->unique();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('stok_sistem');
            $table->integer('stok_fisik');
            $table->integer('selisih');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('no_opname')->unique();
            $table->date('tanggal');
            $table->string('status')->default('draft'); // draft, selesai
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('return_penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('no_return')->unique();
            $table->foreignId('penjualan_id')->nullable()->constrained('penjualans')->nullOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('qty');
            $table->decimal('total_return', 15, 2);
            $table->string('alasan');
            $table->string('status')->default('proses'); // proses, selesai
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_penjualans');
        Schema::dropIfExists('stock_opnames');
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('activity_logs');
    }
};
