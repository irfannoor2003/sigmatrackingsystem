<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'admin',
                'salesman',
                'saleshead',
                'hr',
                'it',
                'account',
                'store',
                'office_boy'
            ) NOT NULL DEFAULT 'salesman'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'admin',
                'salesman',
                'it',
                'account',
                'store',
                'office_boy'
            ) NOT NULL DEFAULT 'salesman'
        ");
    }
};
