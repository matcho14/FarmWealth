<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('mortality_records')->truncate();
        DB::table('medicine_dispensations')->truncate();
        DB::table('cycle_dispensations')->truncate();
        DB::table('shed_inventories')->truncate();
        DB::table('inventory_transfers')->truncate();
        DB::table('financial_records')->truncate();
        DB::table('purchase_invoice_items')->truncate();
        DB::table('sale_invoice_items')->truncate();
        DB::table('journal_entries')->truncate();
        DB::table('journal_entry_lines')->truncate();
        DB::table('transactions')->truncate();
        DB::table('cycles')->truncate();
        DB::table('sheds')->truncate();
        DB::table('clients')->truncate();
        DB::table('suppliers')->truncate();
        DB::table('purchase_invoices')->truncate();
        DB::table('sale_invoices')->truncate();

        DB::table('items')->update(['quantity_in_stock' => 0, 'last_purchase_price' => 0]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
    }
};