<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trim existing values first so column shrinking does not fail.
        $this->trimColumnValues('users', 'id', 'name', 100);
        $this->trimColumnValues('users', 'id', 'username', 60);
        $this->trimColumnValues('users', 'id', 'email', 191);
        $this->trimColumnValues('users', 'id', 'alamat', 200);
        $this->trimColumnValues('users', 'id', 'nomor_hp', 15);

        $this->trimColumnValues('roles', 'id', 'name', 50);
        $this->trimColumnValues('kategori', 'id', 'nama', 80);
        $this->trimColumnValues('ukuran', 'id', 'nama', 20);
        $this->trimColumnValues('merek', 'id', 'nama', 80);
        $this->trimColumnValues('metode_pembayaran', 'id', 'nama', 80);

        $this->trimColumnValues('produk', 'id', 'nama', 150);
        $this->trimColumnValues('produk', 'id', 'gambarproduk', 191);
        $this->trimColumnValues('produk', 'id', 'gambarproduk1', 191);
        $this->trimColumnValues('produk', 'id', 'gambarproduk2', 191);
        $this->trimColumnValues('produk', 'id', 'gambarproduk3', 191);

        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('username', 60)->change();
            $table->string('email', 191)->change();
            $table->string('alamat', 200)->nullable()->change();
            $table->string('nomor_hp', 15)->nullable()->change();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('name', 50)->change();
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->string('nama', 80)->change();
        });

        Schema::table('ukuran', function (Blueprint $table) {
            $table->string('nama', 20)->change();
        });

        Schema::table('merek', function (Blueprint $table) {
            $table->string('nama', 80)->change();
        });

        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->string('nama', 80)->change();
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->string('nama', 150)->change();
            $table->text('deskripsi')->nullable()->change();
            $table->string('gambarproduk', 191)->nullable()->change();
            $table->string('gambarproduk1', 191)->nullable()->change();
            $table->string('gambarproduk2', 191)->nullable()->change();
            $table->string('gambarproduk3', 191)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('username', 255)->change();
            $table->string('email', 255)->change();
            $table->string('alamat', 255)->nullable()->change();
            $table->string('nomor_hp', 255)->nullable()->change();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });

        Schema::table('kategori', function (Blueprint $table) {
            $table->string('nama', 255)->change();
        });

        Schema::table('ukuran', function (Blueprint $table) {
            $table->string('nama', 255)->change();
        });

        Schema::table('merek', function (Blueprint $table) {
            $table->string('nama', 255)->change();
        });

        Schema::table('metode_pembayaran', function (Blueprint $table) {
            $table->string('nama', 255)->change();
        });

        Schema::table('produk', function (Blueprint $table) {
            $table->string('nama', 255)->change();
            $table->string('deskripsi', 255)->nullable(false)->change();
            $table->string('gambarproduk', 255)->nullable(false)->change();
            $table->string('gambarproduk1', 255)->nullable(false)->change();
            $table->string('gambarproduk2', 255)->nullable(false)->change();
            $table->string('gambarproduk3', 255)->nullable(false)->change();
        });
    }

    private function trimColumnValues(string $table, string $primaryKey, string $column, int $maxLength): void
    {
        DB::table($table)
            ->select($primaryKey, $column)
            ->orderBy($primaryKey)
            ->chunkById(200, function ($rows) use ($table, $primaryKey, $column, $maxLength) {
                foreach ($rows as $row) {
                    if ($row->{$column} === null) {
                        continue;
                    }

                    $value = (string) $row->{$column};
                    if (mb_strlen($value) <= $maxLength) {
                        continue;
                    }

                    DB::table($table)
                        ->where($primaryKey, $row->{$primaryKey})
                        ->update([$column => mb_substr($value, 0, $maxLength)]);
                }
            }, $primaryKey);
    }
};
