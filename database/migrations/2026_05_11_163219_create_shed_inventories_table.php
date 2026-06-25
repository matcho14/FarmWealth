<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shed_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shed_id')->constrained('sheds')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->decimal('quantity', 15, 3)->default(0); // الكمية الموجودة في مخزن العنبر
            $table->decimal('avg_unit_cost', 15, 2)->default(0); // متوسط تكلفة الوحدة
            $table->timestamps();
            $table->unique(['shed_id', 'item_id']); // كل صنف مرة واحدة في كل عنبر
        });
    }
    public function down(): void { Schema::dropIfExists('shed_inventories'); }
};
