<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE attendances MODIFY COLUMN checkin_method ENUM('qr','gps','manual','device','finger','card') NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE attendances MODIFY COLUMN checkin_method ENUM('qr','gps','manual','device') NULL");
    }
};
