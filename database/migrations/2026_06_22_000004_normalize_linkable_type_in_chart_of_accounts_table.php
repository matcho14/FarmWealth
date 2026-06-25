<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            \DB::table('chart_of_accounts')
                ->where('linkable_type', 'Client')
                ->update(['linkable_type' => \App\Models\Client::class]);

            \DB::table('chart_of_accounts')
                ->where('linkable_type', 'Supplier')
                ->update(['linkable_type' => \App\Models\Supplier::class]);
        });
    }

    public function down(): void {
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            \DB::table('chart_of_accounts')
                ->where('linkable_type', \App\Models\Client::class)
                ->update(['linkable_type' => 'Client']);

            \DB::table('chart_of_accounts')
                ->where('linkable_type', \App\Models\Supplier::class)
                ->update(['linkable_type' => 'Supplier']);
        });
    }
};
