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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Client', 'Vendor', 'Firm']);
            $table->string('username', 255)->unique()->nullable();
            $table->string('password', 255)->nullable();
            $table->string('password_plain', 255)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->json('email')->nullable(); 
            $table->json('contact_no')->nullable(); 
            $table->rememberToken();
            $table->integer('media_id')->nullable();
            $table->string('gst')->unique();
            $table->string('pincode', 10)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('district', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->text('address')->nullable();
            $table->integer('status_id')->default(14);
            $table->integer('stock_on_email')->default(15);
            $table->integer('allocated_stock_on_email')->default(15);
            $table->integer('login_status')->default(15);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
