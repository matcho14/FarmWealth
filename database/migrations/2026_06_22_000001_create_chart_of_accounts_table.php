<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('account_type', 50); // asset_current, asset_fixed, liability_current, liability_long, equity, revenue, expense
            $table->boolean('is_parent')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->unsignedBigInteger('shed_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('chart_of_accounts')->onDelete('set null');
            $table->foreign('shed_id')->references('id')->on('sheds')->onDelete('set null');
            $table->index(['account_type', 'is_parent']);
            $table->index('parent_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('chart_of_accounts');
    }
};
