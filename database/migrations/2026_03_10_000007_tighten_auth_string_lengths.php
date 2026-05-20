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
        // Trim existing values first so shrinking cannot fail.
        $this->trimColumnValues('users', 'id', 'password', 100);
        $this->trimColumnValues('password_reset_tokens', 'email', 'email', 191);
        $this->trimColumnValues('password_reset_tokens', 'email', 'token', 100);
        $this->trimColumnValues('sessions', 'id', 'id', 128);

        Schema::table('users', function (Blueprint $table) {
            // Bcrypt is 60 chars, Argon hashes are generally below 100 chars.
            $table->string('password', 100)->change();
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 191)->change();
            $table->string('token', 100)->change();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->string('id', 128)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password', 255)->change();
        });

        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('email', 255)->change();
            $table->string('token', 255)->change();
        });

        Schema::table('sessions', function (Blueprint $table) {
            $table->string('id', 255)->change();
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
