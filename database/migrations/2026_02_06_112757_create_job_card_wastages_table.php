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
        Schema::create('job_card_wastages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained('job_cards')->cascadeOnDelete();
            $table->string('core_pipe')->nullable()->default(0.00);
            $table->string('ldp')->nullable()->default(0.00);
            $table->string('strip')->nullable()->default(0.00);
            $table->string('edge_guard')->nullable()->default(0.00);
            $table->string('core_plug')->nullable()->default(0.00);
            $table->string('side_disk')->default(1.10);
            $table->string('paper_broke')->default(0.00);
            $table->string('trim')->default(0.00);
            $table->string('second_sheets')->default(0.00);
            $table->string('diff_due_to_gsm')->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_card_wastages');
    }
};
