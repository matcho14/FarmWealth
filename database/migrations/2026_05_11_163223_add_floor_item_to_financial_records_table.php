<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('financial_records', function (Blueprint $table) {
            $table->integer('floor_number')->nullable()->after('quantity'); // الدور المصروف عليه
            $table->foreignId('item_id')->nullable()->after('floor_number')
                ->constrained('items')->nullOnDelete();                    // الصنف المصروف
            $table->unsignedBigInteger('dispensation_id')->nullable()->after('item_id'); // رابط للصرفية
        });
    }
    public function down(): void {
        Schema::table('financial_records', function (Blueprint $table) {
            $table->dropColumn(['floor_number','item_id','dispensation_id']);
        });
    }
};
