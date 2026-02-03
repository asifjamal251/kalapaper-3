<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->date('transaction_date')->unique();

  
            $table->string('opening_inprocess')->default(0);


            $table->string('total_book')->default(0);
            $table->string('total_delivered')->default(0);


            $table->string('closing_inprocess')->default(0);

            $table->timestamps();

            $table->index('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
