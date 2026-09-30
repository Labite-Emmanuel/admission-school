<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("
                ALTER TABLE users
                ALTER COLUMN end_registration TYPE VARCHAR(20)
                USING end_registration::text
            ");
            DB::statement("
                ALTER TABLE users
                ALTER COLUMN end_registration DROP NOT NULL
            ");
        } else {
            // MySQL / MariaDB
            DB::statement("ALTER TABLE users MODIFY COLUMN end_registration VARCHAR(20) NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // On pourrait tenter de revenir à TIMESTAMP, mais comme
        // les valeurs 'no' / 'process' / 'end' ne sont pas des dates,
        // on laisse simplement le type varchar lors du rollback.
    }
};
