<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('job_card_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_card_id')->constrained('job_cards')->cascadeOnDelete();
            $table->foreignId('inward_item_id')->nullable()->constrained('inward_items')->nullOnDelete();
            $table->foreignId('wastage_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('quality_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('item_number')->nullable();

            // Size
            $table->string('width_cm')->nullable();
            $table->string('width_inch')->nullable();
            $table->string('length_cm')->nullable();
            $table->string('length_inch')->nullable();

            $table->string('gsm')->nullable();
            $table->string('weight')->default(0);
            $table->string('handling_unit')->nullable();

            $table->string('trim')->default(0);
            $table->string('bundle_pack')->nullable();
            $table->string('sheet_per_ream')->nullable();
            $table->string('run_number')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_card_items');
    }
};