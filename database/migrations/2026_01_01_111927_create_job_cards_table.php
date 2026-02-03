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
        Schema::create('job_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('ship_to')->nullable()->constrained('parties')->nullOnDelete();
            $table->foreignId('sold_to')->nullable()->constrained('parties')->nullOnDelete();
            $table->string('job_card_number')->nullable();
            $table->string('so_number')->nullable();
            $table->date('so_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('reference_date')->nullable();
            $table->string('purchase_by')->nullable();
            $table->string('total_weight')->nullable();
            $table->string('ready_weight')->nullable();
            $table->string('delivered_weight')->nullable();
            $table->string('excess')->nullable();
            $table->enum('type', ['Reel', 'Sheet'])->nullable();
            $table->enum('job_card_type', ['Normal', 'Jumbo'])->nullable();
            $table->integer('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_cards');
    }
};
