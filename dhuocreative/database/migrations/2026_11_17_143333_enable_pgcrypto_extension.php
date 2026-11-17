
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // aktifkan extension pgcrypto untuk hash pw
        DB::unprepared('CREATE EXTENSION IF NOT EXISTS pgcrypto');
    }

    public function down(): void
    {
        
    }
};
