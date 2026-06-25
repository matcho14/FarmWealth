<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('cycles')->onDelete('cascade');
            $table->enum('type', ['expense', 'revenue']); // مصروف أو إيراد
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->date('record_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
