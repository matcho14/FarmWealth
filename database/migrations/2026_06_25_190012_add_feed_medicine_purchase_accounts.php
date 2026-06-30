<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::insert('INSERT INTO chart_of_accounts (code, name, account_type, is_active, opening_balance) VALUES (?, ?, ?, ?, ?)', [
            'feed_purchase',
            'مشتريات علف',
            'expense',
            true,
            0
        ]);

        DB::insert('INSERT INTO chart_of_accounts (code, name, account_type, is_active, opening_balance) VALUES (?, ?, ?, ?, ?)', [
            'medicine_purchase',
            'مشتريات أدوية',
            'expense',
            true,
            0
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('chart_of_accounts')->whereIn('code', ['feed_purchase', 'medicine_purchase'])->delete();
    }
};
