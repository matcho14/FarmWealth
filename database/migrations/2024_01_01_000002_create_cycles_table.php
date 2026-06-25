<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shed_id')->constrained('sheds')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->integer('initial_chicks'); // عدد الكتاكيت الأولي
            $table->integer('mortality_count')->default(0); // عدد النافق
            $table->integer('sold_chicks')->nullable(); // عدد المباع
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cycles');
    }
};
