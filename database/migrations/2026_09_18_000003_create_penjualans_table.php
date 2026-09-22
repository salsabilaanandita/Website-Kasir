<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom category_id ke products jika belum ada
        if (!Schema::hasColumn('products', 'category_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
                $table->string('kode_produk')->nullable()->after('category_id');
                $table->text('deskripsi')->nullable()->after('img');
            });
        }

        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('shift_id')->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('poin_digunakan', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('bayar', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);
            $table->string('metode_bayar')->default('Tunai'); // Tunai, Transfer, QRIS
            $table->string('status')->default('selesai'); // selesai, retur
            $table->string('kasir_nama')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualans')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('qty');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('diskon', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
        Schema::dropIfExists('penjualans');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'kode_produk', 'deskripsi']);
        });
    }
};
