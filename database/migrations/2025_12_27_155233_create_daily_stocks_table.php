<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_stocks', function (Blueprint $table) {
            $table->id();

            $table->date('stock_date')->unique();

            // INWARD
            $table->string('inward_received')->default(0);
            $table->string('inward_onway')->default(0);
            $table->string('inward_move_to_stock')->default(0);
            $table->string('inward_cancelled')->default(0);

            // JOB CARD
            $table->string('job_card_booked')->default(0);
            $table->string('job_card_cancelled')->default(0);

            // ALLOCATION / RESERVE
            $table->string('allocated_booked')->default(0); // reserve stock
            $table->string('allocated_remove')->default(0); // release reserve

            // STOCK
            $table->string('available_stock')->default(0);
            $table->string('available_onway_stock')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_stocks');
    }
};