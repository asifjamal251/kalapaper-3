<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyStock extends Model
{
    protected $fillable = [

        'stock_date',

        'inward_opening',
        'inward_received',
        'inward_cancelled',
        'inward_move_to_stock',
        'inward_closing',

        'onway_opening',
        'onway_received',
        'onway_closing',

        'jobcard_opening',
        'jobcard_booked',
        'jobcard_cancelled',
        'jobcard_consumed',
        'jobcard_closing',

        'cutting_opening',
        'cutting_in',
        'cutting_out',
        'cutting_closing',

        'finishing_opening',
        'finishing_in',
        'finishing_out',
        'finishing_closing',

        'bundling_opening',
        'bundling_in',
        'bundling_out',
        'bundling_closing',

        'wrapping_opening',
        'wrapping_in',
        'wrapping_out',
        'wrapping_closing',

        'fg_opening',
        'fg_in',
        'fg_out',
        'fg_closing',

        'challan_created',
        'challan_dispatched',

        'total_stock_opening',
        'total_stock_closing',

        'available_stock',
        'available_onway_stock',
    ];

    protected $casts = [
        'stock_date' => 'date',
    ];

    public function scopeToday($query)
    {
        return $query->whereDate('stock_date', Carbon::today());
    }

    public static function today()
    {
        return static::firstOrCreate(
            ['stock_date' => now()->toDateString()],
            static::defaultOpening()
        );
    }

    protected static function defaultOpening()
    {
        $yesterday = static::latest('stock_date')->first();

        if (!$yesterday) {
            return [];
        }

        return [
            'inward_opening' => $yesterday->inward_closing,
            'onway_opening'  => $yesterday->onway_closing,
            'jobcard_opening'=> $yesterday->jobcard_closing,

            'cutting_opening'   => $yesterday->cutting_closing,
            'finishing_opening' => $yesterday->finishing_closing,
            'bundling_opening'  => $yesterday->bundling_closing,
            'wrapping_opening'  => $yesterday->wrapping_closing,

            'fg_opening' => $yesterday->fg_closing,

            'total_stock_opening' => $yesterday->total_stock_closing,
            'available_stock'     => $yesterday->available_stock,
            'available_onway_stock'=> $yesterday->available_onway_stock,
        ];
    }
}