<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('financial_records', function (Blueprint $table) {
            $table->unsignedBigInteger('shed_id')->nullable()->after('cycle_id');
            $table->unsignedBigInteger('chart_of_account_id')->nullable()->after('shed_id');

            $table->foreign('shed_id')->references('id')->on('sheds')->onDelete('set null');
            $table->foreign('chart_of_account_id')->references('id')->on('chart_of_accounts')->onDelete('set null');
            $table->index('shed_id');
            $table->index('chart_of_account_id');
        });
    }

    public function down(): void {
        Schema::table('financial_records', function (Blueprint $table) {
            $table->dropForeign(['chart_of_account_id']);
            $table->dropForeign(['shed_id']);
            $table->dropIndex(['chart_of_account_id']);
            $table->dropIndex(['shed_id']);
            $table->dropColumn(['shed_id', 'chart_of_account_id']);
        });
    }
};
