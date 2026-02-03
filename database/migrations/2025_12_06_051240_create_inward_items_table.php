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
        Schema::create('inward_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inward_id')->constrained('inwards')->onDelete('cascade');
            $table->foreignId('parent_inward_item_id')->nullable()->constrained('inward_items')->nullOnDelete();
            $table->foreignId('quality_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('job_card_id')->nullable();
            $table->unsignedBigInteger('job_card_item_id')->nullable();
            $table->bigInteger('gsm')->nullable();
            $table->string('width')->nullable();
            $table->string('allocation')->nullable();
            $table->string('weight')->nullable();
            $table->decimal('core_dia', 10, 2)->nullable();
            $table->bigInteger('reel_dia')->nullable();
            $table->string('batch')->nullable();
            $table->string('handling_unit')->unique();
            $table->date('stock_date')->nullable();
            $table->dateTime('booked_at')->nullable();
            $table->integer('status_id')->default(8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inward_items');
    }
};
