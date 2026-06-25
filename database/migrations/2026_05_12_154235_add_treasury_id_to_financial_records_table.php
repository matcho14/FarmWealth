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
        Schema::table('financial_records', function (Blueprint $table) {
            $table->foreignId('treasury_id')->nullable()->constrained('treasuries')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_records', function (Blueprint $table) {
            $table->dropForeign(['treasury_id']);
            $table->dropColumn('treasury_id');
        });
    }
};
