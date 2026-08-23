<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow a salesman to cancel a started visit with a required reason.
     */
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
            $table->text('cancelled_reason')->nullable()->after('cancelled_at');
            $table->unsignedBigInteger('cancelled_by')->nullable()->after('cancelled_reason');

            $table->foreign('cancelled_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['cancelled_by']);
            $table->dropColumn(['cancelled_at', 'cancelled_reason', 'cancelled_by']);
        });
    }
};