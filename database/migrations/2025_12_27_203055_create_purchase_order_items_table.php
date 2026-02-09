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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sold_to')->nullable()->constrained('parties')->nullOnDelete();
            $table->foreignId('quality_id')->nullable()->constrained()->nullOnDelete();
            $table->string('gsm');
            $table->enum('type', ['Reel', 'Sheet']);
            $table->enum('grain', ['Long', 'Short']);
            $table->string('item_number');
            $table->string('length_cm');
            $table->string('length_inch');
            $table->string('width_cm');
            $table->string('width_inch');
            $table->string('ream_weight');
            $table->string('quantity');
            $table->string('quantity_kg');
            $table->string('discount')->nullable();
            $table->string('remarks')->nullable();
            $table->string('job_card_weight')->default(0);
            $table->integer('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
