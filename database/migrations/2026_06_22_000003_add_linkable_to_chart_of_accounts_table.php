<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->string('linkable_type')->nullable()->after('shed_id');
            $table->unsignedBigInteger('linkable_id')->nullable()->after('linkable_type');
            $table->index(['linkable_type', 'linkable_id']);
        });
    }

    public function down(): void {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->dropIndex(['linkable_type', 'linkable_id']);
            $table->dropColumn(['linkable_type', 'linkable_id']);
        });
    }
};
