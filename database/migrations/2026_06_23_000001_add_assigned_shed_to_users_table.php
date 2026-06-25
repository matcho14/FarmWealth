<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_shed_id')->nullable()->after('permissions');
            $table->foreign('assigned_shed_id')->references('id')->on('sheds')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['assigned_shed_id']);
            $table->dropColumn('assigned_shed_id');
        });
    }
};
