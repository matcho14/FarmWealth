<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_dispensations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->foreignId('cycle_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2); // الكمية المنصرفة
            $table->decimal('total_cost', 10, 2); // التكلفة الإجمالية (محسوبة بأسعار FIFO)
            $table->date('dispensation_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_dispensations');
    }
};
