<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $deletedUsers = DB::table('users')
            ->whereNotNull('deleted_at')
            ->select('id')
            ->get();

        foreach ($deletedUsers as $deletedUser) {
            $archiveToken = 'deleted_' . $deletedUser->id;

            DB::table('users')
                ->where('id', $deletedUser->id)
                ->update([
                    'username' => $archiveToken,
                    'email' => $archiveToken . '@deleted.local',
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: archived credentials should stay unique to preserve data integrity.
    }
};
