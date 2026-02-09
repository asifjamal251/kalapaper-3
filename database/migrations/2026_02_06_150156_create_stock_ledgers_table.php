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
        Schema::create('stock_ledgers', function (Blueprint $table) {

            $table->id();

            $table->string('source_type'); // inward, jobcard
            $table->unsignedBigInteger('source_id')->nullable();

            $table->enum('type', ['in', 'out'])->nullable(); // inward_received, jobcard_booked

            $table->decimal('old_stock',12,2)->default(0);
            $table->decimal('new_stock',12,2)->default(0); // +- stock
            $table->decimal('current_stock',12,2)->default(0); //running_balance

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_ledgers');
    }
};
