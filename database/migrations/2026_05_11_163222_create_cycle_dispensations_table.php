<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cycle_dispensations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('cycles')->onDelete('cascade');
            $table->foreignId('shed_id')->constrained('sheds');    // العنبر المصدر للصرف
            $table->foreignId('item_id')->constrained('items');
            $table->integer('floor_number');                        // رقم الدور الذي صُرف عليه
            $table->decimal('quantity', 15, 3);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->date('dispensation_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cycle_dispensations'); }
};
