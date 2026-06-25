<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // حذف البيانات غير المرغوب فيها مع الحفاظ على المستخدمين وصلاحياتهم
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // حذف البيانات المرتبطة بالدورات
        DB::table('mortality_records')->truncate();
        DB::table('dispense_records')->truncate();
        DB::table('cycle_dispensations')->truncate();
        DB::table('financial_records')->truncate();
        DB::table('cycles')->truncate();
        DB::table('shed_inventories')->truncate();
        DB::table('inventory_transfers')->truncate();
        DB::table('journal_entries')->truncate();
        DB::table('journal_entry_lines')->truncate();
        DB::table('transactions')->truncate();
        DB::table('sheds')->truncate();
        
        // حذف الموردين والعملاء
        DB::table('suppliers')->truncate();
        DB::table('clients')->truncate();
        
        // إعادة تعيين رصيد الأصناف إلى صفر
        DB::table('items')->update(['stock' => 0]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // لا شيء للتراجع - هذه عملية حذف
    }
};