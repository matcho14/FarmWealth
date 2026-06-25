<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chart_of_account_id');
            $table->unsignedBigInteger('service_usage_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->date('date');
            $table->timestamps();

            $table->foreign('chart_of_account_id')
                  ->references('id')
                  ->on('chart_of_accounts')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};