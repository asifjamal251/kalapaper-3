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
        Schema::create('inwards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('inward_number')->nullable();
            $table->string('challan_from')->nullable();
            $table->date('challan_date')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('e_way_bill_no')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('transport')->nullable();
            $table->integer('status_id')->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inwards');
    }
};
