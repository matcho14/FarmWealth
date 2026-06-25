<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('shed_id')->constrained('sheds');  // العنبر المستلم
            $table->decimal('quantity', 15, 3);                 // الكمية المحولة
            $table->decimal('unit_cost', 15, 2)->default(0);    // تكلفة الوحدة وقت التحويل
            $table->decimal('total_cost', 15, 2)->default(0);   // الإجمالي
            $table->date('transfer_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('inventory_transfers'); }
};
