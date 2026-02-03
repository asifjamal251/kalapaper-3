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
        Schema::create('firms', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 255)->nullable();
            $table->json('email')->nullable(); 
            $table->json('contact_no')->nullable(); 
            $table->integer('media_id')->nullable();
            $table->string('gst')->unique();
            $table->string('pincode', 10)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->text('address')->nullable();
            $table->integer('status_id')->default(14);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('firms');
    }
};
