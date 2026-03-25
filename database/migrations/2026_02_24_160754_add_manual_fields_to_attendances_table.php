<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->boolean('is_manual')->default(false)->after('status');
            $table->unsignedBigInteger('marked_by')->nullable()->after('is_manual');
            $table->string('manual_reason')->nullable()->after('marked_by');

            $table->foreign('marked_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['marked_by']);
            $table->dropColumn(['is_manual', 'marked_by', 'manual_reason']);
        });
    }
};
