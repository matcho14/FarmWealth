<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('financial_records', function (Blueprint $table) {
            $table->enum('payment_type', ['cash', 'credit'])->default('cash')->after('amount');
            $table->foreignId('client_id')->nullable()->after('payment_type')->constrained()->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('financial_records', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn(['payment_type', 'client_id']);
        });
    }
};