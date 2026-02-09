<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daily_stocks', function (Blueprint $table) {

            $table->id();

            $table->date('stock_date')->unique();

            /*
            |--------------------------------------------------------------------------
            | INWARD STOCK
            |--------------------------------------------------------------------------
            */

            $table->decimal('inward_opening',12,2)->default(0);
            $table->decimal('inward_received',12,2)->default(0);
            $table->decimal('inward_cancelled',12,2)->default(0);
            $table->decimal('inward_move_to_stock',12,2)->default(0);
            $table->decimal('inward_closing',12,2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | ON WAY STOCK
            |--------------------------------------------------------------------------
            */

            $table->decimal('onway_opening',12,2)->default(0);
            $table->decimal('onway_received',12,2)->default(0);
            $table->decimal('onway_closing',12,2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | JOB CARD
            |--------------------------------------------------------------------------
            */

            $table->decimal('jobcard_opening',12,2)->default(0);
            $table->decimal('jobcard_booked',12,2)->default(0);
            $table->decimal('jobcard_cancelled',12,2)->default(0);
            $table->decimal('jobcard_consumed',12,2)->default(0);
            $table->decimal('jobcard_closing',12,2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | PRODUCTION STAGES (WIP)
            |--------------------------------------------------------------------------
            */

            $table->decimal('cutting_opening',12,2)->default(0);
            $table->decimal('cutting_in',12,2)->default(0);
            $table->decimal('cutting_out',12,2)->default(0);
            $table->decimal('cutting_closing',12,2)->default(0);

            $table->decimal('finishing_opening',12,2)->default(0);
            $table->decimal('finishing_in',12,2)->default(0);
            $table->decimal('finishing_out',12,2)->default(0);
            $table->decimal('finishing_closing',12,2)->default(0);

            $table->decimal('bundling_opening',12,2)->default(0);
            $table->decimal('bundling_in',12,2)->default(0);
            $table->decimal('bundling_out',12,2)->default(0);
            $table->decimal('bundling_closing',12,2)->default(0);

            $table->decimal('wrapping_opening',12,2)->default(0);
            $table->decimal('wrapping_in',12,2)->default(0);
            $table->decimal('wrapping_out',12,2)->default(0);
            $table->decimal('wrapping_closing',12,2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | FINISHED GOODS
            |--------------------------------------------------------------------------
            */

            $table->decimal('fg_opening',12,2)->default(0);
            $table->decimal('fg_in',12,2)->default(0);
            $table->decimal('fg_out',12,2)->default(0);
            $table->decimal('fg_closing',12,2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | CHALLAN / DISPATCH
            |--------------------------------------------------------------------------
            */

            $table->decimal('challan_created',12,2)->default(0);
            $table->decimal('challan_dispatched',12,2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | TOTAL STOCK SUMMARY
            |--------------------------------------------------------------------------
            */

            $table->decimal('total_stock_opening',12,2)->default(0);
            $table->decimal('total_stock_closing',12,2)->default(0);

            $table->decimal('available_stock',12,2)->default(0);
            $table->decimal('available_onway_stock',12,2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_stocks');
    }
};