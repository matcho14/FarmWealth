<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // إنشاء جدول منفصل لتسجيل النافق مع تحديد الدور
        Schema::create('mortality_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained('cycles')->onDelete('cascade');
            $table->integer('floor_number'); // رقم الدور
            $table->integer('count');        // عدد النافق في هذا الدور
            $table->date('record_date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortality_records');
    }
};
