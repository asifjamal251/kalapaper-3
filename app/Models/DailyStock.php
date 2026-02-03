<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DailyStock extends Model
{
    protected $fillable = [
        'stock_date',

        'inward_received',
        'inward_onway',
        'inward_move_to_stock',
        'inward_cancelled',

        'job_card_booked',
        'job_card_cancelled',

        'allocated_booked',
        'allocated_remove',

        'available_stock',
        'available_onway_stock',
    ];

    protected $casts = [
        'stock_date' => 'date',
    ];

    function todayStock(){
        return DailyStock::today();
    }

    /**
     * Get or create today's stock row
     */
    public static function today(): self
    {
        return self::firstOrCreate(
            ['stock_date' => today()],
            self::openingBalance()
        );
    }

    /**
     * Copy yesterday closing stock
     */
    protected static function openingBalance(): array{
        $lastStock = self::whereDate('stock_date', '<', today())
            ->orderBy('stock_date', 'desc')
            ->first();

        return [
            'available_stock'        => $lastStock?->available_stock ?? 0,
            'available_onway_stock'  => $lastStock?->available_onway_stock ?? 0,
        ];
    }
}


// $stock = DailyStock::today();

// $stock->increment('allocated_booked', $qty);
// $stock->decrement('available_stock', $qty);
