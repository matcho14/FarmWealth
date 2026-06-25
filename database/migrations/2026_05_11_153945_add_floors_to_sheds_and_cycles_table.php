<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // إضافة عدد الأدوار للعنبر
        Schema::table('sheds', function (Blueprint $table) {
            $table->integer('floors')->default(1)->after('description'); // عدد الأدوار في العنبر
        });

        // إضافة توزيع الكتاكيت على الأدوار في الدورة (JSON)
        Schema::table('cycles', function (Blueprint $table) {
            $table->json('floor_chicks')->nullable()->after('initial_chicks'); // {"1": 500, "2": 300, "3": 200}
        });
    }

    public function down(): void
    {
        Schema::table('sheds', function (Blueprint $table) {
            $table->dropColumn('floors');
        });

        Schema::table('cycles', function (Blueprint $table) {
            $table->dropColumn('floor_chicks');
        });
    }
};
