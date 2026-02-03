<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wastages', function (Blueprint $table) {
            $table->id();

            $table->string('width')->nullable()->default(0.00);
            $table->string('core_pipe')->nullable()->default(0.00);
            $table->string('edge_guard')->nullable()->default(0.00);
            $table->string('ldp')->nullable()->default(0.00);
            $table->string('strip')->nullable()->default(0.00);
            $table->string('core_plug')->nullable()->default(0.00);

            $table->string('side_disk')->default(1.10);
            $table->string('paper_broke')->default(0.00);

            $table->string('diff_due_to_gsm')->nullable()->default(0.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wastages');
    }
};