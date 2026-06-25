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
            $table->integer('quantity')->nullable()->after('type');
            $table->decimal('weight', 10, 2)->nullable()->after('quantity');
        });

        Schema::table('cycles', function (Blueprint $table) {
            $table->decimal('total_weight', 12, 2)->default(0)->after('sold_chicks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_records', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'weight']);
        });

        Schema::table('cycles', function (Blueprint $table) {
            $table->dropColumn('total_weight');
        });
    }
};
