<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->foreignId('produk_id')->nullable()->change();
            $table->foreign('produk_id')->references('id')->on('produk')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('detail_transaksi', function (Blueprint $table) {
            $table->dropForeign(['produk_id']);
            $table->foreignId('produk_id')->nullable(false)->change();
            $table->foreign('produk_id')->references('id')->on('produk')->cascadeOnDelete();
        });
    }
};
