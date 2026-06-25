<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicine_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2); // الكمية المشتراة
            $table->decimal('remaining_quantity', 10, 2); // الكمية المتبقية (لـ FIFO)
            $table->decimal('price', 10, 2); // سعر الوحدة في هذه الشحنة
            $table->date('entry_date'); // تاريخ الإدخال
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicine_entries');
    }
};
