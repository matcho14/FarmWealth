<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChartOfAccountsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('chart_of_accounts')->truncate();
        Schema::enableForeignKeyConstraints();
        DB::table('chart_of_accounts')->insert([
            // الأصول
            ['id' => 1, 'code' => '1000', 'name' => 'الأصول', 'parent_id' => null, 'account_type' => 'asset_current', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'code' => '1100', 'name' => 'الأصول المتداولة', 'parent_id' => 1, 'account_type' => 'asset_current', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'code' => '1110', 'name' => 'الصندوق والنقدية', 'parent_id' => 2, 'account_type' => 'asset_current', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'code' => '1120', 'name' => 'العملاء', 'parent_id' => 2, 'account_type' => 'asset_current', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'code' => '1130', 'name' => 'المخازن', 'parent_id' => 2, 'account_type' => 'asset_current', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'code' => '1140', 'name' => 'المدفوعات المقدمة', 'parent_id' => 2, 'account_type' => 'asset_current', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 7, 'code' => '1200', 'name' => 'الأصول الثابتة', 'parent_id' => 1, 'account_type' => 'asset_fixed', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'code' => '1210', 'name' => 'المباني والمنشآت', 'parent_id' => 7, 'account_type' => 'asset_fixed', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'code' => '1220', 'name' => 'الأثاث والمعدات', 'parent_id' => 7, 'account_type' => 'asset_fixed', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'code' => '1230', 'name' => 'مركبات', 'parent_id' => 7, 'account_type' => 'asset_fixed', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'code' => '1240', 'name' => 'أصول أخرى', 'parent_id' => 7, 'account_type' => 'asset_fixed', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],

            // الخصوم
            ['id' => 12, 'code' => '2000', 'name' => 'الخصوم', 'parent_id' => null, 'account_type' => 'liability_current', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'code' => '2100', 'name' => 'الخصوم المتداولة', 'parent_id' => 12, 'account_type' => 'liability_current', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'code' => '2110', 'name' => 'الموردين', 'parent_id' => 13, 'account_type' => 'liability_current', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'code' => '2120', 'name' => 'المقبوضات المقدمة', 'parent_id' => 13, 'account_type' => 'liability_current', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'code' => '2130', 'name' => 'الضرائب المدفوعة', 'parent_id' => 13, 'account_type' => 'liability_current', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 17, 'code' => '2200', 'name' => 'الخصوم طويلة الأجل', 'parent_id' => 12, 'account_type' => 'liability_long', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'code' => '2210', 'name' => 'قروض طويلة الأجل', 'parent_id' => 17, 'account_type' => 'liability_long', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'code' => '2220', 'name' => 'خصوم أخرى', 'parent_id' => 17, 'account_type' => 'liability_long', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],

            // حقوق الملكية
            ['id' => 20, 'code' => '3000', 'name' => 'حقوق الملكية', 'parent_id' => null, 'account_type' => 'equity', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'code' => '3100', 'name' => 'رأس المال', 'parent_id' => 20, 'account_type' => 'equity', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'code' => '3200', 'name' => 'الأرباح المحتجزة', 'parent_id' => 20, 'account_type' => 'equity', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'code' => '3300', 'name' => 'تسويات الأرباح', 'parent_id' => 20, 'account_type' => 'equity', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],

            // الإيرادات
            ['id' => 24, 'code' => '4000', 'name' => 'الإيرادات', 'parent_id' => null, 'account_type' => 'revenue', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'code' => '4100', 'name' => 'إيرادات المبيعات', 'parent_id' => 24, 'account_type' => 'revenue', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'code' => '4200', 'name' => 'إيرادات أخرى', 'parent_id' => 24, 'account_type' => 'revenue', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],

            // المصاريف
            ['id' => 27, 'code' => '5000', 'name' => 'المصاريف', 'parent_id' => null, 'account_type' => 'expense', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'code' => '5100', 'name' => 'مصاريف الإنتاج', 'parent_id' => 27, 'account_type' => 'expense', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'code' => '5110', 'name' => 'علف', 'parent_id' => 28, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'code' => '5120', 'name' => 'أدوية وعلاجات', 'parent_id' => 28, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 31, 'code' => '5130', 'name' => 'كهرباء', 'parent_id' => 28, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 32, 'code' => '5140', 'name' => 'غاز ووقود', 'parent_id' => 28, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'code' => '5150', 'name' => 'مياه', 'parent_id' => 28, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'code' => '5160', 'name' => 'رواتب وعمولات', 'parent_id' => 28, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 35, 'code' => '5170', 'name' => 'صيانة', 'parent_id' => 28, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 36, 'code' => '5180', 'name' => 'فحوصات واختبارات', 'parent_id' => 28, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'code' => '5190', 'name' => 'مصاريف إنتاج أخرى', 'parent_id' => 28, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 38, 'code' => '5200', 'name' => 'مصاريف إدارية وعمومية', 'parent_id' => 27, 'account_type' => 'expense', 'is_parent' => true, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 39, 'code' => '5210', 'name' => 'إيجارات', 'parent_id' => 38, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 40, 'code' => '5220', 'name' => 'رواتب إدارية', 'parent_id' => 38, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 41, 'code' => '5230', 'name' => 'مستلزمات مكتبية', 'parent_id' => 38, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 42, 'code' => '5240', 'name' => 'اتصالات وإنترنت', 'parent_id' => 38, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 43, 'code' => '5250', 'name' => 'تسويق وإعلان', 'parent_id' => 38, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 44, 'code' => '5260', 'name' => 'تأمينات', 'parent_id' => 38, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 45, 'code' => '5270', 'name' => 'مصاريف إدارية أخرى', 'parent_id' => 38, 'account_type' => 'expense', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => null, 'linkable_id' => null, 'created_at' => now(), 'updated_at' => now()],

            ['id' => 46, 'code' => '11201', 'name' => 'محمد كمال', 'parent_id' => 4, 'account_type' => 'asset_current', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => 'App\Models\Client', 'linkable_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 47, 'code' => '2201', 'name' => 'اسلام هايدا', 'parent_id' => 14, 'account_type' => 'liability_current', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => 'App\Models\Supplier', 'linkable_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 48, 'code' => '2202', 'name' => 'ايفا', 'parent_id' => 14, 'account_type' => 'liability_current', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => 'App\Models\Supplier', 'linkable_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 49, 'code' => '2203', 'name' => 'مورد علف', 'parent_id' => 14, 'account_type' => 'liability_current', 'is_parent' => false, 'is_active' => true, 'opening_balance' => 0, 'shed_id' => null, 'linkable_type' => 'App\Models\Supplier', 'linkable_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
