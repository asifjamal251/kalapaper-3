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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('from')->nullable()->constrained('parties')->nullOnDelete();
            $table->foreignId('bill_to')->nullable()->constrained('parties')->nullOnDelete();
            $table->foreignId('ship_to')->nullable()->constrained('parties')->nullOnDelete();
            $table->foreignId('consignee')->nullable()->constrained('parties')->nullOnDelete();
            $table->string('po_number')->nullable();
            $table->date('po_date')->nullable();
            $table->string('so_number')->nullable()->unique();
            $table->date('so_date')->nullable();
            $table->integer('status_id')->default(3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
